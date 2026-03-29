<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReservationModel;

class ReservationController extends BaseController
{
    protected $reservationModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
    }

    public function index()
    {
        $data = [
            'reservations' => $this->reservationModel
                                ->orderBy('reservation_date', 'DESC')
                                ->orderBy('reservation_time', 'DESC')
                                ->findAll()
        ];
        
        return view('admin/reservations/index', $data);
    }

    public function store()
    {
        $this->reservationModel->save([
            'customer_name'    => $this->request->getPost('customer_name'),
            'customer_phone'   => $this->request->getPost('customer_phone'),
            'reservation_date' => $this->request->getPost('reservation_date'),
            'reservation_time' => $this->request->getPost('reservation_time'),
            'guest_count'      => $this->request->getPost('guest_count'),
            'notes'            => $this->request->getPost('notes'),
            'status'           => 'pending' 
        ]);

        return redirect()->to('/admin/reservations')->with('success', 'Data reservasi berhasil ditambahkan!');
    }

    public function update($id)
    {
        $this->reservationModel->update($id, [
            'customer_name'    => $this->request->getPost('customer_name'),
            'customer_phone'   => $this->request->getPost('customer_phone'),
            'reservation_date' => $this->request->getPost('reservation_date'),
            'reservation_time' => $this->request->getPost('reservation_time'),
            'guest_count'      => $this->request->getPost('guest_count'),
            'notes'            => $this->request->getPost('notes'),
        ]);

        return redirect()->to('/admin/reservations')->with('success', 'Data reservasi berhasil diperbarui!');
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        if (in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $this->reservationModel->update($id, ['status' => $status]);
            return redirect()->to('/admin/reservations')->with('success', 'Status reservasi diperbarui!');
        }
        return redirect()->to('/admin/reservations')->with('error', 'Status tidak valid!');
    }

    public function delete($id)
    {
        $this->reservationModel->delete($id);
        return redirect()->to('/admin/reservations')->with('success', 'Data reservasi berhasil dihapus!');
    }
}