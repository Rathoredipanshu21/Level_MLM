<?php
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

$address = $settings['address'] ?? '123 Wellness Blvd, Eco City, Earth';
$phone = $settings['phone'] ?? '+1 800 123 4567';
$email = $settings['email'] ?? 'support@ebiotheraphy.com';
?>

<footer class="bg-gray-900 text-white pt-16 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            <div data-aos="fade-up">
                <div class="flex items-center gap-2 mb-6">
                    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white w-8 h-8 rounded-md flex items-center justify-center text-sm shadow-lg">
                        <i class="fas fa-dna"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight">e<span class="text-teal-400">Biotheraphy</span></span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Dedicated to providing the highest quality natural supplements to help you live a balanced and vibrant life.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-teal-500 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-teal-500 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-teal-500 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-lg font-bold mb-6 text-white">Quick Links</h3>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li><a href="index.php" class="hover:text-teal-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Home</a></li>
                    <li><a href="about.php" class="hover:text-teal-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Our Story</a></li>
                    <li><a href="products.php" class="hover:text-teal-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Shop</a></li>
                    <li><a href="index2.php" class="hover:text-teal-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Member Login</a></li>
                </ul>
            </div>

            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-lg font-bold mb-6 text-white">Customer Care</h3>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-teal-400 transition">Shipping Policy</a></li>
                    <li><a href="#" class="hover:text-teal-400 transition">Returns & Refunds</a></li>
                    <li><a href="#" class="hover:text-teal-400 transition">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-teal-400 transition">Terms & Conditions</a></li>
                </ul>
            </div>

            <div data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-lg font-bold mb-6 text-white">Get in Touch</h3>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-teal-500 mt-1"></i>
                        <span><?php echo htmlspecialchars($address); ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone-alt text-teal-500"></i>
                        <a href="tel:<?php echo htmlspecialchars($phone); ?>" class="hover:text-teal-400 transition"><?php echo htmlspecialchars($phone); ?></a>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-teal-500"></i>
                        <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="hover:text-teal-400 transition"><?php echo htmlspecialchars($email); ?></a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="border-t border-gray-800 py-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
            <p>&copy; <?php echo date("Y"); ?> eBiotheraphy. All Rights Reserved.</p>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                <span>Secure Payments</span>
            </div>
        </div>
    </div>
</footer>
</body>
</html>