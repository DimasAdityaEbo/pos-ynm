<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="p-6 animate-fadeIn">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Menu</h2>
        <p class="text-gray-500">Kelola daftar produk, harga, dan varian menu kafe Anda.</p>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
            <p><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-2xl p-6 shadow-sm h-fit">
            <h3 id="form-title" class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-5 h-5 text-blue-500"></i>
                Tambah Menu Baru
            </h3>
            
            <form id="menu-form" action="<?= base_url('admin/menus/store') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                    <input type="text" id="menu-name" name="name" required placeholder="Contoh: Kopi Susu Aren" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="menu-category" name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" id="menu-price" name="price" required placeholder="Contoh: 25000" min="0" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Varian (Opsional)</label>
                    <input type="text" id="menu-variants" name="variants" placeholder="Pisahkan dengan koma (Hot, Ice)" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit" id="btn-submit" class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                        Simpan Menu
                    </button>
                    <button type="button" id="btn-cancel" onclick="resetForm()" class="hidden py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i data-lucide="coffee" class="w-5 h-5 text-amber-500"></i>
                    Daftar Menu
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-sm">
                            <th class="p-4 font-medium border-b">Menu</th>
                            <th class="p-4 font-medium border-b">Kategori</th>
                            <th class="p-4 font-medium border-b">Harga</th>
                            <th class="p-4 font-medium border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        <?php if(empty($menus)): ?>
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-400">Belum ada data menu.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($menus as $menu): ?>
                            <tr class="hover:bg-gray-50 border-b last:border-0 transition-colors">
                                <td class="p-4">
                                    <p class="font-medium text-gray-800"><?= esc($menu['name']) ?></p>
                                    <?php if(!empty($menu['variants'])): ?>
                                        <p class="text-xs text-gray-400">Varian: <?= esc($menu['variants']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded-lg text-xs font-medium">
                                        <?= esc($menu['category_name'] ?? 'Tanpa Kategori') ?>
                                    </span>
                                </td>
                                <td class="p-4 font-medium">Rp <?= number_format($menu['price'], 0, ',', '.') ?></td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" 
                                            onclick="editMenu('<?= $menu['id'] ?>', '<?= addslashes($menu['name']) ?>', '<?= $menu['category_id'] ?>', '<?= $menu['price'] ?>', '<?= addslashes($menu['variants']) ?>')" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f77ec" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen w-4 h-4"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg> 
                                        </button>
                                        
                                        <a href="<?= base_url('admin/menus/delete/'.$menu['id']) ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')"
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
    function editMenu(id, name, category_id, price, variants) {
        const form = document.getElementById('menu-form');
        const title = document.getElementById('form-title');
        
        form.action = "<?= base_url('admin/menus/update/') ?>" + id;
        
        document.getElementById('menu-name').value = name;
        document.getElementById('menu-category').value = category_id;
        document.getElementById('menu-price').value = price;
        document.getElementById('menu-variants').value = variants;
        
        title.innerHTML = '<i data-lucide="edit-3" class="w-5 h-5 text-blue-500"></i> Edit Menu';
        document.getElementById('btn-submit').innerText = 'Simpan Perubahan';
        document.getElementById('btn-cancel').classList.remove('hidden');
        
        lucide.createIcons();
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        const form = document.getElementById('menu-form');
        const title = document.getElementById('form-title');
        
        form.action = "<?= base_url('admin/menus/store') ?>";
        form.reset();
        
        title.innerHTML = '<i data-lucide="plus-circle" class="w-5 h-5 text-blue-500"></i> Tambah Menu Baru';
        document.getElementById('btn-submit').innerText = 'Simpan Menu';
        document.getElementById('btn-cancel').classList.add('hidden');
        
        lucide.createIcons();
    }
</script>
<?= $this->endSection() ?>