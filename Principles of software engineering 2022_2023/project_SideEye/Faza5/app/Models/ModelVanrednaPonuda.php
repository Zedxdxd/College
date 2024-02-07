<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelVanrednaPonuda extends Model
{
    protected $table      = 'vanrednaponuda';
    protected $primaryKey = 'SifP';

    protected $useAutoIncrement = false;

    protected $returnType     = 'object';

    protected $allowedFields = ['SifP', 'RokZaOtkazivanje'];
}