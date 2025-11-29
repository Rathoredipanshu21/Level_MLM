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
        $reg_fee = getSetting($pdo, 'registration_fee');      // 2500
        $master_cut = getSetting($pdo, 'master_cut');         // 2000
        $comm_percent = getSetting($pdo, 'level_commission_percent'); // 18%
        
        $distributable_pot = $reg_fee - $master_cut; // 500 (Total to distribute)
        $total_distributed = 0;
        
        // --- Step A: MASTER ADMIN (Fixed Cut) ---
        $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE role = 'master'")->execute([$master_cut]);
        $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (1, ?, ?)")->execute([$master_cut, "Master Cut from new user: $username"]);

        // --- Step B: UPLINE COMMISSION (Immediate 5 Levels) ---
        // Logic: Pay immediate 5 ancestors 18% each (90 Rs).
        
        $current_upline = $parent_id;
        $levels_processed = 0;
        $amount_per_level = ($distributable_pot * $comm_percent) / 100; // 90 Rs
        
        while ($current_upline && $levels_processed < 5) {
            $u = $pdo->query("SELECT id, level, parent_id, role FROM users WHERE id = $current_upline")->fetch();
            
            // Stop if we hit Top (Master)
            if (!$u || $u['role'] == 'master') break;

            // Pay the Upline
            $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$amount_per_level, $u['id']]);
            $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$u['id'], $amount_per_level, "Level Commission ($comm_percent%) from: $username"]);
            
            $total_distributed += $amount_per_level;
            $levels_processed++;
            $current_upline = $u['parent_id'];
        }

        // --- Step C: REMAINDER LOGIC (Goes to Level 1) ---
        // "rest 50 rs will goes directly to that level 1 parent who added and started the chain"
        
        $remainder = $distributable_pot - $total_distributed;

        if ($remainder > 0) {
            // Find the SPECIFIC Level 1 Ancestor for this branch
            $temp_id = $parent_id;
            $level1_id = null;
            
            // Traverse up until we find Level 1 or hit Master
            while($temp_id) {
                $u = $pdo->query("SELECT id, level, parent_id, role FROM users WHERE id = $temp_id")->fetch();
                
                if ($u['level'] == 1) {
                    $level1_id = $u['id'];
                    break;
                }
                
                if ($u['role'] == 'master') break;
                $temp_id = $u['parent_id'];
            }

            // Pay the Level 1 Ancestor the remainder (Bonus)
            if ($level1_id) {
                $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$remainder, $level1_id]);
                $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$level1_id, $remainder, "Chain Starter Bonus (Remainder) from: $username"]);
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