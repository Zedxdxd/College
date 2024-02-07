<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelObicanKorisnik extends Model
{
    protected $table= 'obicankorisnik';
    protected $primaryKey = 'SifK';

    protected $useAutoIncrement = false;

    protected $returnType     = 'object';

    protected $allowedFields = ['SifK'];
}