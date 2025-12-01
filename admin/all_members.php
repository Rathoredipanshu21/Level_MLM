<?php
session_start();
include '../config/db.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'master') {
    header("Location: ../index.php");
    exit;
}

// Fetch ALL Users
$stmt = $pdo->query("SELECT id, name, username, referral_id, password, parent_id, level, wallet, role, created_at, phone, aadhar_no, pan_no, aadhar_img, pan_img, payment_screenshot, txn_id, payment_status, account_status, pin FROM users WHERE 1 ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create a lookup array to find Sponsor Names easily
$userMap = [];
foreach ($users as $u) {
    $userMap[$u['id']] = $u;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Members Registry | eBiotheraphy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Glass Modal Styles */
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #0d9488; border-radius: 3px; }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen text-gray-800">

    <!-- Header / Nav -->
    <div class="bg-white sticky top-0 z-30 shadow-sm border-b border-gray-100 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600 shadow-sm">
                    <i class="fas fa-users text-lg"></i>
                </div>
                <div>
                    <h1 class="font-serif font-bold text-xl text-gray-900">Member Directory</h1>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Users: <?php echo count($users); ?></p>
                </div>
            </div>
            
            <div class="flex gap-3">
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search members..." class="hidden md:block px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-green-500 transition w-64">
                <a href="index.php" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-bold transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto p-6">
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="userTable">
                    <thead>
                        <tr class="bg-gradient-to-r from-teal-700 to-green-700 text-white text-sm uppercase tracking-wider">
                            <th class="p-5 font-bold border-b border-teal-800">ID / Level</th>
                            <th class="p-5 font-bold border-b border-teal-800">Member Info</th>
                            <th class="p-5 font-bold border-b border-teal-800">Credentials</th>
                            <th class="p-5 font-bold border-b border-teal-800">Referred By</th>
                            <th class="p-5 font-bold border-b border-teal-800">Wallet</th>
                            <th class="p-5 font-bold border-b border-teal-800">Status</th>
                            <th class="p-5 font-bold border-b border-teal-800 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        <?php foreach ($users as $u): 
                            // Determine Parent Info
                            $sponsorName = "ROOT (Admin)";
                            if ($u['parent_id'] && isset($userMap[$u['parent_id']])) {
                                $parent = $userMap[$u['parent_id']];
                                $sponsorName = $parent['name'] . " <br><span class='text-[10px] text-gray-400'>(" . ($parent['referral_id'] ?? 'No Code') . ")</span>";
                            }
                        ?>
                        <tr class="hover:bg-green-50/50 transition duration-150 group">
                            
                            <!-- ID & Level -->
                            <td class="p-5">
                                <div class="font-bold text-gray-700">#<?php echo $u['id']; ?></div>
                                <div class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                    Lvl <?php echo $u['level']; ?>
                                </div>
                            </td>

                            <!-- Member Info -->
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500 font-bold border-2 border-white shadow-sm">
                                        <?php echo strtoupper(substr($u['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900"><?php echo htmlspecialchars($u['name']); ?></p>
                                        <p class="text-xs text-gray-500"><i class="fas fa-user-circle mr-1"></i> <?php echo htmlspecialchars($u['username']); ?></p>
                                    </div>
                                </div>
                            </td>

                            <!-- Credentials (Referral ID / PIN) -->
                            <td class="p-5">
                                <?php if($u['referral_id']): ?>
                                    <div class="text-xs">
                                        <p class="text-gray-500 mb-1">Code: <span class="font-mono font-bold text-teal-700 bg-teal-50 px-1 rounded"><?php echo $u['referral_id']; ?></span></p>
                                        <p class="text-gray-500">PIN: <span class="font-mono font-bold text-purple-700 bg-purple-50 px-1 rounded tracking-wider"><?php echo $u['pin']; ?></span></p>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-yellow-600 bg-yellow-50 px-2 py-1 rounded font-bold">Pending</span>
                                <?php endif; ?>
                            </td>

                            <!-- Referred By -->
                            <td class="p-5">
                                <div class="text-sm text-gray-600 leading-tight">
                                    <?php echo $sponsorName; ?>
                                </div>
                            </td>

                            <!-- Wallet -->
                            <td class="p-5">
                                <span class="font-bold text-gray-800">₹ <?php echo number_format($u['wallet'], 2); ?></span>
                            </td>

                            <!-- Status -->
                            <td class="p-5">
                                <?php if($u['account_status'] == 'active'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Active
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Action -->
                            <td class="p-5 text-right">
                                <button onclick='openDetails(<?php echo json_encode($u); ?>)' class="text-gray-400 hover:text-teal-600 transition p-2 rounded-lg hover:bg-teal-50">
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- FULL DETAILS MODAL -->
    <div id="userModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop" onclick="closeModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white w-full max-w-5xl rounded-3xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]" id="modalContent">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 p-6 flex justify-between items-center text-white shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-2xl font-bold border border-white/20">
                        <span id="m_initials">U</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-serif font-bold" id="m_fullname">User Name</h2>
                        <p class="text-sm text-gray-400 flex items-center gap-2">
                            <span class="bg-green-500/20 text-green-300 px-2 py-0.5 rounded text-xs border border-green-500/30" id="m_status">Active</span>
                            <span id="m_joined">Joined: 2023-01-01</span>
                        </p>
                    </div>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/20 flex items-center justify-center transition text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Body -->
            <div class="flex-1 overflow-y-auto p-8 bg-gray-50 custom-scrollbar">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- COLUMN 1: Account Secrets -->
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Login Credentials</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Username</p>
                                    <div class="flex justify-between items-center bg-gray-50 p-2 rounded border border-gray-200">
                                        <span class="font-bold text-gray-800" id="m_username">user123</span>
                                        <i class="fas fa-user text-gray-300"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Password (Raw)</p>
                                    <div class="flex justify-between items-center bg-red-50 p-2 rounded border border-red-100">
                                        <span class="font-mono font-bold text-red-600" id="m_password">pass123</span>
                                        <i class="fas fa-key text-red-300"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Secret PIN</p>
                                    <div class="flex justify-between items-center bg-purple-50 p-2 rounded border border-purple-100">
                                        <span class="font-mono font-bold text-purple-700 tracking-widest text-lg" id="m_pin">123456</span>
                                        <i class="fas fa-lock text-purple-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-teal-500 to-green-600 p-6 rounded-2xl shadow-lg text-white relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/20 rounded-full blur-xl"></div>
                            <h3 class="font-bold text-lg mb-1">Current Wallet</h3>
                            <p class="text-3xl font-extrabold" id="m_wallet">₹ 0.00</p>
                            <div class="mt-4 pt-4 border-t border-white/20 flex justify-between text-sm opacity-90">
                                <span>Referral ID:</span>
                                <span class="font-mono font-bold" id="m_refcode">EBIO-xxxx</span>
                            </div>
                        </div>
                    </div>

                    <!-- COLUMN 2: Personal & KYC -->
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-full">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Personal Information</h3>
                            
                            <ul class="space-y-4 text-sm">
                                <li class="flex justify-between">
                                    <span class="text-gray-500">Phone Number</span>
                                    <span class="font-bold text-gray-800" id="m_phone">+91 9876543210</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-500">Aadhar Number</span>
                                    <span class="font-bold text-gray-800" id="m_aadhar">1234-5678-9012</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-500">PAN Number</span>
                                    <span class="font-bold text-gray-800" id="m_pan">ABCDE1234F</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-500">Parent ID (System)</span>
                                    <span class="font-bold text-blue-600" id="m_parent">#5</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-500">Registration Txn</span>
                                    <span class="font-bold text-gray-800 font-mono" id="m_txn">UPI123456</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- COLUMN 3: Documents -->
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">KYC Documents</h3>
                            
                            <div class="grid gap-4">
                                <div class="group relative overflow-hidden rounded-xl border border-gray-200 aspect-video bg-gray-100">
                                    <p class="absolute top-2 left-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded backdrop-blur-sm z-10">Payment Proof</p>
                                    <img src="" id="img_pay" class="w-full h-full object-cover transition transform group-hover:scale-110">
                                    <a href="" id="link_pay" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition">
                                        <i class="fas fa-external-link-alt text-white text-2xl"></i>
                                    </a>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="group relative overflow-hidden rounded-xl border border-gray-200 aspect-square bg-gray-100">
                                        <p class="absolute top-2 left-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded backdrop-blur-sm z-10">Aadhar</p>
                                        <img src="" id="img_aadhar" class="w-full h-full object-cover transition transform group-hover:scale-110">
                                        <a href="" id="link_aadhar" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition">
                                            <i class="fas fa-expand text-white"></i>
                                        </a>
                                    </div>
                                    <div class="group relative overflow-hidden rounded-xl border border-gray-200 aspect-square bg-gray-100">
                                        <p class="absolute top-2 left-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded backdrop-blur-sm z-10">PAN</p>
                                        <img src="" id="img_pan" class="w-full h-full object-cover transition transform group-hover:scale-110">
                                        <a href="" id="link_pan" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition">
                                            <i class="fas fa-expand text-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 p-4 border-t flex justify-end shrink-0">
                <button onclick="closeModal()" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition">Close Details</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        function filterTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toUpperCase();
            let table = document.getElementById("userTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let tdName = tr[i].getElementsByTagName("td")[1];
                let tdID = tr[i].getElementsByTagName("td")[0];
                if (tdName || tdID) {
                    let txtValueName = tdName.textContent || tdName.innerText;
                    let txtValueID = tdID.textContent || tdID.innerText;
                    if (txtValueName.toUpperCase().indexOf(filter) > -1 || txtValueID.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function openDetails(user) {
            // Text Fields
            document.getElementById('m_fullname').innerText = user.name;
            document.getElementById('m_initials').innerText = user.name.charAt(0).toUpperCase();
            document.getElementById('m_status').innerText = user.account_status.toUpperCase();
            document.getElementById('m_status').className = user.account_status === 'active' 
                ? "bg-green-500/20 text-green-600 px-2 py-0.5 rounded text-xs border border-green-500/30" 
                : "bg-red-500/20 text-red-600 px-2 py-0.5 rounded text-xs border border-red-500/30";
            
            document.getElementById('m_joined').innerText = "Joined: " + user.created_at;
            document.getElementById('m_username').innerText = user.username;
            document.getElementById('m_password').innerText = user.password; // Note: In production, passwords should be hashed!
            document.getElementById('m_pin').innerText = user.pin ? user.pin : 'Not Generated';
            
            document.getElementById('m_wallet').innerText = "₹ " + parseFloat(user.wallet).toFixed(2);
            document.getElementById('m_refcode').innerText = user.referral_id ? user.referral_id : 'Pending';
            
            document.getElementById('m_phone').innerText = user.phone;
            document.getElementById('m_aadhar').innerText = user.aadhar_no;
            document.getElementById('m_pan').innerText = user.pan_no;
            document.getElementById('m_parent').innerText = "#" + user.parent_id;
            document.getElementById('m_txn').innerText = user.txn_id;

            // Images
            const basePath = '../uploads/';
            
            // Helper to handle missing images
            const setImage = (id, linkId, file) => {
                const img = document.getElementById(id);
                const link = document.getElementById(linkId);
                if(file) {
                    img.src = basePath + file;
                    link.href = basePath + file;
                    link.style.display = 'flex';
                } else {
                    img.src = 'https://placehold.co/400x300?text=No+Image';
                    link.style.display = 'none';
                }
            };

            setImage('img_pay', 'link_pay', user.payment_screenshot);
            setImage('img_aadhar', 'link_aadhar', user.aadhar_img);
            setImage('img_pan', 'link_pan', user.pan_img);

            // Animations
            const modal = document.getElementById('userModal');
            const backdrop = document.getElementById('modalBackdrop');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            // Slight delay to allow display:block to apply before opacity transition
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                content.classList.remove('opacity-0', 'scale-95');
                content.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('userModal');
            const backdrop = document.getElementById('modalBackdrop');
            const content = document.getElementById('modalContent');

            backdrop.classList.add('opacity-0');
            content.classList.remove('opacity-100', 'scale-100');
            content.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>