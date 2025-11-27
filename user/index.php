<?php
session_start();
include '../config/db.php';

// Security Check
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'user' && $_SESSION['role'] != 'sm')) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

// Fetch User Details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$current_user = $stmt->fetch();

// Fetch Direct Children (For limit of 5)
$stmt = $pdo->prepare("SELECT * FROM users WHERE parent_id = ?");
$stmt->execute([$user_id]);
$direct_team = $stmt->fetchAll();
$direct_count = count($direct_team);

// Recursive Function to Draw Tree
function buildTree($pdo, $parentId) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE parent_id = ?");
    $stmt->execute([$parentId]);
    $children = $stmt->fetchAll();

    if (count($children) > 0) {
        echo '<ul class="ml-8 border-l-2 border-green-200 pl-6 mt-4 space-y-3 relative">';
        foreach ($children as $child) {
            echo '<li class="bg-white p-4 rounded-lg shadow-sm border border-green-50 flex items-center gap-3 relative transition hover:shadow-md" data-aos="fade-left">';
            // Decorative dash
            echo '<div class="absolute -left-6 top-1/2 w-6 h-0.5 bg-green-200"></div>'; 
            
            echo '<div class="bg-green-100 text-green-700 w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shadow-inner">L' . $child['level'] . '</div>';
            echo '<div>';
            echo '<p class="font-bold text-gray-700">' . htmlspecialchars($child['name']) . '</p>';
            echo '<p class="text-xs text-gray-400">User: ' . htmlspecialchars($child['username']) . '</p>';
            echo '</div>';
            
            // Recursive call
            buildTree($pdo, $child['id']);
            echo '</li>';
        }
        echo '</ul>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | <?php echo $user_name; ?></title>
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
<body class="bg-green-50 font-sans min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-40 px-4 py-3">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-green-600 text-white p-2 rounded-lg">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($user_name); ?></h1>
                    <p class="text-xs text-green-600 font-bold uppercase"><?php echo $current_user['role'] == 'sm' ? 'Sales Manager' : 'Level ' . $current_user['level'] . ' Associate'; ?></p>
                </div>
            </div>
            <a href="logout.php" class="text-gray-400 hover:text-red-500 transition px-3 py-2">
                <i class="fas fa-power-off text-xl"></i>
            </a>
        </div>
    </nav>

    <div class="container mx-auto p-4 lg:p-8">
        
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Wallet Card -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-green-500" data-aos="fade-up">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Earnings</p>
                        <h2 class="text-3xl font-extrabold text-gray-800 mt-2">₹ <?php echo number_format($current_user['wallet'], 2); ?></h2>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl text-green-600 text-xl shadow-sm">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                </div>
            </div>

            <!-- Team Card -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 border-blue-500" data-aos="fade-up" data-aos-delay="100">
                 <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Direct Associates</p>
                        <h2 class="text-3xl font-extrabold text-gray-800 mt-2"><?php echo $direct_count; ?> <span class="text-sm text-gray-400 font-normal">/ 5 Max</span></h2>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl text-blue-600 text-xl shadow-sm">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-4">
                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: <?php echo ($direct_count/5)*100; ?>%"></div>
                </div>
            </div>

            <!-- Add Button -->
            <div onclick="openModal()" class="bg-gradient-to-br from-green-500 to-green-700 cursor-pointer p-6 rounded-2xl shadow-lg text-white transform hover:-translate-y-1 transition duration-300 flex flex-col items-center justify-center text-center group" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white/20 p-3 rounded-full mb-3 group-hover:bg-white/30 transition">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg">Add New Member</h3>
                <p class="text-xs text-green-100 mt-1 opacity-80">Expand your network tree</p>
            </div>
        </div>

        <!-- Content Split -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Network Tree -->
            <div class="bg-white p-6 rounded-2xl shadow-xl lg:col-span-2 min-h-[500px]" data-aos="fade-right">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-network-wired text-green-500"></i> My Genealogy
                    </h3>
                </div>
                
                <div class="overflow-x-auto pb-4">
                    <div class="inline-block min-w-full">
                        <ul class="space-y-4">
                            <li class="bg-green-50 p-4 rounded-lg border-2 border-green-200 font-bold text-green-800 flex items-center gap-3 shadow-sm">
                                <div class="w-10 h-10 bg-green-200 rounded-full flex items-center justify-center text-green-700">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span>YOU (<?php echo $current_user['role'] == 'sm' ? 'Root' : 'Level ' . $current_user['level']; ?>)</span>
                            </li>
                            <?php buildTree($pdo, $user_id); ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Recent Earnings -->
            <div class="bg-white p-6 rounded-2xl shadow-xl h-fit" data-aos="fade-left">
                 <h3 class="text-lg font-bold text-gray-800 mb-6 pb-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fas fa-receipt text-gray-400"></i> Earning History
                 </h3>
                 
                 <div class="space-y-4">
                     <?php
                     $logs = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY id DESC LIMIT 8");
                     $logs->execute([$user_id]);
                     $has_logs = false;
                     while($row = $logs->fetch()):
                        $has_logs = true;
                     ?>
                     <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-green-50 transition border border-gray-100">
                        <div>
                            <p class="text-sm font-semibold text-gray-700 line-clamp-1" title="<?php echo htmlspecialchars($row['description']); ?>">
                                <?php echo htmlspecialchars($row['description']); ?>
                            </p>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wide mt-1"><?php echo date('M d, H:i', strtotime($row['date'])); ?></p>
                        </div>
                        <span class="font-bold text-green-600 text-sm bg-green-100 px-2 py-1 rounded">+<?php echo $row['amount']; ?></span>
                     </div>
                     <?php endwhile; ?>
                     
                     <?php if(!$has_logs): ?>
                        <div class="text-center py-10 text-gray-400">
                            <i class="fas fa-box-open text-3xl mb-2"></i>
                            <p class="text-sm">No earnings yet.</p>
                        </div>
                     <?php endif; ?>
                 </div>
            </div>
        </div>
    </div>

    <!-- ADD MEMBER MODAL -->
    <div id="addModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm">
        <div class="modal-content bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden">
            
            <div class="bg-green-600 p-4 flex justify-between items-center text-white">
                <h3 class="font-bold text-lg">Add New Associate</h3>
                <button onclick="closeModal()" class="hover:bg-green-700 p-1 rounded transition"><i class="fas fa-times"></i></button>
            </div>

            <form action="add_logic.php" method="POST" class="p-6">
                
                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Full Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition" placeholder="John Doe" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition" placeholder="john123" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Password</label>
                    <input type="text" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition" placeholder="SecretPass" required>
                    <p class="text-[10px] text-red-400 mt-1"><i class="fas fa-exclamation-circle"></i> Plain text password (No Hash)</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-6 flex items-start gap-3">
                    <i class="fas fa-info-circle text-yellow-500 mt-1"></i>
                    <div>
                        <p class="text-xs text-yellow-800 font-bold">Registration Fee Required</p>
                        <p class="text-xs text-yellow-700">Amount: ₹<?php echo getSetting($pdo, 'registration_fee'); ?></p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 text-gray-600 font-bold py-3 rounded-lg hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition shadow-lg">Register</button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        
        function openModal() {
            document.getElementById('addModal').classList.add('active');
        }
        function closeModal() {
            document.getElementById('addModal').classList.remove('active');
        }
    </script>
</body>
</html>