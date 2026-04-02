<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\ReservationModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Models\DiscountModel;

class OwnerController extends BaseController
{
    protected $transactionModel;
    protected $reservationModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->reservationModel = new ReservationModel();
    }

    public function index()
    {
        $menuModel = new MenuModel();
        $userModel = new UserModel();
        $discountModel = new DiscountModel();

        $today         = date('Y-m-d');
        $thisMonth     = date('Y-m');
        $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
        $thisWeekEnd   = date('Y-m-d', strtotime('sunday this week'));

        $allTransactions = $this->transactionModel->findAll();

        $todayTotal        = 0;
        $todayTransactions = 0;
        $weekTotal         = 0;
        $monthTotal        = 0;
        
        $cashCount = 0;
        $qrisCount = 0;
        $cardCount = 0;

        $menuSales = []; 

        foreach ($allTransactions as $trx) {
            $trxDate  = date('Y-m-d', strtotime($trx['created_at']));
            $trxMonth = date('Y-m', strtotime($trx['created_at']));

            $method = strtolower($trx['payment_method']);
            if ($method == 'cash') $cashCount++;
            elseif ($method == 'qris') $qrisCount++;
            elseif ($method == 'card') $cardCount++;

            if ($trxDate == $today) {
                $todayTotal += $trx['total'];
                $todayTransactions++;
            }
            if ($trxDate >= $thisWeekStart && $trxDate <= $thisWeekEnd) {
                $weekTotal += $trx['total'];
            }
            if ($trxMonth == $thisMonth) {
                $monthTotal += $trx['total'];
            }

            $items = json_decode($trx['items'], true);
            if (is_array($items)) {
                foreach ($items as $item) {
                    $menuName = $item['name'];
                    $qty      = $item['qty'];
                    $menuSales[$menuName] = ($menuSales[$menuName] ?? 0) + $qty;
                }
            }
        }

        arsort($menuSales);
        $topMenus = [];
        foreach (array_slice($menuSales, 0, 5) as $name => $qty) {
            $topMenus[] = ['name' => $name, 'qty' => $qty];
        }

        $todayReservations = $this->reservationModel->where('reservation_date', $today)
                                              ->orderBy('reservation_time', 'ASC')
                                              ->findAll();
        
        $confirmedReservationsCount = count(array_filter($todayReservations, fn($r) => in_array($r['status'], ['confirmed', 'completed'])));

        return view('owner/dashboard', [
            'menusCount'        => $menuModel->countAllResults(),
            'usersCount'        => $userModel->countAllResults(),
            'reservationsCount' => $this->reservationModel->countAllResults(),
            'transactionsCount' => count($allTransactions), 
            'discountsCount'    => $discountModel->countAllResults(),
            'todayTotal'        => $todayTotal,
            'todayTransactions' => $todayTransactions,
            'weekTotal'         => $weekTotal,
            'monthTotal'        => $monthTotal,
            'cashCount'         => $cashCount,
            'qrisCount'         => $qrisCount,
            'cardCount'         => $cardCount,
            'topMenus'          => $topMenus,
            'todayReservations' => $todayReservations,
            'confirmedReservationsCount' => $confirmedReservationsCount
        ]);
    }

    public function salesReport()
    {
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?: date('Y-m-t');

        $transactions = $this->transactionModel->select('transactions.*, users.name as cashier_name')
                        ->join('users', 'users.id = transactions.cashier_id', 'left')
                        ->where('transactions.created_at >=', $startDate . ' 00:00:00')
                        ->where('transactions.created_at <=', $endDate . ' 23:59:59')
                        ->orderBy('transactions.created_at', 'DESC')
                        ->findAll();

        $totalRevenue = 0;
        $paymentStats = ['cash' => 0, 'qris' => 0, 'card' => 0];

        foreach ($transactions as $trx) {
            $totalRevenue += $trx['total'];
            $method = strtolower($trx['payment_method']);
            if (isset($paymentStats[$method])) {
                $paymentStats[$method]++;
            }
        }

        return view('owner/reports_sales', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalTrx'     => count($transactions),
            'paymentStats' => $paymentStats,
            'startDate'    => $startDate,
            'endDate'      => $endDate
        ]);
    }

    public function reservations()
    {
        $selectedDate = $this->request->getGet('date') ?? date('Y-m-d');

        $reservations = $this->reservationModel
            ->where('reservation_date', $selectedDate)
            ->orderBy('reservation_time', 'ASC') 
            ->findAll();

        $total = count($reservations);
        $confirmed = 0;
        $pending = 0;
        $cancelled = 0;

        foreach ($reservations as $res) {
            if ($res['status'] == 'confirmed') {
                $confirmed++;
            } elseif ($res['status'] == 'pending') {
                $pending++;
            } elseif ($res['status'] == 'cancelled') {
                $cancelled++;
            }
        }

        $data = [
            'reservations' => $reservations,
            'selectedDate' => $selectedDate,
            'total'        => $total,
            'confirmed'    => $confirmed,
            'pending'      => $pending,
            'cancelled'    => $cancelled
        ];
        
        return view('owner/reservations', $data);
    }
}