<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Inputs
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $phone = $_POST['phone'];
    $aadhar_no = $_POST['aadhar_no'];
    $pan_no = $_POST['pan_no'];
    $txn_id = $_POST['txn_id'];
    $sponsor_code = trim($_POST['sponsor_code']); // The Unique Referral ID

    // 1. Verify Sponsor Code
    $stmt = $pdo->prepare("SELECT id, level FROM users WHERE referral_id = ?");
    $stmt->execute([$sponsor_code]);
    $parentData = $stmt->fetch();

    if (!$parentData) {
        die("<script>alert('Invalid Sponsor Referral Code! Please ask your sponsor for the correct code.'); window.history.back();</script>");
    }

    $sponsor_id = $parentData['id'];
    $new_level = $parentData['level'] + 1;

    // 2. Check Username Duplicate
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        die("<script>alert('Username already taken!'); window.history.back();</script>");
    }

    // 3. Handle File Uploads
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $aadhar_img_name = time() . '_aadhar_' . $_FILES['aadhar_img']['name'];
    $pan_img_name = time() . '_pan_' . $_FILES['pan_img']['name'];
    $pay_img_name = time() . '_pay_' . $_FILES['payment_screenshot']['name'];

    move_uploaded_file($_FILES['aadhar_img']['tmp_name'], $uploadDir . $aadhar_img_name);
    move_uploaded_file($_FILES['pan_img']['tmp_name'], $uploadDir . $pan_img_name);
    move_uploaded_file($_FILES['payment_screenshot']['tmp_name'], $uploadDir . $pay_img_name);

    // 4. Insert as Pending (NO referral_id or pin yet)
    try {
        $sql = "INSERT INTO users (name, username, password, phone, aadhar_no, pan_no, aadhar_img, pan_img, 
                payment_screenshot, txn_id, parent_id, level, role, payment_status, account_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'user', 'pending', 'inactive')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $name, $username, $password, $phone, $aadhar_no, $pan_no, 
            $aadhar_img_name, $pan_img_name, $pay_img_name, $txn_id, 
            $sponsor_id, $new_level
        ]);

        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.tailwindcss.com'></script>
        </head>
        <body class='bg-gray-100 flex items-center justify-center h-screen'>
            <div class='bg-white p-8 rounded-2xl shadow-xl max-w-md text-center'>
                <div class='w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600 text-3xl'>
                    <i class='fas fa-check'></i>
                </div>
                <h2 class='text-2xl font-bold text-gray-800 mb-2'>Registration Successful!</h2>
                <p class='text-gray-500 mb-6'>Your application is submitted under Sponsor Code: <b>$sponsor_code</b>. <br>Please wait for Admin approval to receive your PIN and Unique ID.</p>
                <div class='bg-blue-50 p-4 rounded text-blue-800 text-sm font-bold'>
                    Status: PENDING APPROVAL
                </div>
                <a href='index.php' class='block mt-6 text-green-600 hover:underline'>Return to Home</a>
            </div>
        </body>
        </html>
        ";

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>