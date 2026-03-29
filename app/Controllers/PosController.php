<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\CategoryModel;
use App\Models\DiscountModel;
use App\Models\TransactionModel;

class PosController extends BaseController
{
    public function index()
    {
        $menuModel     = new MenuModel();
        $categoryModel = new CategoryModel();
        $discountModel = new DiscountModel();

        $data = [
            'menus'      => $menuModel->getMenusWithCategory(),
            'categories' => $categoryModel->findAll(),
            'discounts'  => $discountModel->where('valid_until >=', date('Y-m-d'))->findAll() 
        ];

        return view('pos/index', $data);
    }

    public function checkout()
    {
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->response->setJSON([
                'status'  => 'error', 
                'message' => 'Data tidak valid',
                'csrf'    => csrf_hash() 
            ]);
        }

        $transactionModel = new \App\Models\TransactionModel();

        $trx_id = 'TRX-' . date('Ymd') . '-' . rand(10000, 99999);

        $saved = $transactionModel->save([
            'transaction_id' => $trx_id,
            'cashier_id'     => session()->get('id'),
            'total'          => $json->total,
            'payment_method' => $json->payment_method,
            'items'          => json_encode($json->items)
        ]);

        if ($saved) {
            return $this->response->setJSON([
                'status'  => 'success', 
                'message' => 'Transaksi Berhasil!',
                'trx_id'  => $trx_id,
                'csrf'    => csrf_hash() 
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error', 
            'message' => 'Gagal menyimpan transaksi',
            'csrf'    => csrf_hash()
        ]);
    }

    // --- FUNGSI API RESERVASI UNTUK POS ---

    public function getReservations()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $reservationModel = new \App\Models\ReservationModel();
        
        // Ambil data reservasi berdasarkan tanggal yang dipilih
        $reservations = $reservationModel->where('reservation_date', $date)
                                         ->orderBy('reservation_time', 'ASC')
                                         ->findAll();

        return $this->response->setJSON([
            'status' => 'success', 
            'data'   => $reservations
        ]);
    }

    public function storeReservation()
    {
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak valid', 'csrf' => csrf_hash()]);
        }

        $reservationModel = new \App\Models\ReservationModel();

        $saved = $reservationModel->save([
            'customer_name'    => $json->customer_name,
            'customer_phone'   => $json->customer_phone,
            'reservation_date' => $json->reservation_date,
            'reservation_time' => $json->reservation_time,
            'guest_count'      => $json->guest_count,
            'notes'            => $json->notes,
            'status'           => 'pending' 
        ]);

        if ($saved) {
            return $this->response->setJSON([
                'status'  => 'success', 
                'message' => 'Reservasi berhasil dicatat!',
                'csrf'    => csrf_hash() 
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error', 
            'message' => 'Gagal menyimpan reservasi',
            'csrf'    => csrf_hash()
        ]);
    }


    public function getTransactions()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $cashierId = session()->get('id'); 
        $transactionModel = new \App\Models\TransactionModel();

        $transactions = $transactionModel->where('cashier_id', $cashierId)
                                         ->like('created_at', $date)
                                         ->orderBy('created_at', 'DESC')
                                         ->findAll();

        return $this->response->setJSON([
            'status' => 'success', 
            'data'   => $transactions
        ]);
    }
}