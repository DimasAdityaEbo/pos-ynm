<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\CategoryModel;

class MenuController extends BaseController
{
    protected $menuModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'menus'      => $this->menuModel->getMenusWithCategory(),
            'categories' => $this->categoryModel->findAll()
        ];
        
        return view('admin/menus/index', $data);
    }

    public function store()
    {
        $this->menuModel->save([
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'price'       => $this->request->getPost('price'),
            'variants'    => $this->request->getPost('variants') 
        ]);

        return redirect()->to('/admin/menus')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    public function update($id)
    {
        $this->menuModel->update($id, [
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'price'       => $this->request->getPost('price'),
            'variants'    => $this->request->getPost('variants')
        ]);

        return redirect()->to('/admin/menus')->with('success', 'Data menu berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->menuModel->delete($id);
        return redirect()->to('/admin/menus')->with('success', 'Menu berhasil dihapus!');
    }
}