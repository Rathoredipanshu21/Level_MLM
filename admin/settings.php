<?php
session_start();
include '../config/db.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'master') {
    header("Location: ../index.php");
    exit;
}

// Handle Form Submission
$message = "";
$msg_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();
        
        // distinct fields we expect
        $allowed_keys = ['registration_fee', 'master_cut', 'level_commission_percent'];
        
        foreach ($allowed_keys as $key) {
            if (isset($_POST[$key])) {
                $val = $_POST[$key];
                $stmt = $pdo->prepare("UPDATE settings SET meta_value = ? WHERE meta_key = ?");
                $stmt->execute([$val, $key]);
            }
        }
        
        $pdo->commit();
        $message = "System settings updated successfully!";
        $msg_type = "success";
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error updating settings: " . $e->getMessage();
        $msg_type = "error";
    }
}

// Fetch Current Settings
$stmt = $pdo->query("SELECT * FROM settings");
$settings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings[$row['meta_key']] = $row['meta_value'];
}

// Defaults if missing
$reg_fee = $settings['registration_fee'] ?? 0;
$master_cut = $settings['master_cut'] ?? 0;
$level_percent = $settings['level_commission_percent'] ?? 0;

// Calculate Preview (Just for display)
$distributable = $reg_fee - $master_cut;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Settings | eBiotheraphy Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Floating Labels */
        .floating-input:focus ~ label,
        .floating-input:not(:placeholder-shown) ~ label {
            transform: translateY(-1.5rem) scale(0.85);
            color: #0d9488;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen text-gray-800">

    <div class="bg-white sticky top-0 z-30 shadow-sm border-b border-gray-100 px-6 py-4">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 shadow-sm">
                    <i class="fas fa-cogs text-lg"></i>
                </div>
                <div>
                    <h1 class="font-serif font-bold text-xl text-gray-900">System Configuration</h1>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Financial Logic Control</p>
                </div>
            </div>
            <a href="index.php" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-bold transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto p-6">
        
        <?php if($message): ?>
            <div class="mb-8 p-4 rounded-xl <?php echo $msg_type == 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'; ?> flex items-center gap-3 shadow-sm" data-aos="fade-down">
                <i class="fas <?php echo $msg_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> text-xl"></i>
                <span class="font-medium"><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 relative overflow-hidden group" data-aos="fade-up">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-green-100"></div>
                    
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-gray-800 mb-1 flex items-center gap-2">
                            <i class="fas fa-wallet text-green-600"></i> Joining Fee
                        </h3>
                        <p class="text-sm text-gray-500 mb-6">Total amount paid by a new member to register.</p>
                        
                        <div class="relative mt-2">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">₹</span>
                            </div>
                            <input type="number" name="registration_fee" id="reg_fee" value="<?php echo $reg_fee; ?>" 
                                class="pl-8 pr-4 py-3 w-full border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition font-bold text-lg text-gray-800" placeholder=" " required>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 relative overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-blue-100"></div>
                    
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-gray-800 mb-1 flex items-center gap-2">
                            <i class="fas fa-user-shield text-blue-600"></i> Master Admin Cut
                        </h3>
                        <p class="text-sm text-gray-500 mb-6">Fixed amount sent directly to Admin wallet per joining.</p>
                        
                        <div class="relative mt-2">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">₹</span>
                            </div>
                            <input type="number" name="master_cut" id="master_cut" value="<?php echo $master_cut; ?>" 
                                class="pl-8 pr-4 py-3 w-full border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition font-bold text-lg text-gray-800" placeholder=" " required>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 relative overflow-hidden group" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-purple-100"></div>
                    
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-gray-800 mb-1 flex items-center gap-2">
                            <i class="fas fa-network-wired text-purple-600"></i> Level Commission
                        </h3>
                        <p class="text-sm text-gray-500 mb-6">Percentage of the <b>remaining amount</b> (Fee - Cut) given to 5 uplines.</p>
                        
                        <div class="relative mt-2">
                            <input type="number" name="level_commission_percent" id="comm_percent" value="<?php echo $level_percent; ?>" 
                                class="pl-4 pr-10 py-3 w-full border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition font-bold text-lg text-gray-800" placeholder=" " required>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-gray-800 to-gray-900 text-white font-bold py-4 rounded-xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition transform flex items-center justify-center gap-3">
                    <i class="fas fa-save"></i> Save Changes
                </button>

            </div>

            <div class="lg:col-span-1" data-aos="fade-left">
                <div class="bg-gradient-to-b from-teal-600 to-teal-800 rounded-3xl p-6 text-white shadow-2xl sticky top-24">
                    <h3 class="text-xl font-serif font-bold mb-6 border-b border-white/20 pb-4">Logic Breakdown</h3>
                    
                    <div class="space-y-6">
                        <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                            <p class="text-xs uppercase tracking-widest text-teal-200 mb-1">Total Pot</p>
                            <div class="flex justify-between items-end">
                                <span class="text-sm opacity-80">Joining Fee</span>
                                <span class="text-xl font-bold">₹ <?php echo $reg_fee; ?></span>
                            </div>
                        </div>

                        <div class="flex justify-center text-white/50"><i class="fas fa-minus"></i></div>

                        <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                            <p class="text-xs uppercase tracking-widest text-teal-200 mb-1">Admin Takes</p>
                            <div class="flex justify-between items-end">
                                <span class="text-sm opacity-80">Master Cut</span>
                                <span class="text-xl font-bold">₹ <?php echo $master_cut; ?></span>
                            </div>
                        </div>

                        <div class="flex justify-center text-white/50"><i class="fas fa-equals"></i></div>

                        <div class="bg-white/20 rounded-xl p-4 backdrop-blur-md border border-white/30">
                            <p class="text-xs uppercase tracking-widest text-yellow-300 mb-1">Distributable</p>
                            <div class="flex justify-between items-end">
                                <span class="text-sm opacity-80">For Network</span>
                                <span class="text-2xl font-bold">₹ <?php echo $distributable; ?></span>
                            </div>
                        </div>
                        
                        <div class="text-xs text-center text-teal-100 px-2 leading-relaxed">
                            <i class="fas fa-info-circle mr-1"></i>
                            Each of 5 ancestors gets <b><?php echo $level_percent; ?>%</b> of ₹<?php echo $distributable; ?> 
                            (approx <b>₹<?php echo ($distributable * $level_percent / 100); ?></b>).
                            <br>Remainder goes to Level 1.
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        
        // Simple client-side math update (Optional visual enhancement)
        const regInput = document.getElementById('reg_fee');
        const cutInput = document.getElementById('master_cut');
        // You could add event listeners here to update the text in the Right Column live
    </script>
</body>
</html>