<?php
session_start();
include '../config/db.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'master') {
    header("Location: ../index.php");
    exit;
}

// Update Settings Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_settings'])) {
    $reg_fee = $_POST['reg_fee'];
    $master_cut = $_POST['master_cut'];
    $comm_percent = $_POST['comm_percent'];

    $pdo->prepare("UPDATE settings SET meta_value = ? WHERE meta_key = 'registration_fee'")->execute([$reg_fee]);
    $pdo->prepare("UPDATE settings SET meta_value = ? WHERE meta_key = 'master_cut'")->execute([$master_cut]);
    $pdo->prepare("UPDATE settings SET meta_value = ? WHERE meta_key = 'level_commission_percent'")->execute([$comm_percent]);
    $success_msg = "Settings updated successfully!";
}

// Fetch Data
$master_wallet = $pdo->query("SELECT wallet FROM users WHERE role = 'master'")->fetchColumn();
$total_users = $pdo->query("SELECT count(*) FROM users WHERE role = 'user'")->fetchColumn();
$transactions = $pdo->query("SELECT * FROM transactions WHERE user_id = 1 ORDER BY id DESC LIMIT 10")->fetchAll();

// Fetch Admin's Direct Children Count (For the card)
$admin_children = $pdo->prepare("SELECT count(*) FROM users WHERE parent_id = ?");
$admin_children->execute([$_SESSION['user_id']]);
$my_directs = $admin_children->fetchColumn();

$reg_fee = getSetting($pdo, 'registration_fee');
$master_cut = getSetting($pdo, 'master_cut');
$comm_percent = getSetting($pdo, 'level_commission_percent');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin | Control Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        .modal { opacity: 0; visibility: hidden; transition: all 0.3s ease-in-out; }
        .modal.active { opacity: 1; visibility: visible; }
        .modal-content { transform: scale(0.9); transition: all 0.3s ease-in-out; }
        .modal.active .modal-content { transform: scale(1); }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-yellow-500 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg">
                    <i class="fas fa-crown"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Master Admin</h1>
            </div>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-full shadow transition transform hover:scale-105">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </a>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Wallet -->
            <div class="bg-gradient-to-r from-green-600 to-green-500 p-6 rounded-2xl shadow-xl text-white md:col-span-2" data-aos="fade-up">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm uppercase tracking-wider font-semibold">Total Master Wallet</p>
                        <h2 class="text-4xl font-bold mt-2">₹ <?php echo number_format($master_wallet, 2); ?></h2>
                    </div>
                    <div class="text-5xl opacity-30"><i class="fas fa-wallet"></i></div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white p-6 rounded-2xl shadow-xl border-l-4 border-blue-500" data-aos="fade-up" data-aos-delay="100">
                 <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm uppercase tracking-wider font-semibold">Total Users</p>
                        <h2 class="text-3xl font-bold mt-2 text-gray-800"><?php echo $total_users; ?></h2>
                    </div>
                    <div class="text-4xl text-blue-200"><i class="fas fa-users"></i></div>
                </div>
            </div>

            <!-- Tree View Link -->
            <a href="tree.php" class="bg-white p-6 rounded-2xl shadow-xl border-l-4 border-purple-500 hover:bg-purple-50 transition cursor-pointer" data-aos="fade-up" data-aos-delay="200">
                 <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm uppercase tracking-wider font-semibold">Network Tree</p>
                        <h2 class="text-xl font-bold mt-2 text-purple-700">View Full Graph <i class="fas fa-arrow-right text-xs ml-1"></i></h2>
                    </div>
                    <div class="text-4xl text-purple-200"><i class="fas fa-network-wired"></i></div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Settings & Add Member -->
            <div class="lg:col-span-1 space-y-8">
                
                <!-- Add Direct Associate (Admin's Branch) -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 text-white p-6 rounded-2xl shadow-lg" data-aos="fade-right">
                    <h3 class="text-xl font-bold mb-2"><i class="fas fa-user-plus mr-2"></i>Add Direct Associate</h3>
                    <p class="text-gray-400 text-sm mb-4">You have <b><?php echo $my_directs; ?></b> direct Level 1 associates.</p>
                    <button onclick="openModal()" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition shadow-md transform hover:scale-[1.02]">
                        Add New Member
                    </button>
                </div>

                <!-- Settings Form -->
                <div class="bg-white p-8 rounded-2xl shadow-lg" data-aos="fade-right" data-aos-delay="100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-cogs text-gray-500"></i> Dynamic Fee Control
                    </h3>
                    
                    <?php if(isset($success_msg)): ?>
                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm font-bold text-center">
                            <?php echo $success_msg; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Registration Fee (₹)</label>
                            <input type="number" name="reg_fee" value="<?php echo $reg_fee; ?>" class="w-full bg-gray-50 border border-gray-300 rounded p-3 focus:border-green-500 focus:outline-none transition">
                        </div>
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Master Cut Amount (₹)</label>
                            <input type="number" name="master_cut" value="<?php echo $master_cut; ?>" class="w-full bg-gray-50 border border-gray-300 rounded p-3 focus:border-green-500 focus:outline-none transition">
                        </div>
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Level Commission (%)</label>
                            <input type="number" name="comm_percent" value="<?php echo $comm_percent; ?>" class="w-full bg-gray-50 border border-gray-300 rounded p-3 focus:border-green-500 focus:outline-none transition">
                        </div>
                        <button type="submit" name="update_settings" class="w-full bg-gray-800 text-white font-bold py-3 rounded hover:bg-black transition shadow-lg">
                            Update Settings
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Transaction Logs -->
            <div class="bg-white p-8 rounded-2xl shadow-lg lg:col-span-2" data-aos="fade-left">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-list-alt text-gray-500"></i> Incoming Payments Log
                    </h3>
                    <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded">Latest 10</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm uppercase">
                                <th class="p-3 rounded-l-lg">ID</th>
                                <th class="p-3">Description</th>
                                <th class="p-3">Amount</th>
                                <th class="p-3 rounded-r-lg">Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach($transactions as $t): ?>
                            <tr class="border-b border-gray-100 hover:bg-green-50 transition">
                                <td class="p-3 font-mono text-gray-600">#<?php echo $t['id']; ?></td>
                                <td class="p-3 text-gray-700"><?php echo $t['description']; ?></td>
                                <td class="p-3 font-bold text-green-600">+₹<?php echo number_format($t['amount'], 2); ?></td>
                                <td class="p-3 text-gray-400 text-xs"><?php echo $t['date']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ADMIN ADD MEMBER MODAL -->
    <div id="adminAddModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm">
        <div class="modal-content bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden">
            
            <div class="bg-gray-800 p-4 flex justify-between items-center text-white">
                <h3 class="font-bold text-lg"><i class="fas fa-plus-circle mr-2"></i>Add Direct Associate</h3>
                <button onclick="closeModal()" class="hover:bg-gray-700 p-1 rounded transition"><i class="fas fa-times"></i></button>
            </div>

            <form action="add_logic.php" method="POST" class="p-6">
                
                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Full Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition" placeholder="Enter Full Name" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition" placeholder="Enter Username" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Password</label>
                    <input type="text" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition" placeholder="Enter Password" required>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-6">
                    <p class="text-xs text-blue-800"><i class="fas fa-info-circle mr-1"></i> This user will be added directly under <strong>Master Admin</strong> as Level 1.</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 text-gray-600 font-bold py-3 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-gray-800 text-white font-bold py-3 rounded-lg hover:bg-gray-900 transition shadow-lg">Create Associate</button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        function openModal() { document.getElementById('adminAddModal').classList.add('active'); }
        function closeModal() { document.getElementById('adminAddModal').classList.remove('active'); }
    </script>
</body>
</html>