<?php
session_start();
include '../config/db.php';

// Security
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'master') { header("Location: ../index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bank_info = $_POST['bank_info'];
    
    // Update Info
    $pdo->prepare("UPDATE bank_details SET bank_info = ? WHERE id = 1")->execute([$bank_info]);

    // Handle QR Upload
    if (!empty($_FILES['qr_code']['name'])) {
        $qrName = 'qr_' . time() . '.png';
        move_uploaded_file($_FILES['qr_code']['tmp_name'], 'uploads/' . $qrName);
        $pdo->prepare("UPDATE bank_details SET qr_code = ? WHERE id = 1")->execute([$qrName]);
    }

    $success = "Bank Details Updated!";
}

$bank = $pdo->query("SELECT * FROM bank_details WHERE id = 1")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bank Settings | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-100 font-sans">
    
    <!-- Navbar (Simplified) -->
    <nav class="bg-white shadow p-4 mb-8">
        <div class="container mx-auto flex justify-between">
            <h1 class="font-bold text-xl">Admin Panel</h1>
            <a href="index.php" class="text-blue-600">Back to Dashboard</a>
        </div>
    </nav>

    <div class="container mx-auto max-w-2xl p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 border-b pb-2">Manage Payment Details</h2>

        <?php if(isset($success)) echo "<p class='bg-green-100 text-green-700 p-3 rounded mb-4'>$success</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Bank Details (Text)</label>
                <textarea name="bank_info" rows="5" class="w-full border p-3 rounded-lg focus:outline-none focus:border-blue-500" required><?php echo $bank['bank_info']; ?></textarea>
                <p class="text-xs text-gray-400 mt-1">Enter Bank Name, Account No, IFSC, etc.</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Current QR Code</label>
                <div class="flex items-center gap-4 mb-2">
                    <img src="uploads/<?php echo $bank['qr_code']; ?>" class="w-24 h-24 object-cover border rounded">
                </div>
                <input type="file" name="qr_code" class="w-full border p-2 rounded">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">Save Changes</button>
        </form>
    </div>
</body>
</html>