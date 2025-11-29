<?php
// No external navbar/footer includes as requested.
// Database connection (optional)
include_once 'config/db.php'; 

// Mock Data for Products to keep code clean
$products = [
    [
        'name' => 'Circadian Reset',
        'cat' => 'Sleep & Recovery',
        'price' => '39.00',
        'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=400&q=80'
    ],
    [
        'name' => 'Neuro Focus',
        'cat' => 'Cognitive Health',
        'price' => '45.00',
        'img' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=400&q=80'
    ],
    [
        'name' => 'Immuno Shield',
        'cat' => 'Immunity',
        'price' => '32.00',
        'img' => 'https://images.unsplash.com/photo-1631549916768-4119b2e5f926?auto=format&fit=crop&w=400&q=80'
    ],
    [
        'name' => 'Vitality Greens',
        'cat' => 'Daily Wellness',
        'price' => '55.00',
        'img' => 'https://images.unsplash.com/photo-1593037515494-3b5033ee268d?auto=format&fit=crop&w=400&q=80'
    ],
    [
        'name' => 'Stress Relief',
        'cat' => 'Mood Balance',
        'price' => '42.00',
        'img' => 'https://images.unsplash.com/photo-1616671225572-36c97e189354?auto=format&fit=crop&w=400&q=80'
    ],
    [
        'name' => 'Joint Flex',
        'cat' => 'Mobility',
        'price' => '38.00',
        'img' => 'https://images.unsplash.com/photo-1550572017-4fcd95616f73?auto=format&fit=crop&w=400&q=80'
    ],
    [
        'name' => 'Gut Harmony',
        'cat' => 'Digestion',
        'price' => '49.00',
        'img' => 'https://images.unsplash.com/photo-1607619056574-7b8d3ee536b5?auto=format&fit=crop&w=400&q=80'
    ],
    [
        'name' => 'Deep Sleep',
        'cat' => 'Rest',
        'price' => '35.00',
        'img' => 'https://images.unsplash.com/photo-1551462147-37885acc36f1?auto=format&fit=crop&w=400&q=80'
    ]
];
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>eBiotheraphy | The Future of Cellular Wellness</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Custom Font Bindings */
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Hide Horizontal Scroll */
        body { overflow-x: hidden; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #0d9488; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #0f766e; }

        /* Blob Animations */
        @keyframes float {
            0% { transform: translate(0px, 0px); }
            50% { transform: translate(-20px, 10px); }
            100% { transform: translate(0px, 0px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        /* Text Gradients */
        .text-gradient {
            background: linear-gradient(135deg, #16a34a 0%, #0d9488 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Collage Image Styles */
        .collage-img {
            transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .collage-img:hover {
            transform: scale(1.03);
        }

        /* FALLING LEAVES ANIMATION CSS */
        #leaves-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none; /* Click through */
            z-index: 50; /* Behind modal, in front of background */
            overflow: hidden;
        }

        .leaf {
            position: absolute;
            top: -50px;
            width: 30px;
            height: 30px;
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0;
            animation: fall linear infinite;
        }

        @keyframes fall {
            0% {
                opacity: 0;
                top: -50px;
                transform: translateX(0) rotate(0deg);
            }
            10% {
                opacity: 0.8;
            }
            90% {
                opacity: 0.8;
            }
            100% {
                opacity: 0;
                top: 110vh; /* Fall off screen */
                transform: translateX(100px) rotate(360deg);
            }
        }
    </style>
</head>
<body class="font-sans text-gray-800 antialiased bg-gray-50 selection:bg-teal-200 selection:text-teal-900 relative">

    <div id="leaves-container"></div>


    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <div class="absolute top-0 right-0 w-[50vw] h-[50vw] bg-green-200/30 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/4 animate-float"></div>
        <div class="absolute bottom-0 left-0 w-[40vw] h-[40vw] bg-blue-200/30 rounded-full blur-[80px] translate-y-1/3 -translate-x-1/4 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                
                <div data-aos="fade-right" data-aos-duration="1000">
                    <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-700 text-xs font-bold tracking-wider mb-6 border border-green-200">
                        NEW FORMULA 2.0 RELEASED
                    </span>
                    <h1 class="font-serif text-5xl md:text-7xl leading-[1.1] font-bold text-gray-900 mb-6">
                        Reclaim Your <br>
                        <span class="text-gradient">Natural Vitality</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed max-w-lg">
                        The world's first bio-adaptive supplement designed to synchronize with your body's circadian rhythm. 100% Organic. Zero compromises.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#products" class="px-8 py-4 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-full font-bold shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition transform hover:-translate-y-1 text-center">
                            Explore Products
                        </a>
                        <a href="#science" class="px-8 py-4 bg-white text-gray-700 border border-gray-200 rounded-full font-bold hover:bg-gray-50 transition flex items-center justify-center gap-2">
                            <i class="fas fa-flask text-teal-600"></i> The Science
                        </a>
                    </div>

                    <div class="mt-12 flex items-center gap-6 border-t border-gray-200 pt-8">
                        <div>
                            <p class="text-3xl font-bold text-gray-900">4.9<span class="text-base text-gray-400 font-normal">/5</span></p>
                            <div class="flex text-yellow-400 text-xs mt-1">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="h-10 w-px bg-gray-200"></div>
                        <div>
                            <p class="text-3xl font-bold text-gray-900">50k+</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">Active Members</p>
                        </div>
                    </div>
                </div>

                <div class="relative" data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="200">
                    <div class="relative z-20 mx-auto w-3/4 lg:w-full">
                        <img src="https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="eBiotheraphy Bottle" class="rounded-3xl shadow-2xl drop-shadow-2xl transform hover:scale-105 transition duration-700">
                    </div>
                    
                    <div class="absolute top-1/4 -left-6 bg-white/90 backdrop-blur p-4 rounded-2xl shadow-xl z-30 animate-float" style="animation-delay: 1s;">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-100 p-2 rounded-lg text-green-600">
                                <i class="fas fa-leaf text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500">Source</p>
                                <p class="font-bold text-gray-800">100% Plant Based</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute bottom-10 -right-4 bg-white/90 backdrop-blur p-4 rounded-2xl shadow-xl z-30 animate-float" style="animation-delay: 3s;">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500">Quality</p>
                                <p class="font-bold text-gray-800">Lab Tested</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="py-10 border-y border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-center gap-8 md:gap-16 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
            <i class="fab fa-envira text-4xl hover:text-green-600 transition"></i>
            <i class="fab fa-nutritionix text-4xl hover:text-green-600 transition"></i>
            <i class="fas fa-leaf text-4xl hover:text-green-600 transition"></i>
            <i class="fab fa-pagelines text-4xl hover:text-green-600 transition"></i>
            <i class="fas fa-seedling text-4xl hover:text-green-600 transition"></i>
        </div>
    </div>

    <section class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <div class="grid grid-cols-2 gap-4 relative">
                    <div class="space-y-4 pt-12">
                        <img src="https://images.unsplash.com/photo-1495461199391-8c39ab674295?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fGhlcmJhbHxlbnwwfHwwfHx8MA%3D%3D" class="collage-img rounded-2xl shadow-lg w-full h-64 object-cover" data-aos="fade-up">
                        <img src="https://images.unsplash.com/photo-1615486511484-92e172cc4fe0?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="collage-img rounded-2xl shadow-lg w-full h-48 object-cover" data-aos="fade-up" data-aos-delay="100">
                    </div>
                    <div class="space-y-4">
                        <img src="https://images.unsplash.com/photo-1505751172876-fa1923c5c528?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="collage-img rounded-2xl shadow-lg w-full h-56 object-cover" data-aos="fade-up" data-aos-delay="200">
                        <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="collage-img rounded-2xl shadow-lg w-full h-72 object-cover" data-aos="fade-up" data-aos-delay="300">
                    </div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[80%] bg-teal-50 rounded-full -z-10 blur-3xl"></div>
                </div>

                <div data-aos="fade-left">
                    <h4 class="text-teal-600 font-bold uppercase tracking-widest text-sm mb-3">Our Philosophy</h4>
                    <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-6">Born from Nature. <br>Refined by Science.</h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        At eBiotheraphy, we believe the body has an innate ability to heal itself. Our mission is to provide the raw materials it needs to perform that miracle. 
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-8">
                        We spent 5 years traveling from the Amazonian rainforests to the high peaks of the Himalayas to source ingredients that are not just "organic," but **bio-active**.
                    </p>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="pl-4 border-l-4 border-teal-500">
                            <h5 class="font-bold text-xl text-gray-800">Eco-Conscious</h5>
                            <p class="text-sm text-gray-500 mt-1">Sustainable farming practices only.</p>
                        </div>
                        <div class="pl-4 border-l-4 border-blue-500">
                            <h5 class="font-bold text-xl text-gray-800">Nano-Tech</h5>
                            <p class="text-sm text-gray-500 mt-1">High absorption delivery system.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-900 text-white relative overflow-hidden" id="science">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-serif text-4xl font-bold mb-4">Potent Ingredients</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">We don't use fillers. Every milligram in our capsule serves a purpose for your biological optimization.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-800 p-8 rounded-3xl border border-gray-700 hover:border-teal-500 transition duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-teal-900/50 rounded-2xl flex items-center justify-center text-teal-400 text-3xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-capsules"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Resveratrol Complex</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Sourced from Japanese Knotweed, this potent antioxidant activates the longevity gene (SIRT1) within your cells.</p>
                </div>

                <div class="bg-gray-800 p-8 rounded-3xl border border-gray-700 hover:border-green-500 transition duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-green-900/50 rounded-2xl flex items-center justify-center text-green-400 text-3xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-tree"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Ashwagandha KSM-66</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">The highest concentration root extract available. Clinically proven to reduce cortisol and stress levels.</p>
                </div>

                <div class="bg-gray-800 p-8 rounded-3xl border border-gray-700 hover:border-blue-500 transition duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-blue-900/50 rounded-2xl flex items-center justify-center text-blue-400 text-3xl mb-6 group-hover:scale-110 transition">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Lion's Mane Mushroom</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">A nootropic fungus that stimulates the synthesis of Nerve Growth Factor (NGF) for cognitive sharpness.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="py-24 bg-gray-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-down">
                <span class="text-teal-600 font-bold uppercase tracking-widest text-xs mb-2 block">Our Collection</span>
                <h2 class="font-serif text-4xl font-bold text-gray-900">Curated Wellness</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach($products as $index => $prod): ?>
                <div class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-gray-100 flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                    <div class="relative overflow-hidden aspect-[4/5] bg-gray-100">
                        <img src="<?php echo $prod['img']; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="<?php echo $prod['name']; ?>">
                        
                        <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition duration-300">
                            <button class="w-full py-3 bg-white/95 backdrop-blur text-gray-900 font-bold rounded-xl shadow-lg hover:bg-teal-600 hover:text-white transition">
                                Quick Add <i class="fas fa-plus ml-2"></i>
                            </button>
                        </div>
                        
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-teal-800 shadow-sm">
                            <?php echo $prod['cat']; ?>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-serif font-bold text-lg text-gray-900 leading-tight"><?php echo $prod['name']; ?></h3>
                            </div>
                            <div class="flex items-center gap-1 mb-3">
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <span class="text-xs text-gray-400 ml-1">(120+)</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100">
                            <span class="text-xl font-bold text-teal-700">$<?php echo $prod['price']; ?></span>
                            <button class="text-gray-400 hover:text-teal-600 transition"><i class="far fa-heart"></i></button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-flex items-center gap-2 text-teal-700 font-bold border-b-2 border-teal-200 pb-1 hover:text-teal-900 hover:border-teal-600 transition">
                    View Full Catalog <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <section class="py-24 bg-gradient-to-b from-teal-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12 mb-24">
                <div class="md:w-1/2 relative" data-aos="fade-right">
                    <div class="absolute -inset-4 bg-teal-200/50 rounded-full blur-2xl"></div>
                    <img src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="relative rounded-3xl shadow-2xl z-10 w-full" alt="Yoga Woman">
                </div>
                <div class="md:w-1/2" data-aos="fade-left">
                    <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-xl font-bold mb-6">1</div>
                    <h3 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-4">Enhanced Energy, <br>Minus the Crash.</h3>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        Unlike caffeine which borrows energy from tomorrow, eBiotheraphy optimizes your mitochondria—the powerhouse of the cell—to generate clean, sustainable fuel all day long.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-700"><i class="fas fa-check-circle text-teal-500 mr-3"></i> No jitters or anxiety</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check-circle text-teal-500 mr-3"></i> Improved sleep quality at night</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check-circle text-teal-500 mr-3"></i> Sustained mental focus</li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row-reverse items-center gap-12">
                <div class="md:w-1/2 relative" data-aos="fade-left">
                    <div class="absolute -inset-4 bg-blue-200/50 rounded-full blur-2xl"></div>
                    <img src="https://images.unsplash.com/photo-1552693673-1bf958298935?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="relative rounded-3xl shadow-2xl z-10 w-full" alt="Happy Man">
                </div>
                <div class="md:w-1/2" data-aos="fade-right">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl font-bold mb-6">2</div>
                    <h3 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-4">Immunity Armor <br>for Modern Living.</h3>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        In a world of pollution and stress, your immune system works overtime. Our formula provides the zinc, Vitamin D3, and adaptogens needed to keep your defenses high.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-700"><i class="fas fa-check-circle text-blue-500 mr-3"></i> Reduces inflammation</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check-circle text-blue-500 mr-3"></i> Faster recovery from workouts</li>
                        <li class="flex items-center text-gray-700"><i class="fas fa-check-circle text-blue-500 mr-3"></i> Supports gut health microbiome</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="process" class="py-24 bg-gray-900 text-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="font-serif text-4xl font-bold mb-4">The Purity Process</h2>
                <p class="text-gray-400">From earth to bottle, we track every step.</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8 text-center relative">
                <div class="hidden md:block absolute top-12 left-0 w-full h-1 bg-gradient-to-r from-teal-800 via-teal-500 to-teal-800 z-0"></div>

                <div class="relative z-10" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-teal-500 rounded-full flex items-center justify-center text-3xl mb-6 shadow-[0_0_20px_rgba(20,184,166,0.5)]">
                        <i class="fas fa-seedling text-teal-400"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">1. Sourcing</h4>
                    <p class="text-sm text-gray-400">Ethically harvested from indigenous organic farms.</p>
                </div>

                <div class="relative z-10" data-aos="fade-up" data-aos-delay="150">
                    <div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-teal-500 rounded-full flex items-center justify-center text-3xl mb-6 shadow-[0_0_20px_rgba(20,184,166,0.5)]">
                        <i class="fas fa-flask text-teal-400"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">2. Extraction</h4>
                    <p class="text-sm text-gray-400">Cold-press CO2 extraction preserves nutrient density.</p>
                </div>

                <div class="relative z-10" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-teal-500 rounded-full flex items-center justify-center text-3xl mb-6 shadow-[0_0_20px_rgba(20,184,166,0.5)]">
                        <i class="fas fa-vial text-teal-400"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">3. Testing</h4>
                    <p class="text-sm text-gray-400">Triple-checked for heavy metals and purity in 3rd party labs.</p>
                </div>

                <div class="relative z-10" data-aos="fade-up" data-aos-delay="450">
                    <div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-teal-500 rounded-full flex items-center justify-center text-3xl mb-6 shadow-[0_0_20px_rgba(20,184,166,0.5)]">
                        <i class="fas fa-box-open text-teal-400"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">4. Delivery</h4>
                    <p class="text-sm text-gray-400">UV-protected glass bottles shipped directly to your door.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-center font-serif text-4xl font-bold mb-16 text-gray-900">Formulated by Experts</h2>
            <div class="grid md:grid-cols-3 gap-10">
                <div class="text-center" data-aos="fade-up">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-teal-50 shadow-lg grayscale hover:grayscale-0 transition duration-500">
                    <h3 class="text-xl font-bold text-gray-900">Dr. James Chen</h3>
                    <p class="text-teal-600 font-medium text-sm mb-3">Chief Biochemist</p>
                    <p class="text-gray-500 text-sm">PhD from Stanford, specializing in plant-based alkaloids.</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-teal-50 shadow-lg grayscale hover:grayscale-0 transition duration-500">
                    <h3 class="text-xl font-bold text-gray-900">Sarah O'Connor</h3>
                    <p class="text-teal-600 font-medium text-sm mb-3">Holistic Nutritionist</p>
                    <p class="text-gray-500 text-sm">20 years experience in circadian rhythm optimization.</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <img src="https://randomuser.me/api/portraits/men/86.jpg" class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-teal-50 shadow-lg grayscale hover:grayscale-0 transition duration-500">
                    <h3 class="text-xl font-bold text-gray-900">Dr. Arush Patel</h3>
                    <p class="text-teal-600 font-medium text-sm mb-3">Ayurvedic Specialist</p>
                    <p class="text-gray-500 text-sm">Bridging ancient wisdom with modern extraction tech.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="reviews" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center font-serif text-4xl font-bold mb-16 text-gray-900" data-aos="fade-down">Real Results from Real People</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl relative shadow-sm hover:shadow-lg transition" data-aos="zoom-in" data-aos-delay="100">
                    <i class="fas fa-quote-left text-4xl text-teal-100 absolute top-6 left-6"></i>
                    <p class="text-gray-600 relative z-10 mb-6 italic">"I've tried everything for my brain fog. eBiotheraphy is the only thing that cleared it up in less than a week. I feel like I'm 20 again."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" class="w-12 h-12 rounded-full border-2 border-white shadow">
                        <div>
                            <p class="font-bold text-gray-900">Emily R.</p>
                            <p class="text-xs text-teal-600">Verified Buyer</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl relative shadow-sm hover:shadow-lg transition" data-aos="zoom-in" data-aos-delay="200">
                    <i class="fas fa-quote-left text-4xl text-teal-100 absolute top-6 left-6"></i>
                    <p class="text-gray-600 relative z-10 mb-6 italic">"My gym recovery time has been cut in half. The inflammation in my joints is gone. This is a game changer for athletes."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-12 h-12 rounded-full border-2 border-white shadow">
                        <div>
                            <p class="font-bold text-gray-900">Michael Ross</p>
                            <p class="text-xs text-teal-600">Crossfit Coach</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl relative shadow-sm hover:shadow-lg transition" data-aos="zoom-in" data-aos-delay="300">
                    <i class="fas fa-quote-left text-4xl text-teal-100 absolute top-6 left-6"></i>
                    <p class="text-gray-600 relative z-10 mb-6 italic">"I was skeptical about the 'MLM' stigma, but the product speaks for itself. It's high quality, effective, and purely organic."</p>
                    <div class="flex items-center gap-4">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" class="w-12 h-12 rounded-full border-2 border-white shadow">
                        <div>
                            <p class="font-bold text-gray-900">Elena Gomez</p>
                            <p class="text-xs text-teal-600">Nutritionist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="offer" class="py-24 bg-gray-900 text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-gray-900 via-teal-900 to-gray-900 opacity-90"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-teal-500/20 rounded-full blur-[120px]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="font-serif text-4xl md:text-5xl font-bold mb-4">Choose Your Wellness Plan</h2>
                <p class="text-gray-300">30-Day Money Back Guarantee. Cancel Anytime.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto items-center">
                
                <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-8 hover:bg-white/10 transition duration-300" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xl font-bold mb-2">Starter</h3>
                    <p class="text-gray-400 text-sm mb-6">Perfect for trying it out.</p>
                    <div class="text-4xl font-bold mb-6">$49<span class="text-lg text-gray-400 font-normal">/mo</span></div>
                    <ul class="space-y-3 mb-8 text-sm text-gray-300">
                        <li class="flex items-center"><i class="fas fa-check text-teal-400 mr-2"></i> 1 Bottle (30 Caps)</li>
                        <li class="flex items-center"><i class="fas fa-check text-teal-400 mr-2"></i> Standard Shipping</li>
                    </ul>
                    <a href="index2.php" class="block w-full py-3 rounded-xl border border-teal-500 text-teal-400 font-bold text-center hover:bg-teal-500 hover:text-white transition">Buy Now</a>
                </div>

                <div class="bg-gradient-to-b from-teal-600 to-teal-800 rounded-3xl p-8 transform scale-105 shadow-2xl relative" data-aos="fade-up">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-yellow-400 text-yellow-900 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-lg">Most Popular</div>
                    <h3 class="text-xl font-bold mb-2">Transformation</h3>
                    <p class="text-teal-100 text-sm mb-6">Full body reset protocol.</p>
                    <div class="text-5xl font-bold mb-6">$129<span class="text-lg text-teal-200 font-normal">/qt</span></div>
                    <p class="text-xs text-teal-100 mb-6 strike-through">Normally $147</p>
                    <ul class="space-y-3 mb-8 text-sm font-medium">
                        <li class="flex items-center"><i class="fas fa-check-circle text-white mr-2"></i> 3 Bottles (90 Caps)</li>
                        <li class="flex items-center"><i class="fas fa-check-circle text-white mr-2"></i> Free Express Shipping</li>
                        <li class="flex items-center"><i class="fas fa-check-circle text-white mr-2"></i> Free E-Book Guide</li>
                    </ul>
                    <a href="index2.php" class="block w-full py-4 rounded-xl bg-white text-teal-800 font-bold text-center hover:bg-gray-100 transition shadow-lg">Get Best Value</a>
                </div>

                <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-8 hover:bg-white/10 transition duration-300" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-xl font-bold mb-2">Family</h3>
                    <p class="text-gray-400 text-sm mb-6">Share the health.</p>
                    <div class="text-4xl font-bold mb-6">$199<span class="text-lg text-gray-400 font-normal">/mo</span></div>
                    <ul class="space-y-3 mb-8 text-sm text-gray-300">
                        <li class="flex items-center"><i class="fas fa-check text-teal-400 mr-2"></i> 6 Bottles</li>
                        <li class="flex items-center"><i class="fas fa-check text-teal-400 mr-2"></i> Priority Support</li>
                    </ul>
                    <a href="index2.php" class="block w-full py-3 rounded-xl border border-teal-500 text-teal-400 font-bold text-center hover:bg-teal-500 hover:text-white transition">Buy Now</a>
                </div>

            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Frequently Asked Questions</h2>
            
            <div class="space-y-4">
                <details class="group bg-gray-50 p-6 rounded-xl cursor-pointer">
                    <summary class="flex justify-between items-center font-medium text-gray-900 list-none">
                        <span>Is eBiotheraphy safe for daily use?</span>
                        <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">Yes! Our formula is 100% organic, non-GMO, and free from synthetic fillers. We recommend taking one capsule with your morning meal.</p>
                </details>

                <details class="group bg-gray-50 p-6 rounded-xl cursor-pointer">
                    <summary class="flex justify-between items-center font-medium text-gray-900 list-none">
                        <span>How long until I see results?</span>
                        <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">Most users report increased energy within the first 3 days. Full cognitive and immune benefits typically peak after 30 days of consistent use.</p>
                </details>

                <details class="group bg-gray-50 p-6 rounded-xl cursor-pointer">
                    <summary class="flex justify-between items-center font-medium text-gray-900 list-none">
                        <span>Do you ship internationally?</span>
                        <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">We currently ship to over 50 countries worldwide. Shipping times vary by location but usually take 5-10 business days.</p>
                </details>
            </div>
        </div>
    </section>

    <footer class="bg-gray-50 border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-4 flex flex-col items-center justify-center text-center">
            <div class="flex items-center gap-2 mb-6 text-gray-400">
                <i class="fas fa-leaf text-2xl"></i>
                <span class="font-bold text-xl">eBiotheraphy</span>
            </div>
            <p class="text-gray-500 text-sm max-w-md mb-8">
                These statements have not been evaluated by the Food and Drug Administration. This product is not intended to diagnose, treat, cure, or prevent any disease.
            </p>
            <div class="flex gap-6 mb-8">
                <a href="#" class="text-gray-400 hover:text-teal-600"><i class="fab fa-instagram text-xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-teal-600"><i class="fab fa-facebook text-xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-teal-600"><i class="fab fa-twitter text-xl"></i></a>
            </div>
            <p class="text-gray-400 text-xs">&copy; <?php echo date('Y'); ?> eBiotheraphy LLC. All rights reserved. <a href="index2.php" class="underline hover:text-teal-600">Member Login</a></p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init Animation Library
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-md');
                nav.classList.add('bg-white/95');
                nav.classList.remove('bg-white/85');
            } else {
                nav.classList.remove('shadow-md');
                nav.classList.remove('bg-white/95');
                nav.classList.add('bg-white/85');
            }
        });

        // FALLING LEAVES SCRIPT
        function createLeaf() {
            const container = document.getElementById('leaves-container');
            const leaf = document.createElement('div');
            
            // Randomly choose a leaf icon or shape
            leaf.innerHTML = '<i class="fas fa-leaf" style="color: rgba(34, 197, 94, 0.4);"></i>';
            leaf.classList.add('leaf');
            
            // Randomize position and animation properties
            leaf.style.left = Math.random() * 100 + 'vw';
            leaf.style.animationDuration = Math.random() * 5 + 5 + 's'; // between 5 and 10 seconds
            leaf.style.fontSize = Math.random() * 10 + 10 + 'px'; // Size between 10px and 20px
            
            container.appendChild(leaf);

            // Remove leaf after animation ends to prevent memory leaks
            setTimeout(() => {
                leaf.remove();
            }, 10000);
        }

        // Create a new leaf every 500ms
        setInterval(createLeaf, 500);
    </script>
</body>
</html>