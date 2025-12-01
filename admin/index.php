<?php
session_start();
// Security Check - Uncomment in production
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'master') {
    // header("Location: ../index.php"); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Console | eBiotheraphy</title>
    <link rel="icon" href="../Assets/icon.png" type="image/png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Sidebar Gradient */
        .sidebar-gradient {
            background: linear-gradient(150deg, #17A24E 0%, #1A8E7D 100%);
        }

        /* Nav Item Styling */
        .nav-item {
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 16px;
            margin-bottom: 4px;
        }

        /* Hover Effect */
        .nav-item:hover:not(.active) {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        /* Active State - Floating White Pill */
        .nav-item.active {
            background: white;
            color: #17A24E; /* Text becomes primary green */
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            font-weight: 700;
        }
        
        .nav-item.active i {
            color: #1A8E7D; /* Icon becomes teal */
        }

        /* Text Gradient for Header */
        .text-gradient {
            background: linear-gradient(to right, #17A24E, #1A8E7D);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Iframe Transition */
        #content-frame {
            transition: opacity 0.4s ease-in-out;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-slate-50 font-sans text-slate-600">

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-80 sidebar-gradient text-white flex flex-col transition-transform duration-300 transform -translate-x-full md:relative md:translate-x-0 shadow-2xl">
        
        <!-- Brand -->
        <div class="h-28 flex items-center px-10 border-b border-white/10">
            <div class="flex items-center gap-4 group cursor-pointer">
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30 shadow-lg group-hover:scale-105 transition duration-300">
                    <i class="fas fa-leaf text-xl"></i>
                </div>
                <div>
                    <span class="font-serif font-bold text-2xl text-white tracking-wide block leading-none drop-shadow-sm">eBiotheraphy</span>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-white/80 font-bold mt-1.5 ml-0.5">Admin Console</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 py-10 px-6 space-y-2 overflow-y-auto no-scrollbar">
            
            <!-- Section Label -->
            <div class="px-6 mb-4 mt-2">
                <p class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">Network</p>
            </div>

            <a href="tree.php" target="content-frame" class="nav-item flex items-center gap-5 px-6 py-4 text-sm font-medium">
                <i class="fas fa-sitemap w-5 text-lg transition-transform group-hover:scale-110"></i>
                <span class="nav-text tracking-wide text-[15px]">Genealogy Tree</span>
            </a>

            <a href="all_members.php" target="content-frame" class="nav-item flex items-center gap-5 px-6 py-4 text-sm font-medium">
                <i class="fas fa-users w-5 text-lg transition-transform group-hover:scale-110"></i>
                <span class="nav-text tracking-wide text-[15px]">Member Registry</span>
            </a>

            <!-- Section Label -->
            <div class="px-6 mb-4 mt-8">
                <p class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">Management</p>
            </div>

            <a href="approve_members.php" target="content-frame" class="nav-item flex items-center gap-5 px-6 py-4 text-sm font-medium">
                <i class="fas fa-user-check w-5 text-lg transition-transform group-hover:scale-110"></i>
                <span class="nav-text tracking-wide text-[15px]">Approvals</span>
                <span class="ml-auto bg-white/20 backdrop-blur text-white text-[9px] font-bold px-2 py-1 rounded-full border border-white/20">NEW</span>
            </a>

            <!-- Section Label -->
            <div class="px-6 mb-4 mt-8">
                <p class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em]">System</p>
            </div>

            <a href="bank_settings.php" target="content-frame" class="nav-item flex items-center gap-5 px-6 py-4 text-sm font-medium">
                <i class="fas fa-landmark w-5 text-lg transition-transform group-hover:scale-110"></i>
                <span class="nav-text tracking-wide text-[15px]">Bank Settings</span>
            </a>

            <a href="settings.php" target="content-frame" class="nav-item flex items-center gap-5 px-6 py-4 text-sm font-medium">
                <i class="fas fa-cogs w-5 text-lg transition-transform group-hover:scale-110"></i>
                <span class="nav-text tracking-wide text-[15px]">Financial Logic</span>
            </a>

        </nav>

        <!-- Profile/Logout -->
        <div class="p-8 border-t border-white/10 bg-black/10 backdrop-blur-sm">
            <a href="logout.php" class="group flex items-center gap-4 px-5 py-4 rounded-2xl bg-white/10 hover:bg-white hover:text-[#17A24E] transition-all duration-300 border border-white/10 hover:border-white shadow-lg">
                <div class="w-10 h-10 rounded-full bg-white text-[#17A24E] flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-[#17A24E] group-hover:text-white transition-colors">
                    <i class="fas fa-power-off"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold uppercase tracking-wider group-hover:translate-x-1 transition-transform">Sign Out</p>
                    <p class="text-[10px] opacity-70 group-hover:translate-x-1 transition-transform delay-75">End Session</p>
                </div>
            </a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen relative bg-slate-50">
        
        <!-- Header -->
        <header class="h-28 flex items-center justify-between px-10 z-20 bg-slate-50">
            
            <div class="flex items-center gap-6">
                <!-- Mobile Toggle -->
                <button id="menu-toggle" class="md:hidden w-12 h-12 flex items-center justify-center rounded-2xl bg-white text-[#17A24E] shadow-lg shadow-slate-200 hover:scale-105 transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <div>
                    <h2 id="page-title" class="font-serif text-4xl font-bold text-slate-800 tracking-tight">Dashboard</h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="w-2 h-2 rounded-full bg-[#17A24E] animate-pulse"></span>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider" id="date-display">System Active</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-8">
                <a href="../index.php" target="_blank" class="hidden md:flex items-center gap-3 px-6 py-3 rounded-full bg-white border border-slate-100 shadow-md shadow-slate-100 hover:shadow-xl hover:scale-105 transition-all group">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-gradient">Visit Website</span> 
                    <i class="fas fa-external-link-alt text-xs text-slate-400 group-hover:text-[#1A8E7D]"></i>
                </a>
                
                <div class="flex items-center gap-5">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800">
                            <?php echo isset($_SESSION['admin']) ? htmlspecialchars($_SESSION['admin']) : 'Master Admin'; ?>
                        </p>
                        <p class="text-[10px] font-bold text-[#17A24E] uppercase tracking-widest">Super User</p>
                    </div>
                    <!-- Avatar with Gradient Ring -->
                    <div class="p-1 rounded-full bg-gradient-to-tr from-[#17A24E] to-[#1A8E7D] shadow-xl">
                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#17A24E]">
                            <i class="fas fa-user-shield text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area (The "Sheet" Effect) -->
        <div class="flex-1 relative overflow-hidden pl-6 pb-6 pr-6">
            <div class="w-full h-full bg-white rounded-[2.5rem] relative overflow-hidden shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] border border-slate-100">
                
                <!-- Loader -->
                <div id="loader" class="absolute inset-0 flex items-center justify-center bg-white/80 backdrop-blur-sm z-20">
                    <div class="flex flex-col items-center gap-6">
                        <div class="relative">
                            <div class="w-20 h-20 border-4 border-slate-100 border-t-[#17A24E] border-b-[#1A8E7D] rounded-full animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-leaf text-[#17A24E] text-2xl animate-pulse"></i>
                            </div>
                        </div>
                        <p class="text-xs font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#17A24E] to-[#1A8E7D] uppercase tracking-[0.3em] animate-pulse">Loading View</p>
                    </div>
                </div>
                
                <!-- Iframe -->
                <iframe id="content-frame" name="content-frame" src="tree.php" class="w-full h-full border-0 relative z-10 opacity-0 rounded-[2.5rem]"></iframe>
            </div>
        </div>
    </div>

    <!-- Overlay for Mobile Sidebar -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300"></div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menu-toggle');
        const overlay = document.getElementById('sidebar-overlay');
        const links = document.querySelectorAll('.nav-item');
        const iframe = document.getElementById('content-frame');
        const pageTitle = document.getElementById('page-title');
        const loader = document.getElementById('loader');

        // Date Display
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('date-display').innerText = new Date().toLocaleDateString('en-US', dateOptions);

        // Mobile Menu Logic
        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        menuToggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Navigation Interaction
        links.forEach(link => {
            link.addEventListener('click', function() {
                // Active State Styling
                links.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Update Header Title with Animation
                const text = this.querySelector('.nav-text').innerText;
                pageTitle.style.opacity = '0';
                pageTitle.style.transform = 'translateY(-10px)';
                
                setTimeout(() => {
                    pageTitle.innerText = text;
                    pageTitle.style.opacity = '1';
                    pageTitle.style.transform = 'translateY(0)';
                }, 300);
                
                // Show Loader & Hide Iframe
                iframe.style.opacity = '0';
                loader.style.display = 'flex';
                
                // Close mobile sidebar if open
                if(window.innerWidth < 768 && !sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
            });
        });

        // Iframe Load Handler
        iframe.addEventListener('load', () => {
            setTimeout(() => {
                iframe.style.opacity = '1';
                loader.style.display = 'none'; 
            }, 500); // Artificial delay for premium feel
        });
        
        // Initialize First Item
        if(links.length > 0) links[0].classList.add('active');
        
        // Title Fade Transition
        pageTitle.style.transition = 'all 0.4s ease-out';
    </script>
</body>
</html>