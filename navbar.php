<?php
// Get current page
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Define navigation items (Product/Customer focused)
$nav_items = [
    'HOME' => 'index.php',
    'OUR PRODUCTS' => 'products.php', // You can create this later
    'WELLNESS SCIENCE' => 'about.php',
    'TESTIMONIALS' => 'reviews.php',
    'CONTACT' => 'contact.php'
];

// Database Connection for dynamic settings
// Adjust path if necessary: include 'config/db.php';
// For this example, assuming strict error handling is off for the view
include_once 'config/db.php'; 

$settings = [];
if(isset($conn)) {
    $sql = "SELECT setting_key, setting_value FROM site_settings";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
}

$phone = $settings['phone'] ?? '+1 800 123 4567';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .nav-link { position: relative; }
        .nav-link::after { 
            content: ''; position: absolute; bottom: -4px; left: 0; 
            width: 0%; height: 2px; background: linear-gradient(to right, #16a34a, #0d9488); 
            transition: width 0.3s ease; 
        }
        .nav-link:hover::after, .active-link::after { width: 100%; }
        .active-link { color: #0d9488; font-weight: 600; }
    </style>
</head>
<body>

    <div class="bg-gradient-to-r from-green-50 to-blue-50 text-gray-600 text-xs py-2 border-b border-gray-100 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <p><i class="fas fa-leaf text-green-600 mr-2"></i>Premium Bio-Organic Supplements & Wellness Solutions</p>
            <div class="flex items-center space-x-4">
                <a href="tel:<?php echo htmlspecialchars($phone); ?>" class="hover:text-green-600 transition"><i class="fas fa-phone-alt mr-1"></i> Support</a>
                <span>|</span>
                <div class="flex space-x-3">
                    <a href="#" class="hover:text-blue-600"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-pink-600"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <div class="flex-shrink-0 cursor-pointer" onclick="window.location.href='index.php'" data-aos="fade-right">
                    <div class="flex items-center gap-2">
                        <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white w-10 h-10 rounded-lg flex items-center justify-center text-xl shadow-lg">
                            <i class="fas fa-dna"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-800 tracking-tight">e<span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-teal-600">Biotheraphy</span></span>
                    </div>
                </div>

                <div class="hidden lg:flex items-center space-x-8">
                    <?php foreach ($nav_items as $title => $url): 
                        $isActive = ($current_page == basename($url, '.php') || ($current_page == 'index' && $title == 'HOME'));
                        $colorClass = $isActive ? "text-teal-700 active-link" : "text-gray-600 hover:text-teal-600";
                    ?>
                        <a href="<?php echo $url; ?>" class="<?php echo $colorClass; ?> text-sm font-medium transition-colors nav-link">
                            <?php echo $title; ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="hidden lg:flex items-center space-x-4" data-aos="fade-left">
                    <a href="#" class="text-gray-500 hover:text-green-600 transition relative">
                        <i class="fas fa-shopping-bag text-xl"></i>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">0</span>
                    </a>
                    <a href="index2.php" class="bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white px-6 py-2.5 rounded-full text-sm font-medium shadow-lg shadow-green-200 transition transform hover:-translate-y-0.5">
                        <i class="fas fa-user-circle mr-2"></i> Member Zone
                    </a>
                </div>

                <div class="lg:hidden flex items-center">
                    <button id="menu-toggle" class="text-gray-600 hover:text-teal-600 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-100 absolute w-full left-0 shadow-xl">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <?php foreach ($nav_items as $title => $url): ?>
                    <a href="<?php echo $url; ?>" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-green-50 hover:text-green-700">
                        <?php echo $title; ?>
                    </a>
                <?php endforeach; ?>
                <div class="border-t border-gray-100 mt-2 pt-2">
                    <a href="index2.php" class="block w-full text-center px-4 py-3 mt-2 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg font-bold">
                        Member Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
        const btn = document.getElementById('menu-toggle');
        const menu = document.getElementById('mobile-menu');
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>