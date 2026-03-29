<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;

class DiscountController extends BaseController
{
    protected $discountModel;

    public function __construct()
    {
        $this->discountModel = new DiscountModel();
    }

    public function index()
    {
        $data = [
            'discounts' => $this->discountModel->orderBy('created_at', 'DESC')->findAll()
        ];
        
        return view('admin/discounts/index', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'code' => 'required|is_unique[discounts.code]',
            'name' => 'required',
            'type' => 'required',
            'value'=> 'required|numeric',
            'valid_until' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Kode Promo sudah digunakan atau data tidak valid.');
        }

        $this->discountModel->save([
            'name'         => $this->request->getPost('name'),
            'code'         => strtoupper($this->request->getPost('code')), 
            'type'         => $this->request->getPost('type'),
            'value'        => $this->request->getPost('value'),
            'min_purchase' => $this->request->getPost('min_purchase') ?: 0,
            'valid_until'  => $this->request->getPost('valid_until')
        ]);

        return redirect()->to('/admin/discounts')->with('success', 'Promo diskon berhasil ditambahkan!');
    }

    public function update($id)
    {
        if (!$this->validate([
            'code' => "required|is_unique[discounts.code,id,{$id}]",
            'name' => 'required',
            'type' => 'required',
            'value'=> 'required|numeric',
            'valid_until' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Update gagal: Kode promo mungkin sudah digunakan.');
        }

        $this->discountModel->update($id, [
            'name'         => $this->request->getPost('name'),
            'code'         => strtoupper($this->request->getPost('code')),
            'type'         => $this->request->getPost('type'),
            'value'        => $this->request->getPost('value'),
            'min_purchase' => $this->request->getPost('min_purchase') ?: 0,
            'valid_until'  => $this->request->getPost('valid_until')
        ]);

        return redirect()->to('/admin/discounts')->with('success', 'Data promo diskon berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->discountModel->delete($id);
        return redirect()->to('/admin/discounts')->with('success', 'Promo diskon berhasil dihapus!');
    }
}