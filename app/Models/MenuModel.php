<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table            = 'menus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    
    protected $allowedFields    = ['category_id', 'name', 'price', 'variants'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getMenusWithCategory()
    {
        return $this->select('menus.*, categories.name as category_name')
                    ->join('categories', 'categories.id = menus.category_id', 'left')
                    ->findAll();
    }
}