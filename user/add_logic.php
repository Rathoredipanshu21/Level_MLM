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

    // 1. Check Child Limit (Max 5)
    $countStmt = $pdo->prepare("SELECT count(*) FROM users WHERE parent_id = ?");
    $countStmt->execute([$parent_id]);
    $current_children = $countStmt->fetchColumn();

    if ($current_children >= 5) {
        echo "<script>alert('Error: You can only add 5 direct members.'); window.location='index.php';</script>";
        exit;
    }

    // 2. Check Depth Limit (Max Level 5)
    // Note: SM is Level 0. Max children can be Level 5.
    if ($parent['level'] >= 5) {
        echo "<script>alert('Error: Graph Completed. Cannot add below Level 5.'); window.location='index.php';</script>";
        exit;
    }

    $new_level = $parent['level'] + 1;

    try {
        $pdo->beginTransaction();

        // 3. Create User
        $insert = $pdo->prepare("INSERT INTO users (name, username, password, parent_id, level, role) VALUES (?, ?, ?, ?, ?, 'user')");
        $insert->execute([$name, $username, $password, $parent_id, $new_level]);
        $new_user_id = $pdo->lastInsertId();

        // 4. Commission Distribution Logic
        $reg_fee = getSetting($pdo, 'registration_fee');     // Default: 2500
        $master_cut = getSetting($pdo, 'master_cut');        // Default: 2000
        $comm_percent = getSetting($pdo, 'level_commission_percent'); // Default: 18
        
        // Step A: Pay Master Admin Fixed Amount
        $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE role = 'master'")->execute([$master_cut]);
        $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (1, ?, ?)")->execute([$master_cut, "Master Cut from new user: $username"]);

        // Step B: Distribute the remaining 500
        $distributable = $reg_fee - $master_cut; // 500
        $remaining_pool = $distributable;

        $current_upline_id = $parent_id;

        // Traverse UP the tree
        while ($current_upline_id) {
            
            $uplineStmt = $pdo->prepare("SELECT id, role, parent_id, username FROM users WHERE id = ?");
            $uplineStmt->execute([$current_upline_id]);
            $upline = $uplineStmt->fetch();

            if (!$upline) break;

            if ($upline['role'] == 'sm') {
                // RULE: If we reach Sales Manager (SM/Root), he gets EVERYTHING remaining in the pool.
                // This covers the "Rest 82% goes to SM" logic.
                if ($remaining_pool > 0) {
                    $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$remaining_pool, $upline['id']]);
                    $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$upline['id'], $remaining_pool, "Residual Commission from: $username"]);
                }
                break; // Stop loop at SM
            } else {
                // Regular Upline User
                // RULE: They get 18% of the distributable amount (18% of 500 = 90)
                $commission = ($distributable * $comm_percent) / 100;

                // Ensure we don't pay more than what's left
                if ($commission > $remaining_pool) {
                    $commission = $remaining_pool;
                }

                $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$commission, $upline['id']]);
                $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$upline['id'], $commission, "Level Commission from: $username"]);

                $remaining_pool -= $commission; // Deduct from pool
                
                // Move to next parent
                $current_upline_id = $upline['parent_id'];
            }
        }

        $pdo->commit();
        echo "<script>alert('New Member Added & Commission Distributed!'); window.location='index.php';</script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
}
?>