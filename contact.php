<?php
// 1. Include Database Config
include_once 'config/db.php';

// 2. Initialize Variables
$msg = '';
$msgClass = '';

// 3. Form Handling Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Check connection
            if (isset($conn)) {
                $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $subject, $message);

                if ($stmt->execute()) {
                    $msg = "<i class='fas fa-check-circle mr-2'></i> Message sent successfully!";
                    $msgClass = "bg-green-100 border-green-500 text-green-700";
                } else {
                    $msg = "<i class='fas fa-exclamation-circle mr-2'></i> Failed to send. Try again.";
                    $msgClass = "bg-red-100 border-red-500 text-red-700";
                }
                $stmt->close();
            } else {
                $msg = "Database connection error.";
                $msgClass = "bg-red-100 border-red-500 text-red-700";
            }
        } else {
            $msg = "Invalid email format.";
            $msgClass = "bg-yellow-100 border-yellow-500 text-yellow-700";
        }
    } else {
        $msg = "All fields are required.";
        $msgClass = "bg-yellow-100 border-yellow-500 text-yellow-700";
    }
}
?>

<!-- Include Navbar -->
<?php include 'navbar.php'; ?>

<!-- PAGE CONTENT START -->
<style>
    /* Page-Specific Styles */
    .font-serif { font-family: 'Playfair Display', serif; }
    
    /* Falling Leaves Animation Layer */
    .leaf-container {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        pointer-events: none; z-index: 1; overflow: hidden;
    }
    .leaf {
        position: absolute; top: -10%; opacity: 0.6;
        animation: falling linear infinite;
    }
    @keyframes falling {
        0% { transform: translate(0, 0) rotate(0deg); opacity: 1; }
        100% { transform: translate(100px, 100vh) rotate(360deg); opacity: 0; }
    }

    /* Glass Effect for Form */
    .glass-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    /* Input Animations */
    .input-field {
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    .input-field:focus {
        transform: translateY(-2px);
        border-color: #0d9488; /* Teal-600 */
        box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.1);
    }
</style>

<!-- Falling Leaves Background -->
<div id="leaves" class="leaf-container"></div>

<!-- HERO SECTION -->
<div class="relative bg-teal-900 h-[60vh] min-h-[500px] flex items-center justify-center overflow-hidden">
    <!-- Parallax Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
             class="w-full h-full object-cover opacity-40 transform scale-105" 
             alt="Nature Background">
        <div class="absolute inset-0 bg-gradient-to-b from-teal-900/50 to-teal-900/90"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto" data-aos="fade-up">
        <span class="inline-block py-1 px-4 rounded-full bg-teal-800/50 border border-teal-500/30 text-teal-300 text-xs font-bold tracking-[0.2em] mb-6 backdrop-blur-sm">
            24/7 SUPPORT
        </span>
        <h1 class="font-serif text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
            Let's Start a <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-200 to-green-400">Wellness Conversation</span>
        </h1>
        <p class="text-lg text-teal-100 max-w-2xl mx-auto font-light leading-relaxed">
            Have questions about our herbal formulations or need guidance on your journey? Our team of botanists and wellness experts is here to help.
        </p>
    </div>
</div>

<!-- MAIN CONTENT SECTION (Overlapping Hero) -->
<section class="relative z-20 -mt-24 px-4 sm:px-6 lg:px-8 pb-24">
    <div class="max-w-7xl mx-auto">
        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- LEFT: Contact Info Cards -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:-translate-y-1 transition duration-300 border-l-4 border-teal-500" data-aos="fade-right" data-aos-delay="100">
                    <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center text-teal-600 text-xl mb-4">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-800 mb-2">Visit Us</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        123 Wellness Blvd, Eco District<br>
                        Green Valley, CA 90210
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:-translate-y-1 transition duration-300 border-l-4 border-green-500" data-aos="fade-right" data-aos-delay="200">
                    <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-600 text-xl mb-4">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-800 mb-2">Email Us</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        <a href="mailto:hello@ebiotheraphy.com" class="hover:text-teal-600 transition">hello@ebiotheraphy.com</a><br>
                        <a href="mailto:support@ebiotheraphy.com" class="hover:text-teal-600 transition">support@ebiotheraphy.com</a>
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:-translate-y-1 transition duration-300 border-l-4 border-blue-500" data-aos="fade-right" data-aos-delay="300">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 text-xl mb-4">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-800 mb-2">Call Us</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Mon-Fri from 8am to 5pm.<br>
                        <span class="text-lg font-bold text-gray-800">+1 (555) 123-4567</span>
                    </p>
                </div>
            </div>

            <!-- RIGHT: Contact Form -->
            <div class="lg:col-span-2" data-aos="fade-left" data-aos-delay="200">
                <div class="glass-panel p-8 md:p-12 rounded-3xl h-full relative overflow-hidden">
                    <!-- Decorative Circle -->
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-teal-50 rounded-full blur-3xl -z-10"></div>
                    
                    <div class="mb-10">
                        <h2 class="font-serif text-3xl font-bold text-gray-900 mb-2">Send a Message</h2>
                        <p class="text-gray-500">We typically reply within 2 hours during business days.</p>
                    </div>

                    <?php if($msg != ''): ?>
                        <div class="mb-8 p-4 rounded-xl border-l-4 flex items-center <?php echo $msgClass; ?>">
                            <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide ml-1">Full Name</label>
                                <input type="text" name="name" class="input-field w-full px-4 py-4 rounded-xl bg-gray-50/50 outline-none text-gray-700 placeholder-gray-400 font-medium" placeholder="Jane Doe" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide ml-1">Email Address</label>
                                <input type="email" name="email" class="input-field w-full px-4 py-4 rounded-xl bg-gray-50/50 outline-none text-gray-700 placeholder-gray-400 font-medium" placeholder="jane@example.com" required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide ml-1">Subject</label>
                            <input type="text" name="subject" class="input-field w-full px-4 py-4 rounded-xl bg-gray-50/50 outline-none text-gray-700 placeholder-gray-400 font-medium" placeholder="Product Inquiry, Order Status, etc.">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide ml-1">Message</label>
                            <textarea name="message" rows="5" class="input-field w-full px-4 py-4 rounded-xl bg-gray-50/50 outline-none text-gray-700 placeholder-gray-400 font-medium resize-none" placeholder="How can we help you today?" required></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 px-6 rounded-xl bg-gradient-to-r from-teal-700 to-green-600 text-white font-bold text-lg shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 hover:-translate-y-1 transition duration-300 flex items-center justify-center gap-3 group">
                            Send Message 
                            <i class="fas fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- MAP SECTION (Full Width) -->
<section class="h-96 w-full relative grayscale hover:grayscale-0 transition duration-700">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509374!2d144.9537353153167!3d-37.81732767975171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf577d337f903a95!2sMelbourne%20VIC%2C%20Australia!5e0!3m2!1sen!2sus!4v1633074495535!5m2!1sen!2sus" 
        width="100%" 
        height="100%" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy">
    </iframe>
    <!-- Overlay Card -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-white px-8 py-4 rounded-full shadow-2xl flex items-center gap-3">
        <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
        <span class="font-bold text-gray-800 text-sm">Open Now: 8:00 AM - 6:00 PM</span>
    </div>
</section>

<!-- SCRIPT FOR LEAVES & ANIMATIONS -->
<script>
    // Falling Leaves Logic
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('leaves');
        const leafCount = 15;
        const colors = ['#0f766e', '#16a34a', '#99f6e4', '#134e4a']; // Various greens/teals

        for (let i = 0; i < leafCount; i++) {
            const leaf = document.createElement('i');
            leaf.classList.add('fas', 'fa-leaf', 'leaf');
            
            // Randomize leaf properties
            const startLeft = Math.random() * 100;
            const duration = Math.random() * 10 + 10; // 10-20s fall time
            const delay = Math.random() * 5;
            const size = Math.random() * 20 + 10; // 10-30px size
            const color = colors[Math.floor(Math.random() * colors.length)];
            
            leaf.style.left = startLeft + '%';
            leaf.style.fontSize = size + 'px';
            leaf.style.color = color;
            leaf.style.animationDuration = duration + 's';
            leaf.style.animationDelay = delay + 's';
            
            container.appendChild(leaf);
        }
    });
</script>

<!-- Include Footer -->
<?php include 'footer.php'; ?>