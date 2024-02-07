<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelMesto extends Model
{
    protected $table      = 'mesto';
    protected $primaryKey = 'SifM';

    protected $useAutoIncrement = false;

    protected $returnType     = 'object';

    protected $allowedFields = ['SifM', 'Naziv'];
}