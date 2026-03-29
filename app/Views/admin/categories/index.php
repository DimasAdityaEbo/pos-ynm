<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="p-6 animate-fadeIn">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Kategori</h2>
            <p class="text-gray-500">Kelola kategori untuk menu kafe Anda.</p>
        </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-2xl p-6 shadow-sm h-fit">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-5 h-5 text-blue-500"></i>
                Tambah Kategori Baru
            </h3>
            <form action="<?= base_url('admin/categories/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Contoh: Kopi, Makanan Ringan" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i data-lucide="list" class="w-5 h-5 text-purple-500"></i>
                    Daftar Kategori
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-sm">
                            <th class="p-4 font-medium border-b">No</th>
                            <th class="p-4 font-medium border-b">Nama Kategori</th>
                            <th class="p-4 font-medium border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        <?php if(empty($categories)): ?>
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-400">Belum ada data kategori.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach($categories as $cat): ?>
                            <tr class="hover:bg-gray-50 border-b last:border-0 transition-colors">
                                <td class="p-4"><?= $no++ ?></td>
                                <td class="p-4 font-medium text-gray-800">
                                    <form action="<?= base_url('admin/categories/update/' . $cat['id']) ?>" method="POST" class="flex gap-2">
                                        <?= csrf_field() ?>
                                        <input type="text" name="name" value="<?= $cat['name'] ?>" class="px-3 py-1 border border-transparent hover:border-gray-300 focus:border-blue-500 rounded-lg bg-transparent w-full">
                                        <button type="submit" class="text-blue-500 hover:text-blue-700 text-xs font-semibold px-2" title="Simpan Perubahan">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                                <td class="p-4 text-center">
                                    <a href="<?= base_url('admin/categories/delete/' . $cat['id']) ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Data menu yang terhubung mungkin akan terpengaruh.')"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </a>
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