<?php
session_start();
include '../config/db.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'master') {
    header("Location: ../index.php");
    exit;
}

// ---------------------------------------------------------
//  DATA FETCHING
// ---------------------------------------------------------

// 1. Total Users
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'master'");
$totalUsers = $stmt->fetchColumn();

// 2. Total System Liquidity (Sum of all wallets)
$stmt = $pdo->query("SELECT SUM(wallet) FROM users");
$totalLiquidity = $stmt->fetchColumn();

// 3. Maxed Out Users (Users with >= 5 directs)
// Note: This is a bit heavier query, doing a subquery check
$sqlMaxed = "SELECT COUNT(*) FROM users u WHERE (SELECT COUNT(*) FROM users c WHERE c.parent_id = u.id) >= 5";
$stmt = $pdo->query($sqlMaxed);
$maxedUsers = $stmt->fetchColumn();

// 4. Recent Transactions (Last 20)
$stmt = $pdo->query("SELECT t.*, u.username, u.name FROM transactions t JOIN users u ON t.user_id = u.id ORDER BY t.date DESC LIMIT 20");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 5. Top Earners (Top 5 by Wallet)
$stmt = $pdo->query("SELECT name, username, wallet, level FROM users WHERE role != 'master' ORDER BY wallet DESC LIMIT 5");
$topEarners = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 6. Level Distribution (For Chart)
$stmt = $pdo->query("SELECT level, COUNT(*) as count FROM users WHERE role != 'master' GROUP BY level ORDER BY level ASC");
$levelData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for JS Chart
$levels = [];
$counts = [];
foreach ($levelData as $row) {
    $levels[] = "Level " . $row['level'];
    $counts[] = $row['count'];
}
$levelsJson = json_encode($levels);
$countsJson = json_encode($counts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports | System Overview</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #ecfdf5; /* Emerald 50 */
            background-image: linear-gradient(#10b9811a 1px, transparent 1px), linear-gradient(90deg, #10b9811a 1px, transparent 1px);
            background-size: 30px 30px;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f0fdf4; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #6ee7b7; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #34d399; }
    </style>
</head>
<body class="text-gray-700 pb-20">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-emerald-100 shadow-sm">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="tree.php" class="text-gray-500 hover:text-emerald-600 transition">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                    System Reports
                </h1>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-xs font-mono text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100 hidden md:block">
                    <i class="fas fa-clock mr-1"></i> <?php echo date("F j, Y, g:i a"); ?>
                </span>
                <div class="h-10 w-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 shadow-inner">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8">
        
        <!-- 1. SUMMARY CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            
            <!-- Total Users -->
            <div class="glass-card rounded-2xl p-6 shadow-lg border-b-4 border-emerald-500 hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Members</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($totalUsers); ?></h2>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-emerald-600 font-medium">
                    <i class="fas fa-arrow-up"></i> Active in Network
                </div>
            </div>

            <!-- System Liquidity -->
            <div class="glass-card rounded-2xl p-6 shadow-lg border-b-4 border-emerald-600 hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">System Liquidity</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2">₹<?php echo number_format($totalLiquidity); ?></h2>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-emerald-600 font-medium">
                    Total User Balances
                </div>
            </div>

            <!-- Maxed Out Users -->
            <div class="glass-card rounded-2xl p-6 shadow-lg border-b-4 border-red-400 hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Maxed Out</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2"><?php echo $maxedUsers; ?></h2>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg text-red-500">
                        <i class="fas fa-ban text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-red-500 font-medium">
                    Users with 5+ Directs
                </div>
            </div>

            <!-- Total Transactions Count (From displayed data) -->
            <div class="glass-card rounded-2xl p-6 shadow-lg border-b-4 border-teal-500 hover:-translate-y-1 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Transactions</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2">
                            <?php 
                                // Quick query for total count
                                echo $pdo->query("SELECT COUNT(*) FROM transactions")->fetchColumn(); 
                            ?>
                        </h2>
                    </div>
                    <div class="p-3 bg-teal-50 rounded-lg text-teal-600">
                        <i class="fas fa-exchange-alt text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-teal-600 font-medium">
                    Lifetime Volume
                </div>
            </div>
        </div>

        <!-- 2. MAIN CONTENT GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- LEFT COLUMN: Charts & Top Users -->
            <div class="space-y-8">
                
                <!-- Chart Section -->
                <div class="glass-card rounded-2xl p-6 shadow-xl">
                    <h3 class="font-bold text-gray-800 text-lg mb-6 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-emerald-500"></i> Member Distribution
                    </h3>
                    <div class="relative h-64">
                        <canvas id="levelChart"></canvas>
                    </div>
                </div>

                <!-- Top Earners Leaderboard -->
                <div class="glass-card rounded-2xl p-6 shadow-xl">
                    <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                        <i class="fas fa-trophy text-yellow-500"></i> Top Earners
                    </h3>
                    <div class="space-y-4">
                        <?php foreach($topEarners as $index => $earner): ?>
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-emerald-50/50 transition border border-transparent hover:border-emerald-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm">
                                    <?php echo $index + 1; ?>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-gray-800"><?php echo htmlspecialchars($earner['name']); ?></p>
                                    <p class="text-xs text-gray-500">@<?php echo htmlspecialchars($earner['username']); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-mono font-bold text-emerald-600">₹<?php echo number_format($earner['wallet']); ?></p>
                                <p class="text-[10px] text-gray-400">Level <?php echo $earner['level']; ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Transaction Table (Takes up 2 cols on Large screens) -->
            <div class="lg:col-span-2">
                <div class="glass-card rounded-2xl shadow-xl overflow-hidden h-full flex flex-col">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white/50">
                        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                            <i class="fas fa-file-invoice-dollar text-emerald-500"></i> Recent Financial Audit
                        </h3>
                        <button class="text-xs bg-white border border-gray-200 px-3 py-1 rounded-lg text-gray-500 hover:text-emerald-600 transition">
                            Last 20 Entries
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto custom-scrollbar flex-grow">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-emerald-50/50 sticky top-0 backdrop-blur-sm z-10">
                                <tr>
                                    <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wide border-b border-emerald-100">TX ID</th>
                                    <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wide border-b border-emerald-100">User</th>
                                    <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wide border-b border-emerald-100">Description</th>
                                    <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wide border-b border-emerald-100">Amount</th>
                                    <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wide border-b border-emerald-100">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white/30">
                                <?php if (count($transactions) > 0): ?>
                                    <?php foreach ($transactions as $tx): ?>
                                    <tr class="hover:bg-emerald-50/30 transition duration-150">
                                        <td class="p-4 text-xs font-mono text-gray-400">#<?php echo $tx['id']; ?></td>
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-sm text-gray-700"><?php echo htmlspecialchars($tx['name']); ?></span>
                                                <span class="text-xs text-gray-500">@<?php echo htmlspecialchars($tx['username']); ?></span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-sm text-gray-600">
                                            <?php echo htmlspecialchars($tx['description']); ?>
                                        </td>
                                        <td class="p-4">
                                            <span class="font-mono font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded text-xs border border-emerald-100">
                                                +₹<?php echo number_format($tx['amount']); ?>
                                            </span>
                                        </td>
                                        <td class="p-4 text-xs text-gray-500 whitespace-nowrap">
                                            <?php echo date('M d, H:i', strtotime($tx['date'])); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-gray-500">No transactions found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-4 bg-gray-50/50 border-t border-gray-100 text-center text-xs text-gray-400">
                        Showing latest financial activity across the network.
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart Config Script -->
    <script>
        const ctx = document.getElementById('levelChart').getContext('2d');
        const levelData = <?php echo $countsJson; ?>;
        const levelLabels = <?php echo $levelsJson; ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: levelLabels,
                datasets: [{
                    label: 'Users per Level',
                    data: levelData,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.6)', // Emerald
                        'rgba(20, 184, 166, 0.6)', // Teal
                        'rgba(6, 182, 212, 0.6)',  // Cyan
                        'rgba(59, 130, 246, 0.6)', // Blue
                        'rgba(99, 102, 241, 0.6)'  // Indigo
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(20, 184, 166, 1)',
                        'rgba(6, 182, 212, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(99, 102, 241, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>2