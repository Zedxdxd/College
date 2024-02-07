<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\I18n\Time;
use PDO;
use PhpParser\Node\Stmt\Echo_;

class ModelObicanKorisnikLana extends Model {
    protected $db;

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }
    // protected $table      = 'korisnik';
    // protected $primaryKey = 'SifK';

    // protected $useAutoIncrement = false;

    // protected $returnType     = 'object';

    // protected $allowedFields = ['SifK', 'KorisnickoIme','Lozinka','TraziBrisanje','Ime','Prezime','BrTel','Email','PrivatnikIliKoirsnik'];
    public function svaMesta() {
        return $this->db->table('mesto')->get()->getResult();
    }
    public function svaSredstva() {
        return $this->db->table('prevoznosredstvo')->get()->getResult();
    }
    public function mojeRezervacije($SifK) {
        $rezervacije =  $this->db->table('rezervacija')->where("SifK=", $SifK)->get()->getResult();
        //    print_r($rezervacije);
        //    echo $SifK;
        if (count($rezervacije) == 0) {
            return [];
        }

        $prave_rezervacije = [];
        foreach ($rezervacije as $rezervacija) {
            $cijela_rez = $this->db->table('ponuda')->where("SifP=", $rezervacija->SifP)->get()->getResult()[0];
            $cijela_rez->SifMesOd = $this->db->table('mesto')->where("SifM=", $cijela_rez->SifMesOd)->get()->getResult()[0]->Naziv;
            $cijela_rez->SifMesDo = $this->db->table('mesto')->where("SifM=", $cijela_rez->SifMesDo)->get()->getResult()[0]->Naziv;
            $cijela_rez->BrRez = $rezervacija->BrMesta;
            $cijela_rez->KorisnikTel = $this->db->table('korisnik')->where("SifK=", $SifK)->get()->getResult()[0]->BrTel;
            $cijela_rez->SifR = $rezervacija->SifR;
            array_push($prave_rezervacije, $cijela_rez);
        }
        // print_r($prave_rezervacije);
        return $prave_rezervacije;
    }

    public function zahtevBrisanje($SifK) {
        $korisnik = $this->db->table("korisnik")->where("SifK=", $SifK)->get()->getResult()[0];

        $korisnik->TraziBrisanje = 1;

        $this->db->table('korisnik')->where("SifK=", $SifK)->update($korisnik);
    }

    public function izmenaProfila($ime, $prezime, $lozinka, $email, $profilna, $SifK, $brTel) {
        $korisnik = $this->db->table("korisnik")->where("SifK=", $SifK)->get()->getResult()[0];

        if ($ime != "") {
            $korisnik->Ime = $ime;
        }
        if ($prezime != "") {
            $korisnik->Prezime = $prezime;
        }
        if ($lozinka != "") {
            $korisnik->Lozinka = $lozinka;
        }
        if ($email != "") {
            $korisnik->Email = $email;
        }
        if ($brTel != "") {
            $korisnik->BrTel = $brTel;
        }
        if ($profilna != null) {
            $korisnik->ProfilnaSlika = $profilna;
        }
        $this->db->table('korisnik')->where("SifK=", $SifK)->update($korisnik);
    }
    public function posaljiVandrednuVoznju($voznja) {

        $privatnici = $this->db->table('privatnik')->get()->getResult();
        $SifSred = $voznja['Sred'];

        foreach ($privatnici as $privatnik) {
            $VandrednaPonuda = [
                'DatumOd' => $voznja['DatumOd'],
                'DatumDo' => $voznja['DatumDo'],

                'VremeOd' => $voznja['VremeOd'],
                'VremeDo' => $voznja['VremeDo'],
                'SifMesDo' => $voznja['SifMesDo'],
                'SifMesOd' => $voznja['SifMesOd'],
                'SifSred' => $SifSred,
                'SifK' => session()->get("korisnik")->SifK,
                "SifPriv" => $privatnik->SifK,
                'CenaKarte' => 0,
                'BrMesta' => $voznja['BrMesta'],

            ];

            $this->db->table('ponuda')->insert($VandrednaPonuda);

            $vandredne = $this->db->table('ponuda')->where('DatumOd=', $VandrednaPonuda['DatumOd'])
                ->where('DatumDo=', $VandrednaPonuda['DatumDo'])
                ->where('VremeOd=', $VandrednaPonuda['VremeOd'])
                ->where('VremeDo=', $VandrednaPonuda['VremeDo'])
                ->where('SifMesDo=', $VandrednaPonuda['SifMesDo'])
                ->where('SifMesOd=', $VandrednaPonuda['SifMesOd'])
                ->where('SifSred=', $VandrednaPonuda['SifSred'])
                ->where('CenaKarte=', $VandrednaPonuda['CenaKarte'])
                ->where('BrMesta=', $VandrednaPonuda['BrMesta'])
                ->get()->getResult();
            foreach ($vandredne as $ponuda) {
                $nadjeni =  $this->db->table('zahtevponuda')->where("SifP=", $ponuda->SifP)->get()->getResult();
                if (count($nadjeni) == 0) {
                    $ZahtjvPonude = [
                        'SifP' => $ponuda->SifP,
                        'CenaDo' => $voznja['CenaDo'],
                        'CenaOd' => $voznja['CenaOd'],
                    ];


                    $this->db->table('zahtevponuda')->insert($ZahtjvPonude);

                    $poruka = [
                        'SifPonuda' => $ponuda->SifP,
                        'SifKor' => $voznja['SifK'],
                        'SifPriv' => $privatnik->SifK,
                        'SmerPoruke' => 1
                    ];
                    $this->db->table('poruka')->insert($poruka);

                    break;
                }
            }
        }
    }

    public function report($SifK, $komentar, $SifPrijavitelja) {
        $koga = $this->db->table('korisnik')->where("KorisnickoIme=", $SifK)->get()->getResult();
        if (count($koga) == 0) {
            return null;
        }
        $koga = $this->db->table('privatnik')->where("SifK=", $koga[0]->SifK)->get()->getResult();
        if (count($koga) == 0) {
            return null;
        }
        $prijava = [
            'Razlog' => $komentar,
            'SifPrijavio' => $SifPrijavitelja,
            'SifPrijavljen' => $koga[0]->SifK
        ];

        return $prijava;
    }
    public function ocenjivanje($Imeprivatnika, $komentar, $ocena, $SifK) {
        $koga = $this->db->table('korisnik')->where("KorisnickoIme=", $Imeprivatnika)->get()->getResult();
        if (count($koga) == 0) {

            return null;
        }
        $koga = $this->db->table('privatnik')->where("SifK=", $koga[0]->SifK)->get()->getResult();
        if (count($koga) == 0) {

            return null;
        }

        $ocena = [
            'Ocena' => $ocena,
            'Komentar' => $komentar,
            'SifPriv' => $koga[0]->SifK,
            'SifK' => $SifK

        ];
        return $ocena;
    }
    public function azuriraj_poklone_i_tokene($poklon, $SifK) {
        if ($poklon == "nista") {
            $korisnik = $this->db->table('obicankorisnik')->where('SifK=', $SifK)->get()->getResult()[0];
            $builder = $this->db->table("obicankorisnik")->where("SifK", $SifK);
            $builder->update(["token" => $korisnik->token - 1]);
            return;
        }

        $poklonobj = [
            'Iznos' => "",
            'TipPoklona' => "",

        ];
        if (strpos($poklon, "%")) {
            $poklonobj['Iznos'] = intval(substr($poklon, 0, -1));
            $poklonobj['TipPoklona'] = "%";
        } else {
            $poklonobj['Iznos'] = intval(substr($poklon, 0, -3));
            $poklonobj['TipPoklona'] = "€";
        }


        $korisnik = $this->db->table('obicankorisnik')->where('SifK=', $SifK)->get()->getResult()[0];
        // print_r($korisnik);
        if ($korisnik->token <= 0) {
            return;
        }
        $korisnik->token = $korisnik->token - 1;
        $this->db->table('obicankorisnik')->where("SifK=", $SifK)->update($korisnik);
        $poklon = $this->db->table('poklon')->where("TipPoklona=", $poklonobj['TipPoklona'])->where("Iznos=", $poklonobj['Iznos'])->get()->getResult()[0];
        $je_dobio = [
            'SifK' => $SifK,
            'SifPokl' => $poklon->SifPokl,
            'Datum' => Time::now()->toDateTimeString()
        ];
        // print_r($je_dobio);
        $this->db->table('jedobio')->insert($je_dobio);
    }
    public function rezervacija_karata($SifP, $SifK, $BrMesta) {
        $ponuda = $this->db->table('ponuda')->where("SifP=", $SifP)->get()->getResult()[0];
        if ($ponuda->BrMesta >= $BrMesta) {
            $ponuda->BrMesta  = $ponuda->BrMesta - $BrMesta;
            $rezervacija = [
                'BrMesta' => $BrMesta,
                'SifP' => $SifP,
                'SifK' => $SifK,
                'DatumRezervacije' => Time::now()->toDateTimeString()
            ];
            $this->db->table('rezervacija')->insert($rezervacija);
            $this->db->table('ponuda')->where("SifP=", $SifP)->update($ponuda);
            return true;
        }
        return false;
    }

    public function kupovina_karata($SifP, $SifK, $BrMesta, $SifPokl) {
        // echo "kaaaa.";


        $ponuda = $this->db->table('ponuda')->where("SifP=", $SifP)->get()->getResult()[0];
        if ($ponuda->BrMesta >= $BrMesta) {
            $ponuda->BrMesta = $ponuda->BrMesta - $BrMesta;
            for ($i = 0; $i < $BrMesta; $i++) {
                $kupljena_karta = [
                    'NacinPlacanja' => $ponuda->CenaKarte,
                    'SifP' => $SifP,
                    'SifK' => $SifK
                ];
                $db      = \Config\Database::connect();
                $db->table('kupljenakarta')->insert($kupljena_karta);

                $uplata = [
                    'SifKar' => $db->insertID(),
                    'DatumUplate' => Time::now()->toDateTimeString(),
                    'Iznos' => $ponuda->CenaKarte
                ];
                $db->table('uplata')->insert($uplata);
            }

            $this->db->table('ponuda')->where("SifP=", $SifP)->update($ponuda);
            $korisnik = $this->db->table('korisnik')->where('SifK', $SifK)->get()->getResult()[0];
            // echo "prije.";
            if (isset($SifPokl) && $SifPokl != "") {
                // echo "nagrada";
                $nagrada = $this->db->table('poklon')->where('SifPokl', $SifPokl)->get()->getResult()[0];
                if ($nagrada->TipPoklona == "%") {
                    // echo "%";

                    $korisnik->Novac -= $ponuda->CenaKarte * $BrMesta * (100 - $nagrada->Iznos) / 100.0;
                    $OVAJ = $this->db->table('jedobio')->where("SifK", $SifK)->where("SifPokl", $SifPokl)->get()->getResult()[0]->JeDobioPK;

                    $this->db->table('jedobio')->where("JeDobioPK", $OVAJ)->delete();
                } else if ($nagrada->TipPoklona == "€") {
                    $suma = $ponuda->CenaKarte * $BrMesta - $nagrada->Iznos;
                    if ($suma < 0) {
                        $suma = 0;
                    }
                    $korisnik->Novac = $korisnik->Novac - $suma;
                    $OVAJ = $this->db->table('jedobio')->where("SifK", $SifK)->where("SifPokl", $SifPokl)->get()->getResult()[0]->JeDobioPK;

                    $this->db->table('jedobio')->where("JeDobioPK", $OVAJ)->delete();
                }
            } else {
                $korisnik->Novac -= $ponuda->CenaKarte * $BrMesta;
            }
            $this->db->table('korisnik')->where("SifK=", $SifK)->update($korisnik);

            $privatnik = $this->db->table('korisnik')->where("SifK=", $ponuda->SifK)->get()->getResult()[0];
            $privatnik->Novac += $ponuda->CenaKarte * $BrMesta;
            $this->db->table('korisnik')->where("SifK=", $privatnik->SifK)->update($privatnik);
            return true;
        }
        return false;
    }
    public function otkazi_rezervaciju($SifR) {
        $rezervacija = $this->db->table('rezervacija')->where("SifR=", $SifR)->get()->getResult()[0];
        $SifP = $rezervacija->SifP;
        // echo $SifP;
        $ponuda = $this->db->table('ponuda')->where("SifP=", $SifP)->get()->getResult()[0];
        $ponuda->BrMesta += $rezervacija->BrMesta;
        $this->db->table('ponuda')->where("SifP=", $SifP)->update($ponuda);
        $this->db->table('rezervacija')->where("SifR=", $SifR)->delete();
    }
    public function kupi_kartu($SifR, $SifK, $BrMesta, $SifPokl) {

        $rezervacija = $this->db->table('rezervacija')->where("SifR=", $SifR)->get()->getResult()[0];
        $SifP = $rezervacija->SifP;
        $ponuda = $this->db->table('ponuda')->where("SifP=", $SifP)->get()->getResult();
        if (count($ponuda) == 0 || $BrMesta <= 0) {
            return false;
        }
        $ponuda = $ponuda[0];

        if (($BrMesta - $rezervacija->BrMesta) <= $ponuda->BrMesta) {
            if (($BrMesta - $rezervacija->BrMesta) < 0) {
                $ponuda->BrMesta += ($BrMesta - $rezervacija->BrMesta);
            } else {
                $ponuda->BrMesta -= ($BrMesta - $rezervacija->BrMesta);
            }
            $rezervacija->BrMesta = $BrMesta;

            $this->db->table('rezervacija')->where("SifR=", $rezervacija->SifR)->update($rezervacija);
            for ($i = 0; $i < $BrMesta; $i++) {
                $this->db->table('ponuda')->where("SifP=", $SifP)->update($ponuda); // jer se prilikom rezervacije azurira
                $kupljena_karta = [
                    'NacinPlacanja' => $ponuda->CenaKarte,
                    'SifP' => $SifP,
                    'SifK' => $SifK
                ];
                $db      = \Config\Database::connect();
                $db->table('kupljenakarta')->insert($kupljena_karta);

                $uplata = [
                    'SifKar' => $db->insertID(),
                    'DatumUplate' => Time::now()->toDateTimeString(),
                    'Iznos' => $ponuda->CenaKarte
                ];
                $db->table('uplata')->insert($uplata);
            }

            $this->db->table('rezervacija')->where("SifR", $rezervacija->SifR)->delete();
            $korisnik = $this->db->table('korisnik')->where('SifK', $SifK)->get()->getResult()[0];
            if (isset($SifPokl) && $SifPokl != "") {
                // echo "nagrada";
                $nagrada = $this->db->table('poklon')->where('SifPokl', $SifPokl)->get()->getResult()[0];
                if ($nagrada->TipPoklona == "%") {

                    $korisnik->Novac -= $ponuda->CenaKarte * $BrMesta * (100 - $nagrada->Iznos) / 100.0;
                    $OVAJ = $this->db->table('jedobio')->where("SifK", $SifK)->where("SifPokl", $SifPokl)->get()->getResult()[0]->JeDobioPK;

                    $this->db->table('jedobio')->where("JeDobioPK", $OVAJ)->delete();
                } else if ($nagrada->TipPoklona == "€") {
                    $suma = $ponuda->CenaKarte * $BrMesta - $nagrada->Iznos;
                    if ($suma < 0) {
                        $suma = 0;
                    }
                    $korisnik->Novac = $korisnik->Novac - $suma;
                    $OVAJ = $this->db->table('jedobio')->where("SifK", $SifK)->where("SifPokl", $SifPokl)->get()->getResult()[0]->JeDobioPK;

                    $this->db->table('jedobio')->where("JeDobioPK", $OVAJ)->delete();
                }
            } else {
                $korisnik->Novac -= $ponuda->CenaKarte * $BrMesta;
            }
            $this->db->table('korisnik')->where("SifK=", $SifK)->update($korisnik);

            $privatnik = $this->db->table('korisnik')->where("SifK=", $ponuda->SifK)->get()->getResult()[0];
            $privatnik->Novac += $ponuda->CenaKarte * $BrMesta;
            $this->db->table('korisnik')->where("SifK=", $privatnik->SifK)->update($privatnik);
            return true;
        }
    }
}
