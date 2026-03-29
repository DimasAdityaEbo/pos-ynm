<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="p-6 animate-fadeIn">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Laporan Penjualan</h2>
            <p class="text-gray-500">Pantau pendapatan dan detail transaksi kafe Anda.</p>
        </div>
        
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-medium transition-colors shadow-sm">
            <i data-lucide="printer" class="w-4 h-4"></i> Cetak Laporan
        </button>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6 print:hidden">
        <form action="<?= base_url('admin/reports') ?>" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="date" name="start_date" value="<?= $startDate ?>" required 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-purple-500 focus:border-purple-500 text-sm">
                </div>
            </div>
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="date" name="end_date" value="<?= $endDate ?>" required 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-purple-500 focus:border-purple-500 text-sm">
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-xl transition-colors flex items-center justify-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i> Terapkan Filter
            </button>
            <a href="<?= base_url('admin/reports') ?>" class="w-full md:w-auto px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors text-center">
                Reset
            </a>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl p-5 shadow-sm text-white">
            <div class="flex items-center justify-between mb-4">
                <p class="text-white/80 text-sm font-medium">Total Pendapatan</p>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i data-lucide="banknote" class="w-5 h-5"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
            <p class="text-xs text-white/70 mt-1">Periode terpilih</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-500 text-sm font-medium">Total Transaksi</p>
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="receipt" class="w-5 h-5"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-800"><?= $totalTrx ?> <span class="text-sm font-normal text-gray-500">struk</span></h3>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-500 text-sm font-medium">Metode Pembayaran</p>
                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="space-y-1 text-sm font-medium text-gray-700">
                <div class="flex justify-between"><span>Cash:</span> <span><?= $paymentStats['cash'] ?></span></div>
                <div class="flex justify-between"><span>QRIS:</span> <span><?= $paymentStats['qris'] ?></span></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-500 text-sm font-medium">Kartu Kredit/Debit</p>
                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-800"><?= $paymentStats['card'] ?> <span class="text-sm font-normal text-gray-500">trx</span></h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Detail Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm">
                        <th class="p-4 font-medium border-b">ID Transaksi</th>
                        <th class="p-4 font-medium border-b">Tanggal & Waktu</th>
                        <th class="p-4 font-medium border-b">Kasir</th>
                        <th class="p-4 font-medium border-b">Metode Pembayaran</th>
                        <th class="p-4 font-medium border-b text-right">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    <?php if(empty($transactions)): ?>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400">
                                <i data-lucide="file-x" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                Tidak ada transaksi pada periode ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($transactions as $trx): ?>
                        <tr class="hover:bg-gray-50 border-b last:border-0 transition-colors">
                            <td class="p-4">
                                <span class="font-mono font-medium text-purple-600"><?= $trx['transaction_id'] ?></span>
                            </td>
                            <td class="p-4 text-gray-600">
                                <?= date('d M Y, H:i', strtotime($trx['created_at'])) ?> WIB
                            </td>
                            <td class="p-4 font-medium text-gray-800">
                                <?= $trx['cashier_name'] ?? 'Sistem' ?>
                            </td>
                            <td class="p-4">
                                <?php 
                                    $methodColors = [
                                        'cash' => 'bg-green-50 text-green-600 border-green-100',
                                        'qris' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'card' => 'bg-amber-50 text-amber-600 border-amber-100'
                                    ];
                                    $colorClass = $methodColors[strtolower($trx['payment_method'])] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                ?>
                                <span class="px-3 py-1 border rounded-full text-xs font-semibold uppercase <?= $colorClass ?>">
                                    <?= $trx['payment_method'] ?>
                                </span>
                            </td>
                            <td class="p-4 text-right font-bold text-gray-800">
                                <?= number_format($trx['total'], 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background-color: white !important; }
        .print\:hidden { display: none !important; }
        .gradient-bg, .sidebar-item { display: none !important; } 
    }
</style>
<?= $this->endSection() ?>