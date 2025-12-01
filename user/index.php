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

// Fetch User Details (including referral_id and pin)
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$current_user = $stmt->fetch();

// Fetch Direct Children (For limit of 5)
$stmt = $pdo->prepare("SELECT * FROM users WHERE parent_id = ?");
$stmt->execute([$user_id]);
$direct_team = $stmt->fetchAll();
$direct_count = count($direct_team);

// "Out of Game" Status
$is_limit_reached = ($direct_count >= 5);

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
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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

            <div class="bg-white p-6 rounded-2xl shadow-lg border-b-4 <?php echo $is_limit_reached ? 'border-red-500' : 'border-blue-500'; ?>" data-aos="fade-up" data-aos-delay="100">
                 <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Direct Associates</p>
                        <h2 class="text-3xl font-extrabold text-gray-800 mt-2"><?php echo $direct_count; ?> <span class="text-sm text-gray-400 font-normal">/ 5 Max</span></h2>
                        <?php if($is_limit_reached): ?>
                            <span class="inline-block mt-2 px-2 py-1 bg-red-100 text-red-600 text-xs font-bold rounded">Limit Reached</span>
                        <?php endif; ?>
                    </div>
                    <div class="<?php echo $is_limit_reached ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600'; ?> p-3 rounded-xl text-xl shadow-sm">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-4">
                    <div class="<?php echo $is_limit_reached ? 'bg-red-500' : 'bg-blue-500'; ?> h-1.5 rounded-full" style="width: <?php echo ($direct_count/5)*100; ?>%"></div>
                </div>
            </div>

            <?php if(!$is_limit_reached): ?>
                <div onclick="openModal()" class="bg-white cursor-pointer p-6 rounded-2xl shadow-lg border border-gray-100 hover:border-green-400 transition duration-300 flex flex-col items-center justify-center text-center group" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-green-50 p-3 rounded-full mb-3 group-hover:bg-green-100 transition text-green-600">
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-gray-700">Manual Entry</h3>
                    <p class="text-xs text-gray-400 mt-1">Register someone yourself</p>
                </div>
            <?php else: ?>
                <div class="bg-gray-100 p-6 rounded-2xl shadow-lg text-gray-400 border border-gray-200 flex flex-col items-center justify-center text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gray-200 p-3 rounded-full mb-3">
                        <i class="fas fa-ban text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-gray-500">Add Limit Reached</h3>
                    <p class="text-xs mt-1">You have filled your 5 direct slots. <br>Contact Admin for a new Level/ID.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="bg-gradient-to-r from-teal-600 to-emerald-600 rounded-2xl p-6 md:p-8 text-white shadow-xl mb-10 relative overflow-hidden" data-aos="zoom-in">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-black/10 rounded-full blur-2xl -ml-10 -mb-10 pointer-events-none"></div>

            <div class="relative z-10">
                <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <i class="fas fa-id-badge"></i> Your Sponsor Credentials
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-5">
                        <p class="text-teal-100 text-xs font-bold uppercase tracking-widest mb-2">Referral Code (Sponsor ID)</p>
                        <div class="flex items-center justify-between gap-4">
                            <?php if(!empty($current_user['referral_id'])): ?>
                                <span class="text-3xl font-mono font-bold tracking-wider" id="refCode"><?php echo htmlspecialchars($current_user['referral_id']); ?></span>
                                <button onclick="copyToClipboard('<?php echo $current_user['referral_id']; ?>')" class="bg-white/20 hover:bg-white hover:text-teal-700 text-white p-2 rounded-lg transition-all" title="Copy Code">
                                    <i class="fas fa-copy"></i>
                                </button>
                            <?php else: ?>
                                <span class="text-lg text-yellow-300 font-bold"><i class="fas fa-clock mr-2"></i> Generating...</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs text-teal-100 mt-2 opacity-80">Share this code with new members so they can join your team.</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-5">
                        <p class="text-teal-100 text-xs font-bold uppercase tracking-widest mb-2">Your Secret PIN</p>
                        <div class="flex items-center gap-3">
                             <?php if(!empty($current_user['pin'])): ?>
                                <span class="text-3xl font-mono font-bold tracking-wider"><?php echo htmlspecialchars($current_user['pin']); ?></span>
                                <i class="fas fa-lock text-white/50 text-xl"></i>
                            <?php else: ?>
                                <span class="text-lg text-yellow-300 font-bold">Pending Approval</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs text-teal-100 mt-2 opacity-80">Keep this PIN safe. You need it for secure transactions.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
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

    <div id="addModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm">
        <div class="modal-content bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden">
            
            <div class="bg-green-600 p-4 flex justify-between items-center text-white">
                <h3 class="font-bold text-lg">Manual Registration</h3>
                <button onclick="closeModal()" class="hover:bg-green-700 p-1 rounded transition"><i class="fas fa-times"></i></button>
            </div>

            <form action="add_logic.php" method="POST" class="p-6">
                
                <div class="bg-blue-50 text-blue-800 text-xs p-3 rounded mb-4">
                    <strong>Note:</strong> You can also share your Referral Code <b><?php echo htmlspecialchars($current_user['referral_id']); ?></b> for them to register themselves.
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Full Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Password</label>
                    <input type="text" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition" required>
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

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert("Referral Code Copied: " + text);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    </script>
</body>
</html>