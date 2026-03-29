<?= $this->extend('layouts/owner') ?>

<?= $this->section('content') ?>
<div class="p-6 animate-fadeIn">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-6 h-6 text-amber-500"></i> Daftar Reservasi
            </h2>
            <p class="text-gray-500 mt-1">Pantau jadwal pemesanan meja berdasarkan tanggal.</p>
        </div>
        
        <form method="GET" action="<?= base_url('owner/reservations') ?>" class="bg-white p-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
            <label for="date" class="text-sm font-bold text-gray-600 pl-2">Pilih Tanggal:</label>
            <input type="date" name="date" id="date" value="<?= $selectedDate ?>" onchange="this.form.submit()"
                class="px-4 py-2 bg-slate-50 border border-gray-200 rounded-lg focus:ring-amber-500 focus:border-amber-500 outline-none text-gray-700 font-medium cursor-pointer">
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border-l-4 border-blue-500 shadow-sm flex items-center justify-between">
            <div><p class="text-xs text-gray-500 font-bold uppercase">Total</p><p class="text-2xl font-bold text-gray-800"><?= $total ?></p></div>
            <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center"><i data-lucide="users" class="w-5 h-5"></i></div>
        </div>
        <div class="bg-white rounded-xl p-4 border-l-4 border-green-500 shadow-sm flex items-center justify-between">
            <div><p class="text-xs text-gray-500 font-bold uppercase">Confirmed</p><p class="text-2xl font-bold text-gray-800"><?= $confirmed ?></p></div>
            <div class="w-10 h-10 bg-green-50 text-green-500 rounded-full flex items-center justify-center"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
        </div>
        <div class="bg-white rounded-xl p-4 border-l-4 border-yellow-500 shadow-sm flex items-center justify-between">
            <div><p class="text-xs text-gray-500 font-bold uppercase">Pending</p><p class="text-2xl font-bold text-gray-800"><?= $pending ?></p></div>
            <div class="w-10 h-10 bg-yellow-50 text-yellow-500 rounded-full flex items-center justify-center"><i data-lucide="clock" class="w-5 h-5"></i></div>
        </div>
        <div class="bg-white rounded-xl p-4 border-l-4 border-red-500 shadow-sm flex items-center justify-between">
            <div><p class="text-xs text-gray-500 font-bold uppercase">Batal</p><p class="text-2xl font-bold text-gray-800"><?= $cancelled ?></p></div>
            <div class="w-10 h-10 bg-red-50 text-red-500 rounded-full flex items-center justify-center"><i data-lucide="x-circle" class="w-5 h-5"></i></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                        <th class="p-4 font-bold">Waktu</th>
                        <th class="p-4 font-bold">Pelanggan</th>
                        <th class="p-4 font-bold">Kontak</th>
                        <th class="p-4 font-bold text-center">Tamu (Pax)</th>
                        <th class="p-4 font-bold">Status</th>
                        <th class="p-4 font-bold">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php if (empty($reservations)): ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">
                            <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 opacity-30 text-slate-400"></i>
                            <p class="text-base font-medium">Tidak ada data reservasi</p>
                            <p class="text-xs mt-1">Belum ada pemesanan meja untuk tanggal ini.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($reservations as $res): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 font-mono font-bold text-amber-600">
                                <?= date('H:i', strtotime($res['reservation_time'])) ?> WIB
                            </td>
                            <td class="p-4 font-bold text-gray-800 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs">
                                    <?= substr($res['customer_name'], 0, 1) ?>
                                </div>
                                <?= esc($res['customer_name']) ?>
                            </td>
                            <td class="p-4 text-gray-600"><?= esc($res['customer_phone']) ?: '-' ?></td>
                            <td class="p-4 text-center font-bold"><?= $res['guest_count'] ?></td>
                            <td class="p-4">
                                <?php 
                                    $statusBadges = [
                                        'pending'   => '<span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-md text-[10px] font-bold uppercase tracking-wider">Pending</span>',
                                        'confirmed' => '<span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-md text-[10px] font-bold uppercase tracking-wider">Confirmed</span>',
                                        'completed' => '<span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-md text-[10px] font-bold uppercase tracking-wider">Selesai</span>',
                                        'cancelled' => '<span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-md text-[10px] font-bold uppercase tracking-wider">Batal</span>'
                                    ];
                                    echo $statusBadges[$res['status']] ?? $res['status'];
                                ?>
                            </td>
                            <td class="p-4 text-gray-500 text-xs italic max-w-xs truncate" title="<?= esc($res['notes']) ?>">
                                <?= esc($res['notes']) ?: '<span class="text-gray-300">Tidak ada</span>' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?= $this->endSection() ?>