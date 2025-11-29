<?php
session_start();
include 'config/db.php';

$error = '';

// Login Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    // Secure Login Check
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        // Role-Based Redirect
        if ($user['role'] == 'master') {
            header("Location: admin/index.php");
        } else {
            header("Location: user/index.php");
        }
        exit;
    } else {
        $error = "Invalid Credentials. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLM Portal | Secure Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .modal {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }
        .modal.active {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            transform: scale(0.95);
            transition: all 0.3s ease-in-out;
        }
        .modal.active .modal-content {
            transform: scale(1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen flex flex-col font-sans">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white w-10 h-10 rounded-lg flex items-center justify-center text-xl shadow-lg">
                    <i class="fas fa-network-wired"></i>
                </div>
                <span class="text-xl font-bold text-gray-800 tracking-tight">E<span class="text-green-600">Biotherapy</span></span>
            </div>
            <div class="text-sm text-gray-500 font-medium">
                <i class="fas fa-lock text-green-500 mr-1"></i> Secure Gateway
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow flex items-center justify-center p-6 relative overflow-hidden">
        
        <!-- Background Elements -->
        <div class="absolute top-20 left-10 w-64 h-64 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-20 right-10 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="container mx-auto max-w-5xl relative z-10">
            
            <div class="text-center mb-12" data-aos="fade-down">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-4">Welcome to Your <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600">Digital Success Network</span></h1>
                <p class="text-gray-500 text-lg">Select your portal to continue to your dashboard</p>
            </div>

            <!-- Error Message -->
            <?php if($error): ?>
                <div class="max-w-md mx-auto bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-r shadow-md flex items-center gap-3" data-aos="shake">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                
                <!-- Admin Card -->
                <div class="group bg-white p-8 rounded-3xl shadow-xl border border-gray-100 hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 cursor-pointer relative overflow-hidden" onclick="openAdminModal()" data-aos="fade-right" data-aos-delay="100">
                    <div class="absolute top-0 right-0 bg-blue-500 w-24 h-24 rounded-bl-full opacity-10 group-hover:opacity-20 transition"></div>
                    
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-inner group-hover:scale-110 transition duration-500">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Admin Portal</h2>
                    <p class="text-gray-500 mb-6 text-sm leading-relaxed">Access system controls, manage genealogy trees, handle payouts, and configure global settings.</p>
                    
                    <button class="w-full py-3 px-6 rounded-xl bg-gray-50 text-blue-600 font-bold group-hover:bg-blue-600 group-hover:text-white transition duration-300 flex items-center justify-center gap-2">
                        Admin Login <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

                <!-- User Card -->
                <div class="group bg-white p-8 rounded-3xl shadow-xl border border-gray-100 hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 cursor-pointer relative overflow-hidden" onclick="openUserModal()" data-aos="fade-left" data-aos-delay="200">
                    <div class="absolute top-0 right-0 bg-green-500 w-24 h-24 rounded-bl-full opacity-10 group-hover:opacity-20 transition"></div>
                    
                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-inner group-hover:scale-110 transition duration-500">
                        <i class="fas fa-users"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Member Login</h2>
                    <p class="text-gray-500 mb-6 text-sm leading-relaxed">View your earnings, check your downline structure, and manage your profile details.</p>
                    
                    <button class="w-full py-3 px-6 rounded-xl bg-gray-50 text-green-600 font-bold group-hover:bg-green-600 group-hover:text-white transition duration-300 flex items-center justify-center gap-2">
                        User Login <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-6 text-gray-400 text-sm">
        <p>&copy; <?php echo date('Y'); ?> MLM Software. All rights reserved.</p>
    </footer>

    <!-- ================= MODALS ================= -->

    <!-- ADMIN LOGIN MODAL -->
    <div id="adminModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm">
        <div class="modal-content bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gray-800 p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-shield text-2xl text-blue-400"></i>
                    <h3 class="font-bold text-xl">Admin Access</h3>
                </div>
                <button onclick="closeModals()" class="text-gray-400 hover:text-white transition"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-8">
                <form method="POST">
                    <div class="mb-5">
                        <label class="block text-gray-600 text-xs font-bold uppercase tracking-wider mb-2">Admin Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-id-badge"></i></span>
                            <input type="text" name="username" class="w-full pl-10 px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" placeholder="Enter Admin ID" required>
                        </div>
                    </div>
                    <div class="mb-8">
                        <label class="block text-gray-600 text-xs font-bold uppercase tracking-wider mb-2">Secure Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-key"></i></span>
                            <input type="password" name="password" class="w-full pl-10 px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" placeholder="••••••••" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gray-800 text-white font-bold py-3 rounded-xl hover:bg-black transition shadow-lg transform active:scale-95">
                        Access Dashboard
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- USER LOGIN MODAL -->
    <div id="userModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm">
        <div class="modal-content bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 p-6 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user text-2xl text-green-100"></i>
                    <h3 class="font-bold text-xl">Member Login</h3>
                </div>
                <button onclick="closeModals()" class="text-green-100 hover:text-white transition"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-8">
                <form method="POST">
                    <div class="mb-5">
                        <label class="block text-gray-600 text-xs font-bold uppercase tracking-wider mb-2">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-user"></i></span>
                            <input type="text" name="username" class="w-full pl-10 px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none transition" placeholder="Enter Username" required>
                        </div>
                    </div>
                    <div class="mb-8">
                        <label class="block text-gray-600 text-xs font-bold uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="w-full pl-10 px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none transition" placeholder="••••••••" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white font-bold py-3 rounded-xl hover:shadow-lg hover:from-green-700 hover:to-teal-700 transition transform active:scale-95">
                        Login to Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        function openAdminModal() {
            closeModals();
            document.getElementById('adminModal').classList.add('active');
        }

        function openUserModal() {
            closeModals();
            document.getElementById('userModal').classList.add('active');
        }

        function closeModals() {
            document.querySelectorAll('.modal').forEach(m => m.classList.remove('active'));
        }

        // Close on clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModals();
            });
        });
    </script>
</body>
</html>