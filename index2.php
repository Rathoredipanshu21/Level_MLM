

<?php
session_start();
include 'config/db.php';

$error = '';

// Login Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; 
    
    // We get the PIN from the form if it exists (only for users)
    $input_pin = isset($_POST['pin']) ? $_POST['pin'] : null;

    // Fetch User by Username & Password
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        
        // --- MASTER ADMIN LOGIN (No PIN Required) ---
        if ($user['role'] == 'master') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            header("Location: admin/index.php");
            exit;
        } 
        
        // --- MEMBER LOGIN (REQUIRES PIN) ---
        else {
            // 1. Check if Account is Active
            if ($user['account_status'] == 'inactive') {
                $error = "Account Inactive! Please wait for Admin to verify payment and generate your PIN.";
            } 
            // 2. Check if PIN exists in DB
            elseif (empty($user['pin'])) {
                $error = "PIN not generated yet! Please contact Admin.";
            }
            // 3. Verify Input PIN
            elseif ($input_pin !== $user['pin']) {
                $error = "Invalid PIN! Please enter the correct PIN sent by Admin.";
            } 
            // 4. Success
            else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                header("Location: user/index.php");
                exit;
            }
        }
    } else {
        $error = "Invalid Username or Password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ebiotheraphy | Secure Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        .modal { opacity: 0; visibility: hidden; transition: all 0.3s ease-in-out; }
        .modal.active { opacity: 1; visibility: visible; }
        .modal-content { transform: scale(0.95); transition: all 0.3s ease-in-out; }
        .modal.active .modal-content { transform: scale(1); }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen flex flex-col font-sans">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white w-10 h-10 rounded-lg flex items-center justify-center text-xl shadow-lg">
                    <i class="fas fa-leaf"></i>
                </div>
                <span class="text-xl font-bold text-gray-800 tracking-tight">ebio<span class="text-green-600">theraphy</span></span>
            </div>
            <div class="flex gap-4 items-center">
                <a href="register" class="text-sm font-bold text-green-600 border border-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition">Register Now</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow flex items-center justify-center p-6 relative overflow-hidden">
        
        <!-- Background Blobs -->
        <div class="absolute top-20 left-10 w-64 h-64 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-20 right-10 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="container mx-auto max-w-5xl relative z-10">
            
            <div class="text-center mb-12" data-aos="fade-down">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-4">Welcome to <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600">Ebiotheraphy Network</span></h1>
                <p class="text-gray-500 text-lg">Select your portal to continue</p>
            </div>

            <!-- Error Display -->
            <?php if($error): ?>
                <div class="max-w-md mx-auto bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-r shadow-md flex items-center gap-3" data-aos="shake">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                
                <!-- Admin Card -->
                <div class="group bg-white p-8 rounded-3xl shadow-xl border border-gray-100 hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 cursor-pointer relative overflow-hidden" onclick="openAdminModal()" data-aos="fade-right" data-aos-delay="100">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-inner group-hover:scale-110 transition duration-500">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Admin Portal</h2>
                    <p class="text-gray-500 mb-6 text-sm">Manage users, payments, and system settings.</p>
                    <button class="w-full py-3 px-6 rounded-xl bg-gray-50 text-blue-600 font-bold group-hover:bg-blue-600 group-hover:text-white transition duration-300">Admin Login</button>
                </div>

                <!-- User Card -->
                <div class="group bg-white p-8 rounded-3xl shadow-xl border border-gray-100 hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 cursor-pointer relative overflow-hidden" onclick="openUserModal()" data-aos="fade-left" data-aos-delay="200">
                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-inner group-hover:scale-110 transition duration-500">
                        <i class="fas fa-users"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Member Login</h2>
                    <p class="text-gray-500 mb-6 text-sm">Access your dashboard using your secure PIN.</p>
                    <button class="w-full py-3 px-6 rounded-xl bg-gray-50 text-green-600 font-bold group-hover:bg-green-600 group-hover:text-white transition duration-300">Member Login</button>
                </div>

            </div>
        </div>
    </div>

    <!-- ADMIN LOGIN MODAL -->
    <div id="adminModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm">
        <div class="modal-content bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gray-800 p-6 text-white flex justify-between items-center">
                <h3 class="font-bold text-xl">Admin Access</h3>
                <button onclick="closeModals()" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-8">
                <form method="POST">
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Username</label>
                        <input type="text" name="username" class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <button type="submit" class="w-full bg-gray-800 text-white font-bold py-3 rounded-xl hover:bg-black transition">Access Dashboard</button>
                </form>
            </div>
        </div>
    </div>

    <!-- USER LOGIN MODAL (WITH PIN) -->
    <div id="userModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm">
        <div class="modal-content bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 p-6 text-white flex justify-between items-center">
                <h3 class="font-bold text-xl">Member Login</h3>
                <button onclick="closeModals()" class="text-green-100 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-8">
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Username</label>
                        <input type="text" name="username" class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-green-500 outline-none" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-green-500 outline-none" required>
                    </div>
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Security PIN</label>
                        <input type="text" name="pin" class="w-full px-4 py-3 rounded-lg border border-green-200 bg-green-50 focus:ring-2 focus:ring-green-500 outline-none tracking-widest font-mono text-center" placeholder="EBIOxxxxx" required>
                        <p class="text-[10px] text-gray-400 mt-1 text-center">Enter the PIN sent by Admin</p>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white font-bold py-3 rounded-xl hover:from-green-700 hover:to-teal-700 transition">Login to Account</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        function openAdminModal() { closeModals(); document.getElementById('adminModal').classList.add('active'); }
        function openUserModal() { closeModals(); document.getElementById('userModal').classList.add('active'); }
        function closeModals() { document.querySelectorAll('.modal').forEach(m => m.classList.remove('active')); }
    </script>
</body>
</html>