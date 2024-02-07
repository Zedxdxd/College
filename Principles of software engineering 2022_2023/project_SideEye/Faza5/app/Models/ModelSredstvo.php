<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelSredstvo extends Model
{
    protected $table      = 'prevoznosredstvo';
    protected $primaryKey = 'SifP';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';

    protected $allowedFields = ['SifSred', 'Naziv'];
}