<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="p-6 animate-fadeIn">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Diskon & Promo</h2>
        <p class="text-gray-500">Buat dan kelola kode promo untuk menarik lebih banyak pelanggan.</p>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
            <p><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm">
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm h-fit">
            <h3 id="form-title" class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="tag" class="w-5 h-5 text-rose-500"></i>
                Buat Promo Baru
            </h3>
            
            <form id="discount-form" action="<?= base_url('admin/discounts/store') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Promo</label>
                    <input type="text" id="disc-name" name="name" required placeholder="Contoh: Diskon Kemerdekaan" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-rose-500 focus:border-rose-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Promo</label>
                    <input type="text" id="disc-code" name="code" required placeholder="Contoh: MERDEKA17" style="text-transform: uppercase;"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-rose-500 focus:border-rose-500 font-mono">
                    <p class="text-xs text-gray-400 mt-1">Kode ini akan diketik oleh kasir saat transaksi.</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Diskon</label>
                        <select id="disc-type" name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-rose-500 focus:border-rose-500">
                            <option value="percentage">Persentase (%)</option>
                            <option value="nominal">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Diskon</label>
                        <input type="number" id="disc-value" name="value" required min="1" placeholder="Misal: 10" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-rose-500 focus:border-rose-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimal Belanja (Rp)</label>
                    <input type="number" id="disc-min" name="min_purchase" placeholder="Kosongkan jika tidak ada" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-rose-500 focus:border-rose-500">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Berlaku Sampai Tanggal</label>
                    <input type="date" id="disc-valid" name="valid_until" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-rose-500 focus:border-rose-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit" id="btn-submit" class="flex-1 py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-xl transition-colors">
                        Simpan Promo
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
                    <i data-lucide="ticket" class="w-5 h-5 text-purple-500"></i>
                    Daftar Promo Diskon
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-sm">
                            <th class="p-4 font-medium border-b">Detail Promo</th>
                            <th class="p-4 font-medium border-b">Potongan</th>
                            <th class="p-4 font-medium border-b text-center">Status</th>
                            <th class="p-4 font-medium border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        <?php if(empty($discounts)): ?>
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-400">Belum ada data promo / diskon.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($discounts as $disc): 
                                $isExpired = strtotime($disc['valid_until']) < strtotime(date('Y-m-d'));
                            ?>
                            <tr class="hover:bg-gray-50 border-b last:border-0 transition-colors <?= $isExpired ? 'opacity-60 bg-gray-50' : '' ?>">
                                <td class="p-4">
                                    <p class="font-bold text-gray-800"><?= $disc['name'] ?></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded text-xs font-mono font-bold tracking-wider">
                                            <?= $disc['code'] ?>
                                        </span>
                                    </div>
                                    <?php if($disc['min_purchase'] > 0): ?>
                                        <p class="text-xs text-gray-500 mt-2">Min. Trx: Rp <?= number_format($disc['min_purchase'], 0, ',', '.') ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <?php if($disc['type'] == 'percentage'): ?>
                                        <span class="text-lg font-bold text-rose-600"><?= $disc['value'] ?>%</span>
                                    <?php else: ?>
                                        <span class="text-lg font-bold text-rose-600">Rp <?= number_format($disc['value'], 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-center">
                                    <?php if($isExpired): ?>
                                        <span class="px-3 py-1 bg-red-50 text-red-600 border border-red-100 rounded-full text-xs font-semibold">Expired</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-green-50 text-green-600 border border-green-100 rounded-full text-xs font-semibold">Aktif</span>
                                    <?php endif; ?>
                                    <p class="text-xs text-gray-400 mt-2">s.d <?= date('d M Y', strtotime($disc['valid_until'])) ?></p>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="editDisc(<?= $disc['id'] ?>, '<?= addslashes($disc['name']) ?>', '<?= $disc['code'] ?>', '<?= $disc['type'] ?>', <?= $disc['value'] ?>, <?= $disc['min_purchase'] ?>, '<?= $disc['valid_until'] ?>')" 
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit Data">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f77ec" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen w-4 h-4"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg> 
                                        </button>
                                        <a href="<?= base_url('admin/discounts/delete/' . $disc['id']) ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus promo diskon ini?')"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus">
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
    function editDisc(id, name, code, type, value, min_purchase, valid_until) {
        const form = document.getElementById('discount-form');
        const title = document.getElementById('form-title');
        
        form.action = "<?= base_url('admin/discounts/update/') ?>" + id;
        
        document.getElementById('disc-name').value = name;
        document.getElementById('disc-code').value = code;
        document.getElementById('disc-type').value = type;
        document.getElementById('disc-value').value = value;
        document.getElementById('disc-min').value = min_purchase;
        document.getElementById('disc-valid').value = valid_until;
        
        title.innerHTML = '<i data-lucide="edit" class="w-5 h-5 text-rose-500"></i> Edit Promo';
        document.getElementById('btn-submit').innerText = 'Simpan Perubahan';
        document.getElementById('btn-cancel').classList.remove('hidden');
        
        lucide.createIcons();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        const form = document.getElementById('discount-form');
        const title = document.getElementById('form-title');
        
        form.action = "<?= base_url('admin/discounts/store') ?>";
        form.reset();
        
        title.innerHTML = '<i data-lucide="tag" class="w-5 h-5 text-rose-500"></i> Buat Promo Baru';
        document.getElementById('btn-submit').innerText = 'Simpan Promo';
        document.getElementById('btn-cancel').classList.add('hidden');
        
        lucide.createIcons();
    }
</script>
<?= $this->endSection() ?>