<?php
// Optional: Database connection
include_once 'config/db.php'; 

// Extended Product Data (Mock Data)
$products = [
    [
        "id" => 1,
        "name" => "Vitality Elixir",
        "cat" => "Immunity",
        "price" => "$45.00",
        "rating" => 4.9,
        "image" => "https://images.unsplash.com/photo-1660033572333-b4c143c240a5?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjB8fFZpdGFsaXR5JTIwRWxpeGlyfGVufDB8fDB8fHww",
        "tag" => "Bestseller"
    ],
    [
        "id" => 2,
        "name" => "Serenity Drops",
        "cat" => "Stress Relief",
        "price" => "$38.00",
        "rating" => 4.8,
        "image" => "https://images.unsplash.com/photo-1576673442511-7e39b6545c87?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80",
        "tag" => ""
    ],
    [
        "id" => 3,
        "name" => "Digest Zen Tea",
        "cat" => "Gut Health",
        "price" => "$22.00",
        "rating" => 4.7,
        "image" => "https://images.unsplash.com/photo-1585163688441-b3e2b4c1dfcb?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8RGlnZXN0JTIwWmVuJTIwVGVhfGVufDB8fDB8fHww",
        "tag" => "New"
    ],
    [
        "id" => 4,
        "name" => "Focus Tincture",
        "cat" => "Cognitive",
        "price" => "$55.00",
        "rating" => 5.0,
        "image" => "https://images.unsplash.com/photo-1555633514-abcee6ab92e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80",
        "tag" => ""
    ],
    [
        "id" => 5,
        "name" => "Deep Sleep Oil",
        "cat" => "Recovery",
        "price" => "$42.00",
        "rating" => 4.9,
        "image" => "https://images.unsplash.com/photo-1633122368233-9320b631a0f6?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8RGVlcCUyMFNsZWVwJTIwT2lsfGVufDB8fDB8fHww",
        "tag" => "Trending"
    ],
    [
        "id" => 6,
        "name" => "Joint Relief Cream",
        "cat" => "Topical",
        "price" => "$30.00",
        "rating" => 4.6,
        "image" => "https://media.istockphoto.com/id/1363546578/photo/focus-on-hands-senior-man-applying-ointment-cream-for-joint-knee-pain-at-home-while-sitting.webp?a=1&b=1&s=612x612&w=0&k=20&c=3QMWu5sst2ZqB3mIB5iqlMEfF6OGbUEFHLspnL39g2Y=",
        "tag" => ""
    ],
    [
        "id" => 7,
        "name" => "Detox Green Powder",
        "cat" => "Cleanse",
        "price" => "$48.00",
        "rating" => 4.8,
        "image" => "https://plus.unsplash.com/premium_photo-1661436015008-d4ece6914f66?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8RGV0b3glMjBHcmVlbiUyMFBvd2RlcnxlbnwwfHwwfHx8MA%3D%3D",
        "tag" => ""
    ],
    [
        "id" => 8,
        "name" => "Radiance Serum",
        "cat" => "Beauty",
        "price" => "$60.00",
        "rating" => 5.0,
        "image" => "https://images.unsplash.com/photo-1656147962243-37f06ef2b974?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8UmFkaWFuY2UlMjBTZXJ1bXxlbnwwfHwwfHx8MA%3D%3D",
        "tag" => "Luxury"
    ]
];
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Our Collection | eBiotheraphy</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Montserrat:wght@200;300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Typography */
        .font-serif { font-family: 'Cormorant Garamond', serif; }
        .font-sans { font-family: 'Montserrat', sans-serif; }
        
        /* Scrollbar */
        body { overflow-x: hidden; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #fff; }
        ::-webkit-scrollbar-thumb { background: #115e59; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #0f766e; }

        /* Falling Leaves Animation */
        .leaf-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            pointer-events: none;
            z-index: 1; 
            overflow: hidden;
        }
        .leaf {
            position: absolute;
            top: -10%;
            opacity: 0.8;
            animation: falling linear infinite;
        }
        @keyframes falling {
            0% { transform: translate(0, 0) rotate(0deg); opacity: 1; }
            100% { transform: translate(100px, 100vh) rotate(360deg); opacity: 0; }
        }

        /* Glassmorphism Header & Filters */
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Hero Collage Styles */
        .collage-wrapper { position: relative; height: 600px; width: 100%; }
        .collage-1 {
            position: absolute;
            top: 0; right: 0;
            width: 65%; height: 80%;
            object-fit: cover;
            border-radius: 0 0 0 80px;
            z-index: 10;
        }
        .collage-2 {
            position: absolute;
            bottom: 50px; left: 20px;
            width: 50%; height: 55%;
            object-fit: cover;
            border-radius: 20px;
            z-index: 20;
            border: 8px solid #fff;
        }
        .collage-circle {
            position: absolute;
            top: 20%; left: 30%;
            width: 200px; height: 200px;
            background: linear-gradient(135deg, rgba(13,148,136,0.2), rgba(22,163,74,0.1));
            backdrop-filter: blur(8px);
            border-radius: 50%;
            z-index: 15;
            animation: pulse 4s infinite ease-in-out;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Product Card Styles */
        .product-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .product-card:hover {
            transform: translateY(-10px);
        }
        .product-image-container {
            overflow: hidden;
            position: relative;
        }
        .product-img {
            transition: transform 0.7s ease;
        }
        .product-card:hover .product-img {
            transform: scale(1.1);
        }
        .action-btn {
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        .product-card:hover .action-btn {
            transform: translateY(0);
            opacity: 1;
        }
        
        .text-gradient {
            background: linear-gradient(to right, #134e4a, #0d9488);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="font-sans text-gray-700 antialiased bg-gray-50 relative selection:bg-teal-200">
<?php include 'navbar.php'; ?>
    <!-- FALLING LEAVES LAYER -->
    <div id="leaves" class="leaf-container"></div>

   
    <!-- 1. HERO SECTION (Collage Design) -->
    <section class="relative pt-32 pb-20 overflow-hidden bg-white">
        <!-- Abstract Bg -->
        <div class="absolute top-0 left-0 w-1/3 h-full bg-gradient-to-br from-teal-50 to-white -z-0"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <!-- Text Content -->
                <div data-aos="fade-right" data-aos-duration="1000">
                    <span class="inline-block py-1 px-3 rounded-full bg-teal-100 text-teal-800 text-xs font-bold tracking-widest mb-6">NEW ARRIVALS</span>
                    <h1 class="font-serif text-6xl lg:text-7xl font-bold text-gray-900 leading-none mb-6">
                        Curated <br>
                        <span class="text-gradient italic">Wellness.</span>
                    </h1>
                    <p class="text-lg text-gray-600 leading-relaxed mb-8 font-light max-w-md">
                        Explore our full range of ethically sourced, scientifically formulated herbal supplements designed to elevate your daily ritual.
                    </p>
                    
                    <!-- Search/Filter Bar -->
                    <div class="bg-white p-2 rounded-full shadow-xl border border-gray-100 max-w-md flex">
                        <input type="text" placeholder="Search for 'Sleep' or 'Energy'..." class="flex-grow px-6 py-3 rounded-full outline-none text-gray-600 placeholder-gray-400">
                        <button class="w-12 h-12 bg-teal-800 rounded-full text-white flex items-center justify-center hover:bg-teal-700 transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Collage Image Composition -->
                <div class="relative collage-wrapper" data-aos="fade-left" data-aos-duration="1200">
                    <!-- Main Product Image -->
                    <img src="https://images.unsplash.com/photo-1695938104182-d9b77375e6de?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8aGVyYmFsJTIwcHJvZHVjdCUyMGJvdHRsZXxlbnwwfHwwfHx8MA%3D%3D" class="collage-1 shadow-2xl" alt="Product Bottle">
                    
                    <!-- Lifestyle Image -->
                    <img src="https://images.unsplash.com/photo-1635367216109-aa3353c0c22e?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8bGlmZXN0eWxlJTIwd2VsbG5lc3N8ZW58MHx8MHx8fDA%3D" class="collage-2 shadow-2xl" alt="Lifestyle Wellness">
                    
                    <!-- Decorative Circle -->
                    <div class="collage-circle flex items-center justify-center">
                        <div class="text-center transform rotate-12">
                            <p class="text-3xl font-serif font-bold text-teal-900">100%</p>
                            <p class="text-xs uppercase tracking-widest text-teal-700">Organic</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. CATEGORY FILTERS (Horizontal Scroll) -->
    <div class="bg-white border-y border-gray-100 sticky top-20 z-40 shadow-sm backdrop-blur-md bg-white/90">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                <button class="px-6 py-2 bg-teal-900 text-white rounded-full text-sm font-bold whitespace-nowrap shadow-lg">All Products</button>
                <button class="px-6 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold whitespace-nowrap hover:bg-teal-50 hover:text-teal-800 transition">Immunity</button>
                <button class="px-6 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold whitespace-nowrap hover:bg-teal-50 hover:text-teal-800 transition">Stress Relief</button>
                <button class="px-6 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold whitespace-nowrap hover:bg-teal-50 hover:text-teal-800 transition">Cognitive Health</button>
                <button class="px-6 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold whitespace-nowrap hover:bg-teal-50 hover:text-teal-800 transition">Beauty & Skin</button>
                <button class="px-6 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold whitespace-nowrap hover:bg-teal-50 hover:text-teal-800 transition">Digestion</button>
            </div>
        </div>
    </div>

    <!-- 3. MAIN PRODUCT GRID -->
    <section class="py-20 bg-gray-50 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <h2 class="font-serif text-3xl font-bold text-gray-900">Showing 8 Results</h2>
                <div class="hidden md:flex items-center gap-2 text-sm text-gray-500">
                    <span>Sort by:</span>
                    <select class="bg-transparent font-bold text-gray-800 outline-none cursor-pointer">
                        <option>Featured</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <?php foreach($products as $index => $item): ?>
                <!-- Product Card -->
                <div class="product-card bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden group" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                    <!-- Image Area -->
                    <div class="product-image-container h-80 bg-gray-100 relative flex items-center justify-center">
                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="product-img w-full h-full object-cover">
                        
                        <!-- Badges -->
                        <?php if($item['tag']): ?>
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-teal-800 shadow-sm">
                            <?php echo $item['tag']; ?>
                        </span>
                        <?php endif; ?>

                        <!-- Quick Action Overlay -->
                        <div class="absolute inset-x-0 bottom-4 px-4 flex justify-between items-end gap-2 z-10">
                            <button class="action-btn flex-1 bg-teal-900 text-white py-3 rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-teal-800 shadow-lg delay-75">
                                Add to Cart
                            </button>
                            <button class="action-btn w-10 h-10 bg-white text-teal-900 rounded-lg flex items-center justify-center hover:bg-gray-50 shadow-lg delay-100">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Details Area -->
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wide"><?php echo $item['cat']; ?></p>
                            <div class="flex text-yellow-400 text-xs">
                                <i class="fas fa-star"></i>
                                <span class="text-gray-400 ml-1"><?php echo $item['rating']; ?></span>
                            </div>
                        </div>
                        <h3 class="font-serif text-xl font-bold text-gray-900 mb-1 group-hover:text-teal-700 transition-colors cursor-pointer">
                            <?php echo $item['name']; ?>
                        </h3>
                        <p class="text-teal-800 font-bold text-lg"><?php echo $item['price']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-16">
                <nav class="flex items-center gap-2">
                    <button class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:border-teal-800 hover:text-teal-800 transition"><i class="fas fa-chevron-left"></i></button>
                    <button class="w-10 h-10 rounded-full bg-teal-900 text-white flex items-center justify-center font-bold shadow-lg">1</button>
                    <button class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-600 hover:border-teal-800 hover:text-teal-800 transition">2</button>
                    <button class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-600 hover:border-teal-800 hover:text-teal-800 transition">3</button>
                    <button class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:border-teal-800 hover:text-teal-800 transition"><i class="fas fa-chevron-right"></i></button>
                </nav>
            </div>
        </div>
    </section>

    <!-- 4. FEATURED PROMO (Dark Mode) -->
    <section class="py-24 bg-gray-900 text-white relative overflow-hidden z-20">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-900 to-gray-900 opacity-90"></div>
        <!-- Decorative leaf pattern -->
        <i class="fas fa-leaf text-[400px] absolute -right-20 -bottom-20 text-white opacity-5 rotate-45"></i>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <span class="text-teal-400 font-bold uppercase tracking-widest text-xs">Product of the Month</span>
                    <h2 class="font-serif text-4xl md:text-5xl font-bold mt-4 mb-6">The "Zen Master" Bundle</h2>
                    <p class="text-gray-400 text-lg leading-relaxed mb-8">
                        Combine our Deep Sleep Oil with the Serenity Drops for a complete nervous system reset. Clinically proven to lower cortisol by 40% in just two weeks.
                    </p>
                    <div class="flex gap-4">
                        <button class="px-8 py-4 bg-white text-teal-900 font-bold rounded-lg hover:bg-teal-50 transition shadow-lg">Shop Bundle - $70</button>
                        <button class="px-8 py-4 border border-gray-600 text-white font-bold rounded-lg hover:border-white transition">Learn More</button>
                    </div>
                </div>
                <div class="relative" data-aos="zoom-in">
                    <div class="absolute inset-0 bg-teal-500 rounded-full blur-[80px] opacity-20"></div>
                    <img src="https://images.unsplash.com/photo-1695938104182-d9b77375e6de?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8aGVyYmFsJTIwcHJvZHVjdCUyMGJvdHRsZXxlbnwwfHwwfHx8MA%3D%3D" class="relative z-10 rounded-2xl shadow-2xl transform rotate-3 hover:rotate-0 transition duration-500 cursor-pointer w-3/4 mx-auto">
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- SCRIPTS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init AOS
        AOS.init({ duration: 800, once: true, offset: 50 });

        // Falling Leaves Logic
        function createLeaves() {
            const container = document.getElementById('leaves');
            const leafCount = 15; 
            const colors = ['#0f766e', '#16a34a', '#86efac', '#134e4a'];

            for (let i = 0; i < leafCount; i++) {
                const leaf = document.createElement('i');
                leaf.classList.add('fas', 'fa-leaf', 'leaf');
                
                // Random properties
                const startLeft = Math.random() * 100;
                const duration = Math.random() * 5 + 5;
                const delay = Math.random() * 5;
                const size = Math.random() * 20 + 10;
                const color = colors[Math.floor(Math.random() * colors.length)];
                
                leaf.style.left = startLeft + '%';
                leaf.style.fontSize = size + 'px';
                leaf.style.color = color;
                leaf.style.animationDuration = duration + 's';
                leaf.style.animationDelay = delay + 's';
                
                container.appendChild(leaf);
            }
        }
        createLeaves();
    </script>
</body>
</html>