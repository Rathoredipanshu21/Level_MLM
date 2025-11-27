<?php
session_start();
include '../config/db.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'master') {
    header("Location: ../index.php");
    exit;
}

// ---------------------------------------------------------
//  API HANDLER (Handles the AJAX request for Modal Data)
// ---------------------------------------------------------
if (isset($_GET['ajax_user_id'])) {
    $uid = $_GET['ajax_user_id'];
    
    // 1. Fetch User Details
    $stmUser = $pdo->prepare("SELECT id, name, username, password, parent_id, level, wallet, role, created_at FROM users WHERE id = ?");
    $stmUser->execute([$uid]);
    $userData = $stmUser->fetch(PDO::FETCH_ASSOC);

    // 2. Fetch Transactions
    $stmTrans = $pdo->prepare("SELECT id, user_id, amount, description, date FROM transactions WHERE user_id = ? ORDER BY date DESC");
    $stmTrans->execute([$uid]);
    $transData = $stmTrans->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON
    header('Content-Type: application/json');
    echo json_encode(['user' => $userData, 'transactions' => $transData]);
    exit;
}

// ---------------------------------------------------------
//  RECURSIVE TREE FUNCTION
// ---------------------------------------------------------
function buildOrgTree($pdo, $parentId) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE parent_id = ?");
    $stmt->execute([$parentId]);
    $children = $stmt->fetchAll();

    if (count($children) > 0) {
        echo '<ul>';
        foreach ($children as $child) {
            echo '<li>';
            
            // Default Styling
            $cardClass = "bg-white text-gray-700 border-t-4 border-blue-500 hover:shadow-lg hover:-translate-y-1";
            $iconBg = "bg-blue-100 text-blue-600";
            
            // Check Child Count for "Out of Game" Status
            $subStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE parent_id = ?");
            $subStmt->execute([$child['id']]);
            $subCount = $subStmt->fetchColumn();

            // Color Logic
            if ($child['level'] == 2) { 
                $cardClass = "bg-white text-gray-700 border-t-4 border-green-500 hover:shadow-lg hover:-translate-y-1";
                $iconBg = "bg-green-100 text-green-600";
            }
            if ($child['level'] >= 3) {
                $cardClass = "bg-white text-gray-700 border-t-4 border-purple-500 hover:shadow-lg hover:-translate-y-1";
                $iconBg = "bg-purple-100 text-purple-600";
            }
            
            // "Out of Game" Logic (5 Directs Completed)
            if ($subCount >= 5) {
                // RED STYLE for Completed/Out users
                $cardClass = "bg-red-50 text-gray-800 border-t-4 border-red-600 shadow-md ring-2 ring-red-200";
                $iconBg = "bg-red-200 text-red-700";
            }

            // Node Card
            echo '<div onclick="openModal('.$child['id'].')" class="tree-node cursor-pointer relative inline-block p-4 rounded-xl shadow-md transition-all duration-300 w-48 ' . $cardClass . '">';
                
                // Notification Badge for children count
                if($subCount > 0){
                    $badgeColor = ($subCount >= 5) ? 'bg-red-600' : 'bg-blue-500';
                    echo '<span class="absolute -top-3 -right-3 w-6 h-6 '.$badgeColor.' text-white text-xs font-bold rounded-full flex items-center justify-center border-2 border-white shadow-sm">'.$subCount.'</span>';
                }

                echo '<div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center text-lg font-bold mb-2 '.$iconBg.'">';
                echo '<i class="fas fa-user"></i>';
                echo '</div>';

                echo '<h3 class="font-bold text-sm text-gray-800 leading-tight mb-1">' . htmlspecialchars($child['name']) . ' <br><span class="text-xs text-gray-500 font-normal">(' . htmlspecialchars($child['username']) . ')</span></h3>';
                
                if ($subCount >= 5) {
                     echo '<p class="text-[10px] text-red-600 font-bold uppercase mb-1">Limit Reached</p>';
                } else {
                     echo '<p class="text-xs text-gray-500 mb-1">Level ' . $child['level'] . '</p>';
                }
                
                echo '<div class="text-xs font-mono bg-white/50 rounded px-2 py-1 border border-gray-200 mt-2">';
                echo '<span class="text-green-700 font-bold">₹' . number_format($child['wallet']) . '</span>';
                echo '</div>';

            echo '</div>';

            buildOrgTree($pdo, $child['id']);

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
    <title>Master Hierarchy | Org Chart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    
    <style>
        .tree ul { padding-top: 20px; position: relative; transition: all 0.5s; display: flex; justify-content: center; }
        .tree li { float: left; text-align: center; list-style-type: none; position: relative; padding: 20px 5px 0 5px; transition: all 0.5s; }
        .tree li::before, .tree li::after{ content: ''; position: absolute; top: 0; right: 50%; border-top: 2px solid #ccc; width: 50%; height: 20px; }
        .tree li::after{ right: auto; left: 50%; border-left: 2px solid #ccc; }
        .tree li:only-child::after, .tree li:only-child::before { display: none; }
        .tree li:only-child{ padding-top: 0;}
        .tree li:first-child::before, .tree li:last-child::after{ border: 0 none; }
        .tree li:last-child::before{ border-right: 2px solid #ccc; border-radius: 0 5px 0 0; }
        .tree li:first-child::after{ border-radius: 5px 0 0 0; }
        .tree ul ul::before{ content: ''; position: absolute; top: 0; left: 50%; border-left: 2px solid #ccc; width: 0; height: 20px; }
        .tree li a:hover+ul li::after, .tree li a:hover+ul li::before, .tree li a:hover+ul::before, .tree li a:hover+ul ul::before{ border-color:  #94a0b4; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-100 font-sans min-h-screen">

    <nav class="bg-white shadow-sm sticky top-0 z-40">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-gray-500 hover:text-gray-800 flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h1 class="text-xl font-bold text-gray-800">Network Hierarchy</h1>
        </div>
    </nav>

    <div class="min-h-screen py-10 overflow-x-auto bg-gray-50 flex justify-center pb-32">
        <div class="tree min-w-max px-4">
            <ul>
                <li>
                    <?php
                        // Fetch Root (Master) Data
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = 1"); 
                        $stmt->execute();
                        $root = $stmt->fetch();
                    ?>
                    
                    <div class="relative inline-block mx-auto cursor-pointer" onclick="openModal(<?php echo $root['id']; ?>)">
                        <div class="bg-teal-800 text-white p-6 rounded-2xl shadow-xl border-b-8 border-yellow-400 w-64 relative z-10 hover:scale-105 transition-transform">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-yellow-500 text-2xl shadow-inner border-4 border-yellow-100 mx-auto mb-3">
                                <i class="fas fa-crown"></i>
                            </div>
                            <h2 class="text-xl font-bold uppercase tracking-wider"><?php echo htmlspecialchars($root['name']); ?></h2>
                            <p class="text-teal-200 text-sm">(<?php echo htmlspecialchars($root['username']); ?>)</p>
                            <div class="mt-3 bg-teal-900/50 rounded-lg py-1 px-3 inline-block">
                                <span class="text-yellow-400 font-bold">₹<?php echo number_format($root['wallet'] ?? 0); ?></span>
                            </div>
                        </div>
                    </div>

                    <?php buildOrgTree($pdo, 1); ?>
                </li>
            </ul>
        </div>
    </div>

    <div id="infoModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl max-h-[90vh] flex flex-col">
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">User Details</h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="px-4 py-5 sm:p-6 overflow-y-auto custom-scrollbar" id="modalContent">
                    </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t">
                    <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(userId) {
            const modal = document.getElementById('infoModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');

            content.innerHTML = `
                <div class="text-center py-10">
                    <i class="fas fa-circle-notch fa-spin text-4xl text-blue-500"></i>
                    <p class="mt-2 text-gray-500">Fetching Data...</p>
                </div>
            `;

            fetch(`?ajax_user_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.user) {
                        content.innerHTML = '<p class="text-red-500 text-center">User not found.</p>';
                        return;
                    }

                    const u = data.user;
                    const t = data.transactions;

                    let html = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <h4 class="font-bold text-blue-800 mb-2 border-b border-blue-200 pb-1">Profile Info</h4>
                                <p><span class="font-semibold text-gray-600">ID:</span> ${u.id}</p>
                                <p><span class="font-semibold text-gray-600">Name:</span> ${u.name} <span class="text-gray-500 text-sm">(${u.username})</span></p>
                                <p><span class="font-semibold text-gray-600">Role:</span> ${u.role}</p>
                                <p class="truncate"><span class="font-semibold text-gray-600">Password:</span> <span class="text-xs bg-gray-200 px-1 rounded">${u.password}</span></p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                <h4 class="font-bold text-green-800 mb-2 border-b border-green-200 pb-1">Account Info</h4>
                                <p><span class="font-semibold text-gray-600">Wallet:</span> ₹${new Intl.NumberFormat().format(u.wallet)}</p>
                                <p><span class="font-semibold text-gray-600">Level:</span> ${u.level}</p>
                                <p><span class="font-semibold text-gray-600">Parent ID:</span> ${u.parent_id}</p>
                                <p><span class="font-semibold text-gray-600">Joined:</span> ${u.created_at}</p>
                            </div>
                        </div>

                        <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-exchange-alt"></i> Transactions
                        </h4>
                    `;

                    if (t.length > 0) {
                        html += `
                            <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-gray-900">ID</th>
                                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-gray-900">Amount</th>
                                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-gray-900">Desc</th>
                                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-gray-900">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                        `;
                        t.forEach(row => {
                            html += `
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-xs text-gray-500">#${row.id}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-xs font-bold text-gray-900">₹${row.amount}</td>
                                    <td class="px-3 py-4 text-xs text-gray-500">${row.description}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-xs text-gray-500">${row.date}</td>
                                </tr>
                            `;
                        });
                        html += `</tbody></table></div>`;
                    } else {
                        html += `<div class="p-4 bg-gray-50 text-center text-gray-500 rounded border border-dashed border-gray-300">No transactions found.</div>`;
                    }

                    content.innerHTML = html;
                })
                .catch(err => {
                    console.error(err);
                    content.innerHTML = '<p class="text-red-500 text-center">Error fetching data.</p>';
                });
        }

        function closeModal() {
            document.getElementById('infoModal').classList.add('hidden');
        }
    </script>
</body>
</html>