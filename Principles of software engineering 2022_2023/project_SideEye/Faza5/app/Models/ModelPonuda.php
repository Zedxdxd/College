<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPonuda extends Model
{
    protected $table      = 'ponuda';
    protected $primaryKey = 'SifP';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';

    protected $allowedFields = ['SifP', 'BrMesta','DatumOd','DatumDo','VremeOd','VremeDo','CenaKarte','SifMesDo','SifMesOd','SifSred','SifK'];
}