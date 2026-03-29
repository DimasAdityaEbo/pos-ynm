<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="p-6 animate-fadeIn">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen User</h2>
        <p class="text-gray-500">Kelola akun admin, kasir, dan owner untuk sistem Anda.</p>
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
            <h3 id="form-title" class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-blue-500"></i>
                Tambah User Baru
            </h3>
            
            <form id="user-form" action="<?= base_url('admin/users/store') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="user-name" name="name" required placeholder="Contoh: Budi Santoso" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="user-username" name="username" required placeholder="Contoh: budi123" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role (Hak Akses)</label>
                    <select id="user-role" name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        <option value="kasir">Kasir</option>
                        <option value="admin">Admin</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="user-password" name="password" required placeholder="Masukkan password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    <p id="password-help" class="text-xs text-gray-400 mt-1 hidden">Kosongkan jika tidak ingin mengubah password.</p>
                </div>

                <div class="flex gap-2">
                    <button type="submit" id="btn-submit" class="flex-1 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                        Simpan User
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
                    <i data-lucide="users" class="w-5 h-5 text-purple-500"></i>
                    Daftar User
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-sm">
                            <th class="p-4 font-medium border-b">Nama & Username</th>
                            <th class="p-4 font-medium border-b">Role</th>
                            <th class="p-4 font-medium border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        <?php foreach($users as $user): ?>
                        <tr class="hover:bg-gray-50 border-b last:border-0 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800"><?= $user['name'] ?></p>
                                        <p class="text-xs text-gray-400">@<?= $user['username'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <?php 
                                    $roleColors = [
                                        'admin' => 'bg-red-50 text-red-600 border-red-100',
                                        'kasir' => 'bg-green-50 text-green-600 border-green-100',
                                        'owner' => 'bg-amber-50 text-amber-600 border-amber-100'
                                    ];
                                    $colorClass = $roleColors[$user['role']] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                ?>
                                <span class="px-3 py-1 border rounded-full text-xs font-semibold capitalize <?= $colorClass ?>">
                                    <?= $user['role'] ?>
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="editUser(<?= $user['id'] ?>, '<?= addslashes($user['name']) ?>', '<?= addslashes($user['username']) ?>', '<?= $user['role'] ?>')" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5f77ec" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen w-4 h-4"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                                    </button>
                                    <?php if($user['id'] != session()->get('id')): ?>
                                    <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function editUser(id, name, username, role) {
        const form = document.getElementById('user-form');
        const title = document.getElementById('form-title');
        const passwordInput = document.getElementById('user-password');
        const passwordHelp = document.getElementById('password-help');
        
        form.action = "<?= base_url('admin/users/update/') ?>" + id;
        
        document.getElementById('user-name').value = name;
        document.getElementById('user-username').value = username;
        document.getElementById('user-role').value = role;
        
        passwordInput.removeAttribute('required');
        passwordInput.value = '';
        passwordHelp.classList.remove('hidden');
        
        title.innerHTML = '<i data-lucide="user-cog" class="w-5 h-5 text-blue-500"></i> Edit User';
        document.getElementById('btn-submit').innerText = 'Simpan Perubahan';
        document.getElementById('btn-cancel').classList.remove('hidden');
        
        lucide.createIcons();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        const form = document.getElementById('user-form');
        const title = document.getElementById('form-title');
        const passwordInput = document.getElementById('user-password');
        const passwordHelp = document.getElementById('password-help');
        
        form.action = "<?= base_url('admin/users/store') ?>";
        form.reset();
        
        passwordInput.setAttribute('required', 'required');
        passwordHelp.classList.add('hidden');
        
        title.innerHTML = '<i data-lucide="user-plus" class="w-5 h-5 text-blue-500"></i> Tambah User Baru';
        document.getElementById('btn-submit').innerText = 'Simpan User';
        document.getElementById('btn-cancel').classList.add('hidden');
        
        lucide.createIcons();
    }
</script>
<?= $this->endSection() ?>