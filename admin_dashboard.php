<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eBiotheraphy | Admin Portal</title>
    <link rel="icon" href="../Assets/icon.png" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        /* Typography from Hero.php */
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }

        :root {
            --bg-light: #f9fafb;       
            --sidebar-bg: #ffffff;    
            --accent-teal: #0d9488;   
            --text-main: #1f2937;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            overflow: hidden; 
        }

        /* Gradient Text */
        .text-gradient {
            background: linear-gradient(135deg, #16a34a 0%, #0d9488 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Sidebar Styling */
        #sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(0,0,0,0.05);
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        }

        /* Nav Item Styling */
        .nav-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .nav-item:hover {
            background-color: #f0fdfa; /* Teal-50 */
            color: #0f766e; /* Teal-700 */
        }

        .nav-item.active {
            background: linear-gradient(to right, #f0fdfa, white);
            color: #0d9488; /* Teal-600 */
            border-right: 3px solid #0d9488;
            font-weight: 600;
        }
        
        .nav-item.active i {
            color: #0d9488;
        }

        /* Iframe Container */
        #content-frame-container {
            background: white;
            border-radius: 20px 0 0 0;
            box-shadow: -5px -5px 30px rgba(0,0,0,0.03);
            border-top: 1px solid rgba(0,0,0,0.05);
            border-left: 1px solid rgba(0,0,0,0.05);
        }

        /* Top Header */
        .top-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
        }

        @media (max-width: 768px) {
            #sidebar { position: fixed; left: -100%; height: 100%; width: 260px; z-index: 50; }
            #sidebar.active { left: 0; }
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-gray-50">

    <aside id="sidebar" class="h-screen w-72 min-w-[18rem] flex flex-col transition-all duration-300 relative z-50">
        
        <div class="h-24 flex items-center justify-center border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="bg-gradient-to-br from-green-100 to-teal-100 w-10 h-10 rounded-xl flex items-center justify-center text-teal-600 shadow-sm">
                    <i class="fas fa-leaf text-lg"></i>
                </div>
                <div>
                    <span class="font-serif font-bold text-xl tracking-tight text-gray-900">eBiotheraphy</span>
                    <p class="text-[10px] uppercase tracking-widest text-teal-600 font-bold">Admin Portal</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 py-8 px-4 space-y-2 overflow-y-auto">
            
            <p class="text-xs font-bold text-gray-400 uppercase px-4 mb-4 tracking-widest">Management</p>

            <a href="Admin/tree.php" target="content-frame" class="nav-item flex items-center gap-4 px-4 py-4 rounded-xl text-gray-500 group">
                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center group-hover:bg-white transition-colors shadow-sm">
                    <i class="fas fa-network-wired text-sm group-hover:scale-110 transition-transform"></i>
                </div>
                <span class="font-medium tracking-wide nav-text">Network Tree</span>
            </a>

            <a href="Admin/approve_members.php" target="content-frame" class="nav-item flex items-center gap-4 px-4 py-4 rounded-xl text-gray-500 group">
                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center group-hover:bg-white transition-colors shadow-sm">
                    <i class="fas fa-user-check text-sm group-hover:scale-110 transition-transform"></i>
                </div>
                <span class="font-medium tracking-wide nav-text">Approvals</span>
            </a>
            <a href="Admin/all_members.php" target="content-frame" class="nav-item flex items-center gap-4 px-4 py-4 rounded-xl text-gray-500 group">
                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center group-hover:bg-white transition-colors shadow-sm">
                    <i class="fas fa-user-check text-sm group-hover:scale-110 transition-transform"></i>
                </div>
                <span class="font-medium tracking-wide nav-text">Approvals</span>
            </a>

            <a href="Admin/bank_settings.php" target="content-frame" class="nav-item flex items-center gap-4 px-4 py-4 rounded-xl text-gray-500 group">
                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center group-hover:bg-white transition-colors shadow-sm">
                    <i class="fas fa-university text-sm group-hover:scale-110 transition-transform"></i>
                </div>
                <span class="font-medium tracking-wide nav-text">Bank Settings</span>
            </a>

        </nav>

        <div class="p-6 border-t border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-3 mb-4 px-2">
                <img src="https://ui-avatars.com/api/?name=Admin&background=0d9488&color=fff" class="w-10 h-10 rounded-full shadow-md border-2 border-white">
                <div>
                    <p class="text-sm font-bold text-gray-900">
                        <?php echo isset($_SESSION['admin']) ? htmlspecialchars($_SESSION['admin']) : 'Administrator'; ?>
                    </p>
                    <p class="text-xs text-teal-600">Super Admin</p>
                </div>
            </div>
            <a href="Admin/logout.php" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 hover:border-red-200 transition-all text-sm font-bold">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen relative z-10">
        
        <header class="top-header h-20 px-8 flex items-center justify-between z-20">
            <div class="flex items-center gap-4">
                <button id="menu-toggle" class="w-10 h-10 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-all flex items-center justify-center md:hidden">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 id="page-title" class="font-serif text-2xl font-bold text-gray-800">Dashboard</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <a href="../index.php" target="_blank" class="hidden md:flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-teal-600 transition-colors">
                    <i class="fas fa-external-link-alt"></i> View Website
                </a>
                <div class="w-px h-8 bg-gray-200 hidden md:block"></div>
                <button class="relative p-2 text-gray-400 hover:text-teal-600 transition">
                    <i class="far fa-bell text-xl"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                </button>
            </div>
        </header>

        <div id="content-frame-container" class="flex-1 relative m-4 mt-0 overflow-hidden">
            <div id="loader" class="absolute inset-0 flex items-center justify-center bg-white z-0">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-100 border-t-teal-600"></div>
            </div>
            <iframe id="content-frame" name="content-frame" src="Admin/tree.php" class="w-full h-full border-0 relative z-10 opacity-0 transition-opacity duration-500"></iframe>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menu-toggle');
        const links = document.querySelectorAll('.nav-item');
        const iframe = document.getElementById('content-frame');
        const pageTitle = document.getElementById('page-title');
        const loader = document.getElementById('loader');

        // Mobile Menu
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Link Interactions
        links.forEach(link => {
            link.addEventListener('click', function() {
                // Remove active class from all
                links.forEach(l => l.classList.remove('active'));
                
                // Add active to current
                this.classList.add('active');
                
                // Update Title
                const text = this.querySelector('.nav-text').innerText;
                pageTitle.innerText = text;
                
                // Show Loader
                iframe.style.opacity = '0';
                loader.style.display = 'flex';
                
                // Close mobile menu
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                }
            });
        });

        // Iframe Load
        iframe.addEventListener('load', () => {
            iframe.style.opacity = '1';
            setTimeout(() => { loader.style.display = 'none'; }, 300);
        });
        
        // Set first item active by default
        links[0].classList.add('active');
    </script>
</body>
</html>