<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPrivatnik extends Model
{
    protected $table      = 'privatnik';
    protected $primaryKey = 'SifK';

    protected $useAutoIncrement = false;

    protected $returnType     = 'object';

    protected $allowedFields = ['SifK', 'SifPret'];
}