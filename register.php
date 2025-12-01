<?php
session_start();
include 'config/db.php';

// Fetch Registration Fee
$reg_fee = getSetting($pdo, 'registration_fee');

// Fetch Admin Bank Details
$bank = $pdo->query("SELECT * FROM bank_details LIMIT 1")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Network | Ebiotheraphy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        .step-active { @apply border-green-600 text-green-600; }
        .step-inactive { @apply border-gray-300 text-gray-400; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-green-600 text-white w-8 h-8 rounded flex items-center justify-center text-lg">
                    <i class="fas fa-leaf"></i>
                </div>
                <span class="text-xl font-bold text-gray-800">ebio<span class="text-green-600">theraphy</span></span>
            </div>
            <a href="index.php" class="text-green-600 font-semibold hover:underline">Back to Login</a>
        </div>
    </nav>

    <div class="container mx-auto py-10 px-4 max-w-4xl">
        
        <div class="text-center mb-10" data-aos="fade-down">
            <h1 class="text-3xl font-bold text-gray-800">Join Our Network</h1>
            <p class="text-gray-500 mt-2">Enter your Sponsor's Referral Code to begin.</p>
        </div>

        <div class="flex justify-center mb-10" data-aos="fade-up">
            <div class="flex items-center w-full max-w-lg">
                <div id="step1-indicator" class="flex-1 border-b-4 step-active pb-2 text-center font-bold text-sm">1. Personal Info</div>
                <div id="step2-indicator" class="flex-1 border-b-4 step-inactive pb-2 text-center font-bold text-sm">2. Payment</div>
            </div>
        </div>

        <form action="register_logic.php" method="POST" enctype="multipart/form-data" id="regForm" class="bg-white rounded-2xl shadow-xl overflow-hidden relative">
            
            <div id="step1" class="p-8 space-y-6" data-aos="fade-right">
                
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <label class="block text-sm font-bold text-blue-800 mb-1">Sponsor Referral ID <span class="text-red-500">*</span></label>
                    <input type="text" name="sponsor_code" class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:outline-none focus:border-blue-500 transition font-mono tracking-wider" placeholder="e.g. EBIO-MSTR-01" required>
                    <p class="text-xs text-blue-600 mt-1">You must provide a valid Referral Code from an existing member.</p>
                </div>

                <h3 class="text-xl font-bold text-gray-700 border-b pb-2 mb-4"><i class="fas fa-user-edit mr-2 text-green-500"></i> Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1">Full Name</label>
                        <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1">Desired Username</label>
                        <input type="text" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1">Phone Number</label>
                        <input type="text" name="phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500 transition" required>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-700 border-b pb-2 mb-4 mt-8"><i class="fas fa-id-card mr-2 text-green-500"></i> KYC Documents</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1">Aadhar Number</label>
                        <input type="text" name="aadhar_no" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1">PAN Number</label>
                        <input type="text" name="pan_no" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1">Upload Aadhar (Image)</label>
                        <input type="file" name="aadhar_img" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1">Upload PAN (Image)</label>
                        <input type="file" name="pan_img" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition" required>
                    </div>
                </div>

                <div class="text-right mt-6">
                    <button type="button" onclick="nextStep()" class="bg-green-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg hover:bg-green-700 transition transform hover:translate-x-1">Next Step <i class="fas fa-arrow-right ml-2"></i></button>
                </div>
            </div>

            <div id="step2" class="hidden p-8 space-y-6">
                
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-yellow-800 font-bold">Registration Fee</p>
                            <h2 class="text-3xl font-bold text-gray-800">₹ <?php echo number_format($reg_fee); ?></h2>
                        </div>
                        <button type="button" onclick="openPaymentModal()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                            <i class="fas fa-qrcode mr-2"></i> Show Payment Details
                        </button>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-700 border-b pb-2 mb-4"><i class="fas fa-receipt mr-2 text-green-500"></i> Payment Confirmation</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1">Transaction ID (Required)</label>
                        <input type="text" name="txn_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500 transition" placeholder="e.g. UPI12345678" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-1">Payment Screenshot (Required)</label>
                        <input type="file" name="payment_screenshot" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition" required>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" onclick="prevStep()" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-bold hover:bg-gray-300 transition">Back</button>
                    <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg hover:bg-green-700 transition transform hover:-translate-y-1">Submit Application <i class="fas fa-check-circle ml-2"></i></button>
                </div>
            </div>

        </form>
    </div>

    <div id="payModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-2xl p-6 relative m-4" data-aos="zoom-in">
            <button onclick="closePaymentModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500"><i class="fas fa-times text-xl"></i></button>
            <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Scan to Pay</h3>
            <div class="bg-gray-100 p-4 rounded-xl flex justify-center mb-4">
                <img src="<?php echo 'admin/uploads/' . htmlspecialchars($bank['qr_code']); ?>" alt="QR Code" class="h-48 w-48 object-cover rounded-lg shadow-md border-2 border-white">
            </div>
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-sm text-blue-800 whitespace-pre-line mb-4 text-center">
                <?php echo htmlspecialchars($bank['bank_info']); ?>
            </div>
            <div class="text-center">
                <button onclick="closePaymentModal()" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-black transition">I Have Made Payment</button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        function nextStep() {
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
            document.getElementById('step1-indicator').classList.remove('step-active');
            document.getElementById('step1-indicator').classList.add('step-inactive');
            document.getElementById('step2-indicator').classList.remove('step-inactive');
            document.getElementById('step2-indicator').classList.add('step-active');
        }
        function prevStep() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
            document.getElementById('step2-indicator').classList.remove('step-active');
            document.getElementById('step2-indicator').classList.add('step-inactive');
            document.getElementById('step1-indicator').classList.remove('step-inactive');
            document.getElementById('step1-indicator').classList.add('step-active');
        }
        function openPaymentModal() { document.getElementById('payModal').classList.remove('hidden'); }
        function closePaymentModal() { document.getElementById('payModal').classList.add('hidden'); }
    </script>
</body>
</html>