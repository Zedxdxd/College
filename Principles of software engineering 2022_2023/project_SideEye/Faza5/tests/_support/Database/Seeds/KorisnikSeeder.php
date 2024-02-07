<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KorisnikSeeder extends Seeder
{
    public function run()
    {
        $korisnici = [
            [
                'SifK' => 4,
                'KorisnickoIme' => 'milica.c',
                'Lozinka' => 'Milica123#',
                'TraziBrisanje' => 0,
                'Ime' => 'Milica',
                'Prezime' => 'Cvetkovic',
                'BrTel' =>1234,
                'Email' => 'pomocniEPUTUJ2@outlook.com',
                'PrivatnikIliKorisnik' => 'P',
                'Novac' =>300,
                'ProfilnaSlika' => '3_20230531220122_RE4wwtb.jpg'
            ],
            [
                'SifK' => 12,
                'KorisnickoIme' => 'trivic123',
                'Lozinka' => 'Trivic123!',
                'TraziBrisanje' => 0,
                'Ime' => 'Aleksa',
                'Prezime' => 'Trivic',
                'BrTel' =>38165123456,
                'Email' => 'pomocniEPUTUJ2@outlook.com',
                'PrivatnikIliKorisnik' => 'K',
                'Novac' =>300,
                'ProfilnaSlika' => '3_20230531220122_RE4wwtb.jpg'
            ],
            [
                'SifK' => 5,
                'KorisnickoIme' => 'admin',
                'Lozinka' => 'Admin123#',
                'TraziBrisanje' => 0,
                'Ime' => 'admin',
                'Prezime' => 'admin',
                'BrTel' =>0,
                'Email' => 'admin',
                'PrivatnikIliKorisnik' => 'A',
                'Novac' =>0,
                'ProfilnaSlika' => '3_20230531220122_RE4wwtb.jpg'
            ],
        ];

        $builder = $this->db->table('korisnik');
        foreach ($korisnici as $korisnik) {
            $builder->insert($korisnik);
        }

        $ponude = [
            [
                'SifP' => 37,
                'BrMesta' => 1,
                'DatumOd' => '2023-07-09',
                'DatumDo' => '2023-06-26',
                'VremeOd' => '23:00:00',
                'VremeDo' => '20:00:00',
                'CenaKarte' => 10.00,
                'SifMesDo' => 3,
                'SifMesOd' => 8,
                'SifSred' => 2,
                'SifK' => 4,
                'Slika' => '31_20230605185505_beograd-na-vodi.jpg',
                'SifPriv' => 4
            ],
            [
                'SifP' => 31,
                'BrMesta' => 1,
                'DatumOd' => '2023-06-14',
                'DatumDo' => '2023-06-18',
                'VremeOd' => '22:22:00',
                'VremeDo' => '14:00:00',
                'CenaKarte' => 10.00                ,
                'SifMesDo' => 3,
                'SifMesOd' => 8,
                'SifSred' => 2,
                'SifK' => 4,
                'Slika' => '31_20230605185505_beograd-na-vodi.jpg',
                'SifPriv' => 4
            ],
        ];

        $poruke = [
            [
                "SifPonuda" => 31,
                "SifKor" => 12,
                "SifPriv" => 4,
                "SifPor" => 1,
                "SmerPoruke" => 2
            ]
        ];

        $vanredne = [
            [
            "SifP" => 31,
            "RokZaOtkazivanje" => 3
            ]
        ];

        $builder = $this->db->table('poruka');
        foreach ($poruke as $poruka) {
            $builder->insert($poruka);
        }
        
        $builder = $this->db->table('ponuda');
        foreach ($ponude as $ponuda) {
            $builder->insert($ponuda);
        }

        $builder = $this->db->table('vanrednaponuda');
        foreach ($vanredne as $vanredna) {
            $builder->insert($vanredna);
        }

        $postavljene=[[
            'SifP' => 37,
            'RokZaOtkazivanje' => 2
        ],];

        $builder = $this->db->table('postavljenaponuda');
        foreach ($postavljene as $p) {
            $builder->insert($p);
        }

        $privatnici=[
            ['SifK' => 4,
            'SifPret' => 1
            ],
        ];

        $builder=$this->db->table('privatnik');
        foreach($privatnici as $privatnik){ 
            $builder->insert($privatnik);
        }

        $pretplate=[
            [
                'SifPret' => 1,
                'Naziv' => 'Standard',
                'Iznos' => 9.99
            ],
        ];

        $builder=$this->db->table('pretplata');
        foreach($pretplate as $pretplata){ 
            $builder->insert($pretplata);
        }

        $obicnikorisnici=[
            [
                'SifK' => 12,
                'token' => 1
            ],
        ];
        $builder=$this->db->table('obicankorisnik');
        foreach($obicnikorisnici as $obicankorisnik){ 
            $builder->insert($obicankorisnik);
        }

        $admini=[
            [
                'SifK' => 5
            ],
        ];
        $builder=$this->db->table('admin');
        foreach($admini as $admin){ 
            $builder->insert($admin);
        }

        $ocene=[
            [
                'SifO' => 4,
                'Ocena' => 5,
                'SifPriv' => 4,
                'SifK' => 0,
                'Komentar' => 'super'
            ],
    
        ];

        $builder=$this->db->table('ocena');
        foreach($ocene as $ocena){ 
            $builder->insert($ocena);
        }

        $sredstva = [
            [
                'SifSred' => 2,
                'Naziv' => 'Automobil'
            ],
        ];
        $builder = $this->db->table('prevoznosredstvo');
        foreach($sredstva as $sredstvo){
            $builder->insert($sredstvo);
        }

        $mesta = [
            [
                'SifM' => 3,
                'Naziv' => 'Jagodina'
            ],
            [
                'SifM' => 8,
                'Naziv' => 'Valjevo'
            ]
        ];
        $builder = $this->db->table('mesto');
        foreach($mesta as $mesto){
            $builder->insert($mesto);
        }

        $pokloni = [
            [
                "SifPokl" => 1,
                "Iznos" => 10.00,
                "TipPoklona" => '%'
            ]
        ];
        $builder = $this->db->table('poklon');
        foreach($pokloni as $poklon){
            $builder->insert($poklon);
        }

        $dobici = [
            [
                "SifK" => 12,
                "SifPokl" => 1,
                "JeDobioPK" => 1,
                "Datum" => date("Y-m-d H:i:s")
            ]
        ];
        $builder = $this->db->table('jedobio');
        foreach($dobici as $dobitak){
            $builder->insert($dobitak);
        }

        $rezervacije = [
            [
                "SifK" => 12,
                "SifP" => 37,
                "BrMesta" => 4,
                "SifR" => 1,
                "DatumRezervacije" => "2023-06-05 19:55:50"
            ]
        ];
        $builder = $this->db->table('rezervacija');
        foreach($rezervacije as $rezervacija){
            $builder->insert($rezervacija);
        }
    }
}
