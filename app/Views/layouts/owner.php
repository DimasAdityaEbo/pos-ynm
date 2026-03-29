<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You and Me Coffee - Admin</title>
    
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .sidebar-item { transition: all 0.2s ease; }
        .sidebar-item:hover { background: rgba(255,255,255,0.1); }
        .sidebar-item.active { background: rgba(255,255,255,0.2); border-left: 3px solid white; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadeIn { animation: fadeIn 0.3s ease forwards; }
    </style>
</head>
<body class="h-full bg-gray-50">
    <div class="h-full flex">
        
        <div class="w-64 gradient-bg text-white flex flex-col shrink-0">
            <div class="p-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="coffee" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg">You & Me Coffee</h1>
                        <p class="text-xs text-white/70">POS System</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 px-4 space-y-1">
                <a href="<?= base_url('owner/dashboard') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left <?= uri_string() == 'owner/dashboard' ? 'active' : '' ?>">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
                <a href="<?= base_url('owner/reservations') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left <?= uri_string() == 'owner/reservations' ? 'active' : '' ?>">
                    <i data-lucide="calendar-days" class="w-5 h-5"></i>
                    <span class="text-sm font-medium">Daftar Reservasi</span>
                </a>
                <a href="<?= base_url('owner/reports/sales') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left <?= uri_string() == 'owner/reports/sales' ? 'active' : '' ?>">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                    <span class="text-sm font-medium">Laporan Penjualan</span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="font-medium text-sm"><?= session()->get('name') ?? 'Admin' ?></p>
                        <p class="text-xs text-white/70 capitalize"><?= session()->get('role') ?? 'admin' ?></p>
                    </div>
                </div>
                <a href="<?= base_url('logout') ?>" class="w-full flex items-center justify-center gap-2 py-2 bg-white/10 rounded-xl hover:bg-white/20 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span class="text-sm">Keluar</span>
                </a>
            </div>
        </div>
        
        <div class="flex-1 overflow-auto bg-gray-50">
            <?= $this->renderSection('content') ?>
        </div>
        
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>