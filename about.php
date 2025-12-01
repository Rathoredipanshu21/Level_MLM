<?php
// Optional: Database connection if needed for dynamic content
include_once 'config/db.php'; 
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>About Us | eBiotheraphy Story</title>
    
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

        /* Glassmorphism Header */
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Image Collage Styles */
        .collage-wrapper { position: relative; height: 600px; width: 100%; }
        .collage-1 {
            position: absolute;
            top: 0; right: 0;
            width: 70%; height: 85%;
            object-fit: cover;
            border-radius: 0 0 0 100px;
            z-index: 10;
        }
        .collage-2 {
            position: absolute;
            bottom: 0; left: 0;
            width: 45%; height: 50%;
            object-fit: cover;
            border-radius: 20px;
            z-index: 20;
            border: 8px solid #fff;
        }
        .collage-decoration {
            position: absolute;
            top: 40%; left: 35%;
            width: 150px; height: 150px;
            background: rgba(13, 148, 136, 0.1);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            z-index: 15;
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
        <div class="absolute top-0 right-0 w-1/3 h-full bg-teal-50 -z-0"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <!-- Text Content -->
                <div data-aos="fade-right" data-aos-duration="1000">
                    <span class="text-teal-600 font-bold uppercase tracking-[0.2em] text-xs mb-4 block">Since 2015</span>
                    <h1 class="font-serif text-6xl lg:text-7xl font-bold text-gray-900 leading-none mb-8">
                        Rooted in Nature,<br>
                        <span class="text-gradient italic">Backed by Data.</span>
                    </h1>
                    <p class="text-lg text-gray-600 leading-relaxed mb-8 font-light border-l-4 border-teal-200 pl-6">
                        eBiotheraphy began with a simple question: What if we could take the ancient wisdom of herbal medicine and validate it with modern clinical trials? The result is a new category of wellness.
                    </p>
                    
                    <div class="flex items-center gap-8 mt-10">
                        <div class="text-center">
                            <h3 class="text-4xl font-bold text-teal-800 font-serif">50+</h3>
                            <p class="text-xs uppercase tracking-widest text-gray-500 mt-1">Countries Served</p>
                        </div>
                        <div class="w-px h-12 bg-gray-200"></div>
                        <div class="text-center">
                            <h3 class="text-4xl font-bold text-teal-800 font-serif">10k+</h3>
                            <p class="text-xs uppercase tracking-widest text-gray-500 mt-1">Success Stories</p>
                        </div>
                    </div>
                </div>

                <!-- Collage Image Composition -->
                <div class="relative collage-wrapper" data-aos="fade-left" data-aos-duration="1200">
                    <!-- Main Image (Top Right) -->
                    <img src="https://images.unsplash.com/photo-1759818890781-d651821a97c5?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTh8fEhlcmJhbCUyMFByZXBhcmF0aW9ufGVufDB8fDB8fHww" class="collage-1 shadow-2xl" alt="Herbal Preparation">
                    
                    <!-- Secondary Image (Bottom Left) -->
                    <img src="https://images.unsplash.com/photo-1507041957456-9c397ce39c97?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="collage-2 shadow-2xl" alt="Scientist in Lab">
                    
                    <!-- Decorative Element -->
                    <div class="collage-decoration flex items-center justify-center animate-pulse">
                        <i class="fas fa-leaf text-4xl text-teal-700 opacity-50"></i>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. MISSION & VALUES -->
    <section class="py-24 bg-teal-900 text-white relative overflow-hidden z-20">
        <!-- Abstract patterns -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-96 h-96 bg-teal-500 rounded-full blur-[100px] opacity-20"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20" data-aos="fade-up">
                <h2 class="font-serif text-4xl md:text-5xl mb-6">Our Core Philosophy</h2>
                <p class="text-teal-100 text-lg font-light leading-relaxed">
                    We don't just sell supplements. We are architects of a healthier future, building a bridge between the raw power of the earth and the precision of the laboratory.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Value 1 -->
                <div class="bg-white/5 backdrop-blur border border-white/10 p-8 rounded-2xl hover:bg-white/10 transition duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-teal-800 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-hand-holding-heart text-teal-300"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 font-serif">Transparency First</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">We list every single ingredient. No proprietary blends hidden behind vague labels. You deserve to know what you put in your body.</p>
                </div>

                <!-- Value 2 -->
                <div class="bg-white/5 backdrop-blur border border-white/10 p-8 rounded-2xl hover:bg-white/10 transition duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-teal-800 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-recycle text-teal-300"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 font-serif">Regenerative Impact</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">We source from farms that heal the soil. Every bottle purchased contributes to reforestation projects in the Amazon.</p>
                </div>

                <!-- Value 3 -->
                <div class="bg-white/5 backdrop-blur border border-white/10 p-8 rounded-2xl hover:bg-white/10 transition duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-teal-800 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-microscope text-teal-300"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 font-serif">Clinical Precision</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Traditional herbs, optimized. We use nano-emulsion technology to increase absorption rates by up to 300%.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. THE FOUNDER'S JOURNEY (Timeline-ish) -->
    <section class="py-24 bg-gray-50 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <div class="relative" data-aos="fade-right">
                    <div class="absolute inset-0 bg-teal-900 rounded-[2rem] transform rotate-3 opacity-10"></div>
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Founder" class="relative rounded-[2rem] shadow-2xl w-full object-cover h-[600px] grayscale hover:grayscale-0 transition duration-700">
                    
                    <div class="absolute bottom-8 left-8 bg-white/90 backdrop-blur p-6 rounded-xl shadow-lg max-w-xs">
                        <p class="font-serif text-xl font-bold text-gray-900">Dr. Eleanor Vane</p>
                        <p class="text-xs text-teal-700 uppercase tracking-widest">Founder & Lead Botanist</p>
                    </div>
                </div>

                <div data-aos="fade-left">
                    <h2 class="font-serif text-4xl font-bold text-gray-900 mb-8">A Journey from the Lab to the Forest</h2>
                    
                    <div class="space-y-12 border-l border-gray-300 pl-8 ml-4">
                        <div class="relative">
                            <span class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-teal-600 border-4 border-white shadow"></span>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">2010: The Realization</h4>
                            <p class="text-gray-600 font-light">While working in pharmaceutical research, Dr. Vane realized that synthetic compounds often caused more side effects than solutions. She began studying ethno-botany.</p>
                        </div>

                        <div class="relative">
                            <span class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-teal-600 border-4 border-white shadow"></span>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">2015: The Discovery</h4>
                            <p class="text-gray-600 font-light">Deep in the Peruvian Andes, she discovered a specific extraction method used by locals that preserved the vitality of the Maca root. eBiotheraphy was born.</p>
                        </div>

                        <div class="relative">
                            <span class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-teal-600 border-4 border-white shadow"></span>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">2020: Global Expansion</h4>
                            <p class="text-gray-600 font-light">With a team of 50 scientists and ethical partnerships with 200+ farmers, we launched our global platform to bring healing to the world.</p>
                        </div>
                    </div>
                    
                    <div class="mt-12">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Signature_sample.svg/1200px-Signature_sample.svg.png" class="h-12 opacity-50" alt="Signature">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 4. MEET THE EXPERTS (Grid) -->
    <section class="py-24 bg-white relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-down">
                <span class="text-teal-600 font-bold uppercase tracking-widest text-xs">The Minds Behind the Magic</span>
                <h2 class="font-serif text-4xl font-bold mt-2">Our Scientific Advisory Board</h2>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <!-- Member 1 -->
                <div class="group text-center" data-aos="zoom-in" data-aos-delay="0">
                    <div class="relative overflow-hidden rounded-2xl mb-4 aspect-[3/4]">
                        <img src="https://images.unsplash.com/photo-1537368910025-700350fe46c7?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-teal-900/0 group-hover:bg-teal-900/60 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <div class="flex gap-4">
                                <a href="#" class="text-white hover:text-teal-200"><i class="fab fa-linkedin text-2xl"></i></a>
                                <a href="#" class="text-white hover:text-teal-200"><i class="fas fa-envelope text-2xl"></i></a>
                            </div>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg text-gray-900">Dr. James Chen</h4>
                    <p class="text-xs text-teal-600 uppercase tracking-wide">Neurology Specialist</p>
                </div>

                <!-- Member 2 -->
                <div class="group text-center" data-aos="zoom-in" data-aos-delay="100">
                    <div class="relative overflow-hidden rounded-2xl mb-4 aspect-[3/4]">
                        <img src="https://images.unsplash.com/photo-1559839734-2b71ea86b48e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-teal-900/0 group-hover:bg-teal-900/60 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <div class="flex gap-4">
                                <a href="#" class="text-white hover:text-teal-200"><i class="fab fa-linkedin text-2xl"></i></a>
                                <a href="#" class="text-white hover:text-teal-200"><i class="fas fa-envelope text-2xl"></i></a>
                            </div>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg text-gray-900">Sarah O'Conner</h4>
                    <p class="text-xs text-teal-600 uppercase tracking-wide">Head of Sourcing</p>
                </div>

                <!-- Member 3 -->
                <div class="group text-center" data-aos="zoom-in" data-aos-delay="200">
                    <div class="relative overflow-hidden rounded-2xl mb-4 aspect-[3/4]">
                        <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-teal-900/0 group-hover:bg-teal-900/60 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <div class="flex gap-4">
                                <a href="#" class="text-white hover:text-teal-200"><i class="fab fa-linkedin text-2xl"></i></a>
                                <a href="#" class="text-white hover:text-teal-200"><i class="fas fa-envelope text-2xl"></i></a>
                            </div>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg text-gray-900">Dr. Marcus Webb</h4>
                    <p class="text-xs text-teal-600 uppercase tracking-wide">Biochemist</p>
                </div>

                <!-- Member 4 -->
                <div class="group text-center" data-aos="zoom-in" data-aos-delay="300">
                    <div class="relative overflow-hidden rounded-2xl mb-4 aspect-[3/4]">
                        <img src="https://images.unsplash.com/photo-1594824476967-48c8b964273f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-teal-900/0 group-hover:bg-teal-900/60 transition duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <div class="flex gap-4">
                                <a href="#" class="text-white hover:text-teal-200"><i class="fab fa-linkedin text-2xl"></i></a>
                                <a href="#" class="text-white hover:text-teal-200"><i class="fas fa-envelope text-2xl"></i></a>
                            </div>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg text-gray-900">Elena Rodriguez</h4>
                    <p class="text-xs text-teal-600 uppercase tracking-wide">Holistic Nutritionist</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. STATS & CTA -->
    <section class="py-24 bg-gradient-to-r from-teal-800 to-teal-900 text-white relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right">
                    <h2 class="font-serif text-4xl font-bold mb-6">Ready to Start Your Journey?</h2>
                    <p class="text-teal-100 text-lg mb-8 font-light">
                        Join over 50,000 members who have reclaimed their vitality through the power of eBiotheraphy.
                    </p>
                    <div class="flex gap-4">
                        <a href="index.php#collection" class="px-8 py-3 bg-white text-teal-900 font-bold rounded-full hover:bg-teal-50 transition shadow-lg">View Products</a>
                        <a href="index2.php" class="px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-teal-900 transition">Member Login</a>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6" data-aos="fade-left">
                    <div class="bg-white/10 p-6 rounded-xl backdrop-blur text-center border border-white/20">
                        <i class="fas fa-shipping-fast text-3xl mb-3 text-teal-300"></i>
                        <h4 class="font-bold text-xl">24h</h4>
                        <p class="text-xs opacity-70">Dispatch Time</p>
                    </div>
                    <div class="bg-white/10 p-6 rounded-xl backdrop-blur text-center border border-white/20">
                        <i class="fas fa-certificate text-3xl mb-3 text-teal-300"></i>
                        <h4 class="font-bold text-xl">ISO</h4>
                        <p class="text-xs opacity-70">Certified Labs</p>
                    </div>
                    <div class="bg-white/10 p-6 rounded-xl backdrop-blur text-center border border-white/20">
                        <i class="fas fa-users text-3xl mb-3 text-teal-300"></i>
                        <h4 class="font-bold text-xl">50k+</h4>
                        <p class="text-xs opacity-70">Happy Users</p>
                    </div>
                    <div class="bg-white/10 p-6 rounded-xl backdrop-blur text-center border border-white/20">
                        <i class="fas fa-star text-3xl mb-3 text-teal-300"></i>
                        <h4 class="font-bold text-xl">4.9</h4>
                        <p class="text-xs opacity-70">Avg Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

   

    <!-- SCRIPTS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init AOS
        AOS.init({ duration: 1000, once: true, offset: 50 });

        // Falling Leaves Logic
        function createLeaves() {
            const container = document.getElementById('leaves');
            const leafCount = 12; 
            const colors = ['#0f766e', '#16a34a', '#86efac', '#134e4a'];

            for (let i = 0; i < leafCount; i++) {
                const leaf = document.createElement('i');
                leaf.classList.add('fas', 'fa-leaf', 'leaf');
                
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