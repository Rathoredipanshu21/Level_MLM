<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    
    $parent_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch Parent Data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$parent_id]);
    $parent = $stmt->fetch();

    // 1. Check Child Limit (Max 5 Direct Children - "Out of Game" Logic)
    $countStmt = $pdo->prepare("SELECT count(*) FROM users WHERE parent_id = ?");
    $countStmt->execute([$parent_id]);
    $current_children = $countStmt->fetchColumn();

    if ($current_children >= 5) {
        // This user is "Out of Game" for this ID.
        echo "<script>alert('Limit Reached: You have completed your 5 direct associates. Please contact Admin for a new Level/ID.'); window.location='index.php';</script>";
        exit;
    }

    $new_level = $parent['level'] + 1;

    try {
        $pdo->beginTransaction();

        // 2. Create User
        $insert = $pdo->prepare("INSERT INTO users (name, username, password, parent_id, level, role) VALUES (?, ?, ?, ?, ?, 'user')");
        $insert->execute([$name, $username, $password, $parent_id, $new_level]);
        $new_user_id = $pdo->lastInsertId();

        // 3. Commission Distribution Logic
        // ---------------------------------------------------
        $reg_fee = getSetting($pdo, 'registration_fee');      
        $master_cut = getSetting($pdo, 'master_cut');         
        $comm_percent = getSetting($pdo, 'level_commission_percent'); 
        
        $distributable_pot = $reg_fee - $master_cut; 
        
        // --- Step A: MASTER ADMIN (Fixed Cut) ---
        $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE role = 'master'")->execute([$master_cut]);
        $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (1, ?, ?)")->execute([$master_cut, "Master Cut from new user: $username"]);

        // --- Step B: SALES MANAGER (SM) - 10% ALWAYS ---
        // "admin's added sales Manager will get 10% always"
        $sm_amount = ($distributable_pot * 10) / 100;
        $sm_paid = false;
        
        $sm = $pdo->query("SELECT id FROM users WHERE role = 'sm'")->fetch();
        if ($sm) {
            $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$sm_amount, $sm['id']]);
            $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$sm['id'], $sm_amount, "SM Fixed 10% from: $username"]);
            $sm_paid = true;
        }

        // --- Step C: UPLINE COMMISSION (Immediate 5 Levels) ---
        // Logic: Pay immediate 5 ancestors. 
        // If Level 1 is NOT in those 5 (Deep Tree), Level 1 gets the Rest.
        // If Level 1 IS in those 5 (Shallow Tree), SM gets the Rest.

        $current_upline = $parent_id;
        $levels_processed = 0;
        $amount_per_level = ($distributable_pot * $comm_percent) / 100;
        
        $total_distributed_in_loop = 0;
        $level1_was_paid_in_loop = false;

        while ($current_upline && $levels_processed < 5) {
            $u = $pdo->query("SELECT id, level, parent_id, role FROM users WHERE id = $current_upline")->fetch();
            
            // Stop if we hit Top (Master) or SM
            if (!$u || $u['role'] == 'master' || $u['role'] == 'sm') break;

            // Pay the Upline
            $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$amount_per_level, $u['id']]);
            $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$u['id'], $amount_per_level, "Level Commission ($comm_percent%) from: $username"]);
            
            $total_distributed_in_loop += $amount_per_level;
            
            // Check if this was Level 1
            if ($u['level'] == 1) {
                $level1_was_paid_in_loop = true;
            }

            $levels_processed++;
            $current_upline = $u['parent_id'];
        }

        // --- Step D: REMAINDER LOGIC ---
        // Calculate what is left from the Pot after SM (10%) and Loop payments
        $remainder = $distributable_pot - $sm_amount - $total_distributed_in_loop;

        if ($remainder > 0) {
            if ($level1_was_paid_in_loop) {
                // CASE 1: SHALLOW TREE (e.g. L5 adding L6)
                // L1 was already paid 18% inside the loop.
                // "rest whatever the amount is rest then it will goes to SM"
                if ($sm) {
                    $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$remainder, $sm['id']]);
                    $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$sm['id'], $remainder, "Residual Commission (Shallow Tree) from: $username"]);
                }
            } else {
                // CASE 2: DEEP TREE (e.g. L8 adding L9)
                // L1 was NOT reached in the 5-level loop.
                // "rest whatever the %age is rest that will goes to level 1 always"
                // Find Level 1 Ancestor
                $temp_id = $parent_id;
                $level1_id = null;
                while($temp_id) {
                    $u = $pdo->query("SELECT id, level, parent_id, role FROM users WHERE id = $temp_id")->fetch();
                    if ($u['level'] == 1) {
                        $level1_id = $u['id'];
                        break;
                    }
                    if ($u['role'] == 'master') break;
                    $temp_id = $u['parent_id'];
                }

                if ($level1_id) {
                    $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$remainder, $level1_id]);
                    $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$level1_id, $remainder, "Residual Commission (Deep Tree) from: $username"]);
                }
            }
        }

        $pdo->commit();
        echo "<script>alert('New Member Added! Commissions Distributed.'); window.location='index.php';</script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
}
?>