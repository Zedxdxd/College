<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelReport extends Model
{
    protected $table      = 'report';
    protected $primaryKey = 'SifRep';

    protected $useAutoIncrement = false;

    protected $returnType     = 'object';

    protected $allowedFields = ['SifRep', 'Razlog','SifPrijavljen','SifPrijavio'];
}