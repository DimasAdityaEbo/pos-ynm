<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'categories' => $this->categoryModel->findAll()
        ];
        return view('admin/categories/index', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'name' => 'required|min_length[3]|is_unique[categories.name]'
        ])) {
            return redirect()->to('/admin/categories')->with('error', 'Nama kategori tidak valid atau sudah ada.');
        }

        $this->categoryModel->save([
            'name' => $this->request->getPost('name')
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update($id)
    {
        $this->categoryModel->update($id, [
            'name' => $this->request->getPost('name')
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->categoryModel->delete($id);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus!');
    }
}