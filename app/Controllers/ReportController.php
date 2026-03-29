<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class ReportController extends BaseController
{
    protected $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $query = $this->transactionModel->select('transactions.*, users.name as cashier_name')
                                        ->join('users', 'users.id = transactions.cashier_id', 'left');

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('transactions.created_at >=', $startDate . ' 00:00:00')
                  ->where('transactions.created_at <=', $endDate . ' 23:59:59');
        } else {
            $startDate = date('Y-m-01');
            $endDate   = date('Y-m-t');
            $query->where('transactions.created_at >=', $startDate . ' 00:00:00')
                  ->where('transactions.created_at <=', $endDate . ' 23:59:59');
        }

        $transactions = $query->orderBy('transactions.created_at', 'DESC')->findAll();

        $totalRevenue = 0;
        $paymentStats = ['cash' => 0, 'qris' => 0, 'card' => 0];

        foreach ($transactions as $trx) {
            $totalRevenue += $trx['total'];
            $method = strtolower($trx['payment_method']);
            if (isset($paymentStats[$method])) {
                $paymentStats[$method]++;
            }
        }

        $data = [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalTrx'     => count($transactions),
            'paymentStats' => $paymentStats,
            'startDate'    => $startDate,
            'endDate'      => $endDate
        ];
        
        return view('admin/reports/index', $data);
    }
}