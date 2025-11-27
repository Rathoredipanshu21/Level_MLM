<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] == 'master') {
    
    $parent_id = $_SESSION['user_id']; // This is 1 (Master)
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Check if username exists
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$username]);
    if ($check->rowCount() > 0) {
        die("<script>alert('Username already exists!'); window.location='index.php';</script>");
    }

    // 2. Check Admin Child Limit (Optional? Usually Admin has no limit, but sticking to 5 for consistency if you want)
    // We will apply the same logic: Max 5 direct legs for Master too.
    $countStmt = $pdo->prepare("SELECT count(*) FROM users WHERE parent_id = ?");
    $countStmt->execute([$parent_id]);
    if ($countStmt->fetchColumn() >= 5) {
        die("<script>alert('Limit Reached: You already have 5 direct Level 1 associates.'); window.location='index.php';</script>");
    }

    // 3. Determine Level
    // If Master is effectively Level 0, new child is Level 1.
    // If Master was -1, new child is 0. 
    // Based on user prompt "Level 1 Associate", we assume Master is 0.
    $new_level = 1;

    try {
        $pdo->beginTransaction();

        // 4. Create User
        $stmt = $pdo->prepare("INSERT INTO users (name, username, password, parent_id, level, role) VALUES (?, ?, ?, ?, ?, 'user')");
        $stmt->execute([$name, $username, $password, $parent_id, $new_level]);
        $new_user_id = $pdo->lastInsertId();

        // 5. Money Logic
        // Since Admin IS the parent AND the Master:
        // Total Fee = 2500.
        // Master Cut = 2000 (Goes to self).
        // Commission = 500 (Goes to Parent... which is self).
        // So Admin gets the full 2500 added to wallet.
        
        $reg_fee = getSetting($pdo, 'registration_fee');

        $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$reg_fee, $parent_id]);
        $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$parent_id, $reg_fee, "Direct Registration Fee from: $username"]);

        $pdo->commit();
        echo "<script>alert('New Direct Associate Added Successfully!'); window.location='index.php';</script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
}
?>