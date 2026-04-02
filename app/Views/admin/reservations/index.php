<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="p-6 animate-fadeIn">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Reservasi</h2>
        <p class="text-gray-500">Catat dan kelola pemesanan tempat oleh pelanggan.</p>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
            <p><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm h-fit">
            <h3 id="form-title" class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="calendar-plus" class="w-5 h-5 text-blue-500"></i>
                Catat Reservasi Baru
            </h3>
            
            <form id="res-form" action="<?= base_url('admin/reservations/store') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan</label>
                    <input type="text" id="res-name" name="customer_name" required placeholder="Contoh: Bapak Andi" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. WhatsApp/HP</label>
                    <input type="text" id="res-phone" name="customer_phone" placeholder="08123456789" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" id="res-date" name="reservation_date" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam</label>
                        <input type="time" id="res-time" name="reservation_time" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Orang (Pax)</label>
                    <input type="number" id="res-guest" name="guest_count" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea id="res-notes" name="notes" rows="2" placeholder="Contoh: Meja pojok" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="flex gap-2">
                    <button type="submit" id="btn-submit" class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                        Simpan Data
                    </button>
                    <button type="button" id="btn-cancel" onclick="resetForm()" class="hidden py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm overflow-hidden h-fit">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i data-lucide="book-open" class="w-5 h-5 text-purple-500"></i>
                    Daftar Reservasi
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-sm">
                            <th class="p-4 font-medium border-b">Pelanggan</th>
                            <th class="p-4 font-medium border-b">Waktu & Tamu</th>
                            <th class="p-4 font-medium border-b text-center">Status</th>
                            <th class="p-4 font-medium border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        <?php if(empty($reservations)): ?>
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-400">Belum ada data reservasi.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($reservations as $res): ?>
                            <tr class="hover:bg-gray-50 border-b last:border-0 transition-colors">
                                <td class="p-4">
                                    <p class="font-medium text-gray-800"><?= esc($res['customer_name']) ?></p>
                                    <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                        <i data-lucide="phone" class="w-3 h-3"></i> <?= esc($res['customer_phone'] ?: '-') ?>
                                    </p>
                                    <?php if(!empty($res['notes'])): ?>
                                        <p class="text-xs text-amber-600 mt-1 italic">"<?= esc($res['notes']) ?>"</p>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i data-lucide="calendar" class="w-4 h-4 text-blue-500"></i>
                                        <span class="font-medium"><?= date('d M Y', strtotime($res['reservation_date'])) ?></span>
                                    </div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <i data-lucide="clock" class="w-4 h-4 text-amber-500"></i>
                                        <span><?= date('H:i', strtotime($res['reservation_time'])) ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="users" class="w-4 h-4 text-purple-500"></i>
                                        <span><?= $res['guest_count'] ?> Pax</span>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <form action="<?= base_url('admin/reservations/update_status/' . $res['id']) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <select name="status" onchange="this.form.submit()" 
                                            class="text-xs font-semibold rounded-full px-3 py-1 border-0 ring-1 ring-inset cursor-pointer focus:ring-2 focus:ring-blue-500 outline-none
                                            <?php 
                                                if($res['status'] == 'pending') echo 'bg-yellow-50 text-yellow-700 ring-yellow-600/20';
                                                elseif($res['status'] == 'confirmed') echo 'bg-blue-50 text-blue-700 ring-blue-600/20';
                                                elseif($res['status'] == 'completed') echo 'bg-green-50 text-green-700 ring-green-600/20';
                                                else echo 'bg-red-50 text-red-700 ring-red-600/20';
                                            ?>">
                                            <option value="pending" <?= $res['status'] == 'pending' ? 'selected' : '' ?>>Menunggu</option>
                                            <option value="confirmed" <?= $res['status'] == 'confirmed' ? 'selected' : '' ?>>Dikonfirmasi</option>
                                            <option value="completed" <?= $res['status'] == 'completed' ? 'selected' : '' ?>>Selesai</option>
                                            <option value="cancelled" <?= $res['status'] == 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" 
                                            onclick="editRes('<?= $res['id'] ?>', '<?= addslashes($res['customer_name']) ?>', '<?= addslashes($res['customer_phone']) ?>', '<?= $res['reservation_date'] ?>', '<?= $res['reservation_time'] ?>', '<?= $res['guest_count'] ?>', '<?= addslashes($res['notes']) ?>')" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f77ec" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen w-4 h-4"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                                        </button>
                                        <a href="<?= base_url('admin/reservations/delete/' . $res['id']) ?>" 
                                           onclick="return confirm('Hapus data ini?')"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function editRes(id, name, phone, date, time, guest, notes) {
        const form = document.getElementById('res-form');
        const title = document.getElementById('form-title');
        
        form.action = "<?= base_url('admin/reservations/update/') ?>" + id;
        
        document.getElementById('res-name').value = name;
        document.getElementById('res-phone').value = phone;
        document.getElementById('res-date').value = date;
        document.getElementById('res-time').value = time.substring(0, 5); 
        document.getElementById('res-guest').value = guest;
        document.getElementById('res-notes').value = notes;
        
        title.innerHTML = '<i data-lucide="edit-3" class="w-5 h-5 text-blue-500"></i> Edit Reservasi';
        document.getElementById('btn-submit').innerText = 'Simpan Perubahan';
        document.getElementById('btn-cancel').classList.remove('hidden');
        
        lucide.createIcons();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        const form = document.getElementById('res-form');
        const title = document.getElementById('form-title');
        
        form.action = "<?= base_url('admin/reservations/store') ?>";
        form.reset();
        
        title.innerHTML = '<i data-lucide="calendar-plus" class="w-5 h-5 text-blue-500"></i> Catat Reservasi Baru';
        document.getElementById('btn-submit').innerText = 'Simpan Data';
        document.getElementById('btn-cancel').classList.add('hidden');
        
        lucide.createIcons();
    }
</script>
<?= $this->endSection() ?>