<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelKorisnik extends Model
{
    protected $table      = 'korisnik';
    protected $primaryKey = 'SifK';

    protected $useAutoIncrement = false;

    protected $returnType     = 'object';

    protected $allowedFields = ['SifK', 'KorisnickoIme','Lozinka','TraziBrisanje','Ime','Prezime','BrTel','Email','PrivatnikIliKorisnik'];
}