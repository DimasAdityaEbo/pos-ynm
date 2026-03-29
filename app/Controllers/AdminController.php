<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\UserModel;
use App\Models\ReservationModel;
use App\Models\TransactionModel;
use App\Models\DiscountModel;

class AdminController extends BaseController
{
    public function index()
    {
        $menuModel        = new MenuModel();
        $userModel        = new UserModel();
        $reservationModel = new ReservationModel();
        $transactionModel = new TransactionModel();
        $discountModel    = new DiscountModel();

        $today         = date('Y-m-d');
        $thisMonth     = date('Y-m');
        $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
        $thisWeekEnd   = date('Y-m-d', strtotime('sunday this week'));

        $allTransactions = $transactionModel->findAll();

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
            if ($method == 'qris') $qrisCount++;
            if ($method == 'card') $cardCount++;

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
                    
                    if (!isset($menuSales[$menuName])) {
                        $menuSales[$menuName] = 0;
                    }
                    $menuSales[$menuName] += $qty;
                }
            }
        }

        arsort($menuSales);
        $topMenus = [];
        $count = 0;
        foreach ($menuSales as $name => $qty) {
            if ($count >= 5) break;
            $topMenus[] = ['name' => $name, 'qty' => $qty];
            $count++;
        }

        $todayReservations = $reservationModel->where('reservation_date', $today)
                                              ->orderBy('reservation_time', 'ASC')
                                              ->findAll();
        
        $confirmedReservationsCount = 0;
        foreach ($todayReservations as $res) {
            if ($res['status'] == 'confirmed' || $res['status'] == 'completed') {
                $confirmedReservationsCount++;
            }
        }

        $data = [
            'menusCount'        => $menuModel->countAllResults(),
            'usersCount'        => $userModel->countAllResults(),
            'reservationsCount' => $reservationModel->countAllResults(),
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
        ];

        return view('admin/dashboard', $data);
    }
}