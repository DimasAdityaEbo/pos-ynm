<?= $this->extend('layouts/owner') ?> <?= $this->section('content') ?>
<div class="p-6 animate-fadeIn">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Dashboard Owner</h2>
            <p class="text-gray-500">Selamat datang kembali, <?= session()->get('name') ?? 'Owner' ?>! Berikut ringkasan performa kafe Anda.</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm card-hover border-b-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Penjualan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800">Rp <?= number_format($todayTotal, 0, ',', '.') ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm card-hover border-b-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800"><?= $todayTransactions ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="receipt" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm card-hover border-b-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pendapatan Minggu Ini</p>
                    <p class="text-2xl font-bold text-gray-800">Rp <?= number_format($weekTotal, 0, ',', '.') ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="calendar" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm card-hover border-b-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pendapatan Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-800">Rp <?= number_format($monthTotal, 0, ',', '.') ?></p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="wallet" class="w-6 h-6 text-amber-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="trophy" class="w-5 h-5 text-amber-500"></i>
                Top 5 Menu Terlaris
            </h3>
            <div class="space-y-3">
                <?php $totalSold = array_sum(array_column($topMenus, 'qty')); ?>
                <?php foreach($topMenus as $i => $menu): ?>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold <?= $i === 0 ? 'bg-amber-100 text-amber-600' : ($i === 1 ? 'bg-gray-100 text-gray-600' : ($i === 2 ? 'bg-orange-100 text-orange-600' : 'bg-gray-50 text-gray-500')) ?>">
                        <?= $i + 1 ?>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800"><?= $menu['name'] ?></p>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full" style="width: <?= $totalSold ? ($menu['qty'] / $totalSold * 100) : 0 ?>%"></div>
                            </div>
                            <span class="text-xs text-gray-500"><?= $menu['qty'] ?> terjual</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="calendar-check" class="w-5 h-5 text-blue-500"></i>
                Reservasi Hari Ini
            </h3>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-blue-600"><?= count($todayReservations) ?></p>
                    <p class="text-xs text-blue-600">Total</p>
                </div>
                <div class="bg-green-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-green-600"><?= $confirmedReservationsCount ?></p>
                    <p class="text-xs text-green-600">Dikonfirmasi / Selesai</p>
                </div>
            </div>
            
            <div class="space-y-2 max-h-40 overflow-auto scrollbar-hide">
                <?php if(empty($todayReservations)): ?>
                    <p class="text-sm text-gray-400 text-center py-4">Tidak ada reservasi hari ini.</p>
                <?php else: ?>
                    <?php foreach($todayReservations as $res): ?>
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex justify-between items-center transition hover:bg-gray-100">
                            <div>
                                <p class="text-sm font-bold text-gray-800"><?= $res['customer_name'] ?></p>
                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                    <i data-lucide="clock" class="w-3 h-3"></i> <?= date('H:i', strtotime($res['reservation_time'])) ?> WIB • <?= $res['guest_count'] ?> Pax
                                </p>
                            </div>
                            <?php 
                                $statusColors = [
                                    'pending'   => 'bg-yellow-100 text-yellow-700',
                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700'
                                ];
                                $color = $statusColors[$res['status']] ?? 'bg-gray-200 text-gray-700';
                            ?>
                            <span class="text-[10px] font-bold px-2 py-1 rounded uppercase <?= $color ?>">
                                <?= $res['status'] ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="credit-card" class="w-5 h-5 text-emerald-500"></i>
                Metode Pembayaran
            </h3>
            <div class="space-y-3 mt-4">
                <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl border border-emerald-100 transition hover:bg-emerald-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-emerald-600 shadow-sm">
                            <i data-lucide="banknote" class="w-5 h-5"></i>
                        </div>
                        <span class="text-sm font-bold text-emerald-800">Cash / Tunai</span>
                    </div>
                    <span class="text-xl font-bold text-emerald-600"><?= $cashCount ?> <span class="text-xs font-normal">trx</span></span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl border border-blue-100 transition hover:bg-blue-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-blue-600 shadow-sm">
                            <i data-lucide="qr-code" class="w-5 h-5"></i>
                        </div>
                        <span class="text-sm font-bold text-blue-800">QRIS</span>
                    </div>
                    <span class="text-xl font-bold text-blue-600"><?= $qrisCount ?> <span class="text-xs font-normal">trx</span></span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-amber-50 rounded-xl border border-amber-100 transition hover:bg-amber-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-amber-600 shadow-sm">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                        </div>
                        <span class="text-sm font-bold text-amber-800">Kartu (Debit/Kredit)</span>
                    </div>
                    <span class="text-xl font-bold text-amber-600"><?= $cardCount ?> <span class="text-xs font-normal">trx</span></span>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <h3 class="font-semibold text-gray-800 mb-4">Aset & Ringkasan Sistem</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                <i data-lucide="utensils" class="w-8 h-8 mx-auto mb-2 text-purple-600"></i>
                <p class="text-2xl font-bold text-gray-800"><?= $menusCount ?></p>
                <p class="text-xs text-gray-500">Total Menu</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl">
                <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 text-blue-600"></i>
                <p class="text-2xl font-bold text-gray-800"><?= $usersCount ?></p>
                <p class="text-xs text-gray-500">Total Pegawai</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl">
                <i data-lucide="calendar" class="w-8 h-8 mx-auto mb-2 text-green-600"></i>
                <p class="text-2xl font-bold text-gray-800"><?= $reservationsCount ?></p>
                <p class="text-xs text-gray-500">Total Reservasi</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl">
                <i data-lucide="receipt" class="w-8 h-8 mx-auto mb-2 text-amber-600"></i>
                <p class="text-2xl font-bold text-gray-800"><?= $transactionsCount ?></p>
                <p class="text-xs text-gray-500">Total Transaksi</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl">
                <i data-lucide="percent" class="w-8 h-8 mx-auto mb-2 text-rose-600"></i>
                <p class="text-2xl font-bold text-gray-800"><?= $discountsCount ?></p>
                <p class="text-xs text-gray-500">Diskon Aktif</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= $this->endSection() ?>