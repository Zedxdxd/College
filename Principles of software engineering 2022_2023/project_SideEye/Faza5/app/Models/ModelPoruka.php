<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPoruka extends Model
{
    protected $table      = 'poruka';
    protected $primaryKey = 'SifPor';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';

    protected $allowedFields = ['SifPor', 'SifKor','SifPriv','SifPonuda','SmerPoruke'];
}