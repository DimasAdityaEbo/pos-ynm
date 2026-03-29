<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    
    protected $allowedFields    = ['transaction_id', 'cashier_id', 'total', 'payment_method', 'items'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getTransactionsWithCashier()
    {
        return $this->select('transactions.*, users.name as cashier_name')
                    ->join('users', 'users.id = transactions.cashier_id', 'left')
                    ->orderBy('transactions.created_at', 'DESC')
                    ->findAll();
    }
}