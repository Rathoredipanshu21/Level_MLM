<?php
session_start();
include '../config/db.php';

// Helper function to get settings (if not already in db.php)
if (!function_exists('getSetting')) {
    function getSetting($pdo, $key) {
        $stmt = $pdo->prepare("SELECT meta_value FROM settings WHERE meta_key = ?");
        $stmt->execute([$key]);
        return $stmt->fetchColumn();
    }
}

// Handle Approval
if (isset($_POST['approve_user_id'])) {
    $uid = $_POST['approve_user_id'];
    
    // 1. Fetch User Data (Need parent_id for commissions)
    $stmt = $pdo->prepare("SELECT id, name, username, parent_id, level FROM users WHERE id = ?");
    $stmt->execute([$uid]);
    $userData = $stmt->fetch();

    if ($userData) {
        $username = $userData['username'];
        $parent_id = $userData['parent_id'];
        $user_level = $userData['level'];

        // 2. Generate Credentials
        // Unique Referral ID: EBIO + Level + Random 4 digits (e.g., EBIO3-9482)
        $unique_ref_id = "EBIO" . $user_level . "-" . rand(1000, 9999);
        // Secure PIN (6 Digits)
        $secure_pin = rand(100000, 999999);

        try {
            // START TRANSACTION (Money matters!)
            $pdo->beginTransaction();

            // 3. Update User Status & Credentials
            $upd = $pdo->prepare("UPDATE users SET account_status='active', payment_status='approved', referral_id=?, pin=? WHERE id=?");
            $upd->execute([$unique_ref_id, $secure_pin, $uid]);

            // ---------------------------------------------------
            // 4. COMMISSION DISTRIBUTION LOGIC
            // ---------------------------------------------------
            
            // A. Fetch Settings
            $reg_fee = getSetting($pdo, 'registration_fee');               // e.g., 2500
            $master_cut = getSetting($pdo, 'master_cut');                  // e.g., 2000
            $comm_percent = getSetting($pdo, 'level_commission_percent');  // e.g., 18
            
            $distributable_pot = $reg_fee - $master_cut; // e.g., 500
            $total_distributed = 0;

            // B. Pay Master Admin (Fixed Cut)
            $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE role = 'master'")->execute([$master_cut]);
            $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (1, ?, ?)")->execute([$master_cut, "Master Cut from new user: $username"]);

            // C. Pay Upline (Immediate 5 Levels)
            $current_upline = $parent_id;
            $levels_processed = 0;
            $amount_per_level = ($distributable_pot * $comm_percent) / 100; // e.g., 90

            while ($current_upline && $levels_processed < 5) {
                // Fetch upline details
                $u = $pdo->query("SELECT id, level, parent_id, role FROM users WHERE id = $current_upline")->fetch();
                
                // Stop if we hit Top (Master) or invalid user
                if (!$u || $u['role'] == 'master') break;

                // Pay Commission
                $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$amount_per_level, $u['id']]);
                
                // Log Transaction
                $desc = "Level Commission ($comm_percent%) from new member: $username";
                $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$u['id'], $amount_per_level, $desc]);
                
                $total_distributed += $amount_per_level;
                $levels_processed++;
                $current_upline = $u['parent_id']; // Move up
            }

            // D. Remainder Logic (Goes to Level 1 Ancestor)
            $remainder = $distributable_pot - $total_distributed;

            if ($remainder > 0) {
                // Find the specific Level 1 Ancestor for this branch
                $temp_id = $parent_id;
                $level1_id = null;
                
                // Traverse up
                while($temp_id) {
                    $u = $pdo->query("SELECT id, level, parent_id, role FROM users WHERE id = $temp_id")->fetch();
                    if (!$u) break;

                    if ($u['level'] == 1) {
                        $level1_id = $u['id'];
                        break; // Found him
                    }
                    
                    if ($u['role'] == 'master') break; // Hit master, stop
                    $temp_id = $u['parent_id'];
                }

                // Pay Remainder
                if ($level1_id) {
                    $pdo->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?")->execute([$remainder, $level1_id]);
                    $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")->execute([$level1_id, $remainder, "Chain Starter Bonus (Remainder) from: $username"]);
                }
            }

            // ALL GOOD - COMMIT
            $pdo->commit();
            echo "<script>alert('User Approved! Commission Distributed.\\nUnique ID: $unique_ref_id\\nPIN: $secure_pin'); window.location='approve_members.php';</script>";

        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        }
    }
}

// Fetch Pending Users for Display
$stmt = $pdo->prepare("SELECT * FROM users WHERE account_status = 'inactive' ORDER BY id DESC");
$stmt->execute();
$pending_users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Members</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-50 p-6 font-sans">

    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-2" data-aos="fade-right">Pending Approvals</h1>
        <p class="text-gray-500 mb-8" data-aos="fade-right" data-aos-delay="100">Review applications and generate access keys.</p>

        <?php if (count($pending_users) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($pending_users as $u): ?>
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition transform hover:-translate-y-1" data-aos="fade-up">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-xl text-gray-800"><?php echo htmlspecialchars($u['name']); ?></h3>
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded font-bold uppercase tracking-wide">Pending</span>
                        </div>
                        <div class="space-y-2 mb-6">
                            <p class="text-gray-500 text-sm flex items-center"><i class="fas fa-user w-6 text-teal-500"></i> <?php echo htmlspecialchars($u['username']); ?></p>
                            <p class="text-gray-500 text-sm flex items-center"><i class="fas fa-phone w-6 text-teal-500"></i> <?php echo htmlspecialchars($u['phone']); ?></p>
                            <p class="text-gray-500 text-sm flex items-center"><i class="fas fa-calendar w-6 text-teal-500"></i> <?php echo date('d M Y', strtotime($u['created_at'])); ?></p>
                        </div>
                        
                        <button onclick="openModal(<?php echo htmlspecialchars(json_encode($u)); ?>)" 
                                class="w-full bg-gradient-to-r from-teal-600 to-green-600 text-white py-2.5 rounded-lg hover:from-teal-700 hover:to-green-700 transition font-semibold shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-eye"></i> View & Approve
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-xl shadow-sm border border-gray-100" data-aos="zoom-in">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-check text-4xl text-green-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">All Caught Up!</h3>
                <p class="text-gray-500">No pending membership applications.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- DETAILS MODAL -->
    <div id="detailsModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/80 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-all duration-300 m-4 max-h-[90vh] flex flex-col" id="modalContent">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 p-6 flex justify-between items-center text-white shrink-0">
                <div>
                    <h2 class="text-2xl font-bold">Applicant Details</h2>
                    <p class="text-xs text-gray-400">Verify documents before approval</p>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition"><i class="fas fa-times text-2xl"></i></button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-8 overflow-y-auto custom-scrollbar">
                
                <!-- Info Column -->
                <div class="space-y-6">
                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                        <h3 class="text-blue-800 font-bold mb-3 flex items-center gap-2"><i class="fas fa-info-circle"></i> Personal Info</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b border-blue-100 pb-2">
                                <span class="text-gray-500">Full Name</span>
                                <span class="font-bold text-gray-800" id="m_name"></span>
                            </div>
                            <div class="flex justify-between border-b border-blue-100 pb-2">
                                <span class="text-gray-500">Username</span>
                                <span class="font-bold text-gray-800" id="m_username"></span>
                            </div>
                            <div class="flex justify-between border-b border-blue-100 pb-2">
                                <span class="text-gray-500">Phone</span>
                                <span class="font-bold text-gray-800" id="m_phone"></span>
                            </div>
                            <div class="flex justify-between border-b border-blue-100 pb-2">
                                <span class="text-gray-500">Proposed Level</span>
                                <span class="font-bold text-blue-600" id="m_level"></span>
                            </div>
                            <div class="flex justify-between pt-1">
                                <span class="text-gray-500">Sponsor ID</span>
                                <span class="font-bold text-gray-800" id="m_sponsor"></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                         <h3 class="text-gray-700 font-bold mb-3 flex items-center gap-2"><i class="fas fa-id-card"></i> Government IDs</h3>
                         <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Aadhar No</span>
                                <span class="font-bold text-gray-800" id="m_aadhar"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">PAN No</span>
                                <span class="font-bold text-gray-800" id="m_pan"></span>
                            </div>
                         </div>
                    </div>

                    <div class="bg-green-50 p-5 rounded-xl border border-green-100">
                        <p class="text-xs font-bold text-green-600 uppercase mb-1">Payment Reference</p>
                        <p class="font-mono font-bold text-green-800 text-lg" id="m_txn"></p>
                    </div>
                </div>

                <!-- Proofs Column -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Submitted Documents</h3>
                    
                    <div>
                        <p class="text-xs font-bold text-gray-500 mb-2 uppercase">Payment Screenshot</p>
                        <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm group relative h-48 bg-gray-100">
                            <img id="img_pay" src="" class="w-full h-full object-cover">
                            <a id="link_pay" href="#" target="_blank" class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                <span class="text-white font-bold"><i class="fas fa-external-link-alt mr-2"></i> View Full</span>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-gray-500 mb-2 uppercase">Aadhar Card</p>
                            <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm group relative h-32 bg-gray-100">
                                <img id="img_aadhar" src="" class="w-full h-full object-cover">
                                <a id="link_aadhar" href="#" target="_blank" class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <i class="fas fa-expand text-white"></i>
                                </a>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 mb-2 uppercase">PAN Card</p>
                            <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm group relative h-32 bg-gray-100">
                                <img id="img_pan" src="" class="w-full h-full object-cover">
                                <a id="link_pan" href="#" target="_blank" class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <i class="fas fa-expand text-white"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 p-6 flex justify-end gap-4 border-t shrink-0">
                <button onclick="closeModal()" class="px-6 py-2.5 rounded-lg text-gray-600 font-bold hover:bg-gray-200 transition">Close</button>
                <form method="POST">
                    <input type="hidden" name="approve_user_id" id="approve_id">
                    <button type="submit" onclick="return confirm('Are you sure you want to approve this member? Commisions will be distributed immediately.')" 
                            class="px-8 py-2.5 rounded-lg bg-green-600 text-white font-bold hover:bg-green-700 shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Approve & Distribute
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        function openModal(user) {
            // Populate Data
            document.getElementById('m_name').innerText = user.name;
            document.getElementById('m_username').innerText = user.username;
            document.getElementById('m_phone').innerText = user.phone;
            document.getElementById('m_level').innerText = "Level " + user.level;
            document.getElementById('m_sponsor').innerText = "#" + user.parent_id;
            document.getElementById('m_aadhar').innerText = user.aadhar_no;
            document.getElementById('m_pan').innerText = user.pan_no;
            document.getElementById('m_txn').innerText = user.txn_id;
            document.getElementById('approve_id').value = user.id;

            // Images
            const basePath = '../uploads/';
            
            // Safe Image Loading Helper
            const loadImg = (id, linkId, file) => {
                const img = document.getElementById(id);
                const link = document.getElementById(linkId);
                if(file) {
                    img.src = basePath + file;
                    link.href = basePath + file;
                } else {
                    img.src = 'https://placehold.co/400x300?text=No+Image';
                    link.href = '#';
                }
            };

            loadImg('img_pay', 'link_pay', user.payment_screenshot);
            loadImg('img_aadhar', 'link_aadhar', user.aadhar_img);
            loadImg('img_pan', 'link_pan', user.pan_img);

            // Show Modal
            const modal = document.getElementById('detailsModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            // Small delay for animation
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('detailsModal');
            const content = document.getElementById('modalContent');
            
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>