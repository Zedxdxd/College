<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelZahtevPonuda extends Model
{
    protected $table      = 'zahtevponuda';
    protected $primaryKey = 'SifP';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';

    protected $allowedFields = ['SifP', 'CenaOd','CenaDo'];
}