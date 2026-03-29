<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'users' => $this->userModel->findAll()
        ];
        
        return view('admin/users/index', $data);
    }

    public function store()
    {
        if (!$this->validate(['username' => 'is_unique[users.username]'])) {
            return redirect()->to('/admin/users')->with('error', 'Username sudah digunakan, silakan pilih yang lain.');
        }

        $this->userModel->save([
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT) // Enkripsi password
        ]);

        return redirect()->to('/admin/users')->with('success', 'User baru berhasil ditambahkan!');
    }

    public function update($id)
    {
        $data = [
            'name'     => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/admin/users')->with('success', 'Data user berhasil diperbarui!');
    }

    public function delete($id)
    {
        if ($id == session()->get('id')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!');
        }

        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus!');
    }
}