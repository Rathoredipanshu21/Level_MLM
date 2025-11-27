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

    // 2. Check Admin Child Limit (Max 5 for Master too, to be consistent)
    $countStmt = $pdo->prepare("SELECT count(*) FROM users WHERE parent_id = ?");
    $countStmt->execute([$parent_id]);
    if ($countStmt->fetchColumn() >= 5) {
        die("<script>alert('Limit Reached: You already have 5 direct Level 1 associates. Create a new root position?'); window.location='index.php';</script>");
    }

    // 3. Determine Level (New child of Master is Level 1)
    $new_level = 1;

    try {
        $pdo->beginTransaction();

        // 4. Create User
        $stmt = $pdo->prepare("INSERT INTO users (name, username, password, parent_id, level, role) VALUES (?, ?, ?, ?, ?, 'user')");
        $stmt->execute([$name, $username, $password, $parent_id, $new_level]);
        $new_user_id = $pdo->lastInsertId();

        // 5. Money Logic for Admin
        // Admin gets full fee added to wallet since there is no upline to pay.
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