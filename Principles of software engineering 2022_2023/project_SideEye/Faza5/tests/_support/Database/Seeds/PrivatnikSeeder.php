<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrivatnikSeeder extends Seeder {
    public function run() {
        $korisnici = [
            [
                'SifK' => 3,
                'KorisnickoIme' => 'zeljko123',
                'Lozinka' => 'zeljko123',
                'TraziBrisanje' => 0,
                'Ime' => 'Zeljko',
                'Prezime' => 'Urosevic',
                'BrTel' => 432678900,
                'Email' => 'pomocniEPUTUJ2@outlook.com',
                'PrivatnikIliKorisnik' => 'P',
                'Novac' => 400,
                'ProfilnaSlika' => '3_20230531220122_RE4wwtb.jpg'
            ],
            [
                'SifK' => 4,
                'KorisnickoIme' => 'milica.c',
                'Lozinka' => 'Milica123#',
                'TraziBrisanje' => 0,
                'Ime' => 'Milica',
                'Prezime' => 'Cvetkovic',
                'BrTel' => 1234,
                'Email' => 'pomocniEPUTUJ2@outlook.com',
                'PrivatnikIliKorisnik' => 'P',
                'Novac' => 300,
                'ProfilnaSlika' => '3_20230531220122_RE4wwtb.jpg'
            ],
            [
                'SifK' => 12,
                'KorisnickoIme' => 'trivic123',
                'Lozinka' => 'Trivic123!',
                'TraziBrisanje' => 0,
                'Ime' => 'Aleksa',
                'Prezime' => 'Trivic',
                'BrTel' => 38165123456,
                'Email' => 'pomocniEPUTUJ2@outlook.com',
                'PrivatnikIliKorisnik' => 'K',
                'Novac' => 300,
                'ProfilnaSlika' => '3_20230531220122_RE4wwtb.jpg'
            ],
            [
                'SifK' => 5,
                'KorisnickoIme' => 'admin',
                'Lozinka' => 'Admin123#',
                'TraziBrisanje' => 0,
                'Ime' => 'admin',
                'Prezime' => 'admin',
                'BrTel' => 0,
                'Email' => 'admin',
                'PrivatnikIliKorisnik' => 'A',
                'Novac' => 0,
                'ProfilnaSlika' => '3_20230531220122_RE4wwtb.jpg'
            ],
        ];

        $builder = $this->db->table('korisnik');
        foreach ($korisnici as $korisnik) {
            $builder->insert($korisnik);
        }


        $mesta = [
            [
                'SifM' => 0,
                'Naziv' => 'Beograd'
            ],
            [
                'SifM' => 14,
                'Naziv' => 'Trebinje'
            ],
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
        foreach ($mesta as $mesto) {
            $builder->insert($mesto);
        }

        $sredstva = [
            [
                'SifSred' => 2,
                'Naziv' => 'Automobil'
            ]
        ];
        $builder = $this->db->table('prevoznosredstvo');
        foreach ($sredstva as $sredstvo) {
            $builder->insert($sredstvo);
        }

        $ponude = [
            [
                'SifP' => 31,
                'BrMesta' => 1,
                'DatumOd' => '2023-06-14',
                'DatumDo' => '2023-06-30',
                'VremeOd' => '22:22:00',
                'VremeDo' => '14:00:00',
                'CenaKarte' => 10.00,
                'SifMesDo' => 0,
                'SifMesOd' => 14,
                'SifSred' => 2,
                'SifK' => 0,
                'Slika' => '31_20230605185505_beograd-na-vodi.jpg',
                'SifPriv' => 0
            ],
            [
                'SifP' => 37,
                'BrMesta' => 1,
                'DatumOd' => '2023-06-09',
                'DatumDo' => '2023-07-26',
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
        ];

        $builder = $this->db->table('ponuda');
        foreach ($ponude as $ponuda) {
            $builder->insert($ponuda);
        }

        $postavljene = [[
            'SifP' => 37,
            'RokZaOtkazivanje' => 2
        ],];

        $builder = $this->db->table('postavljenaponuda');
        foreach ($postavljene as $p) {
            $builder->insert($p);
        }

        $rezervacije = [
            [
                "SifK" => 4,
                "SifP" => 31,
                "BrMesta" => 2,
                "DatumRezervacije" => '2023-06-02',
            ]
        ];

        $builder = $this->db->table('rezervacija');
        foreach ($rezervacije as $r) {
            $builder->insert($r);
        }

        $kupljenekarte = [
            [
                "SifKar" => 2,
                "NacinPlacanja" => 100.00,
                "SifP" => 37,
                "SifK" => 4
            ]
        ];

        $builder = $this->db->table('kupljenakarta');
        foreach ($kupljenekarte as $k) {
            $builder->insert($k);
        }

        $uplate = [
            [
                "SifU" => 1,
                "DatumUplate" => "2023-06-11 12:00:00",
                "Iznos" => 100.00,
                "SifKar" => 2
            ]
        ];

        $builder = $this->db->table('uplata');
        foreach ($uplate as $u) {
            $builder->insert($u);
        }

        $pretplate = [
            [
                "SifPret" => 1,
                "Naziv" => "Standard",
                "Iznos" => 9.99
            ],
            [
                "SifPret" => 2,
                "Naziv" => "Premium",
                "Iznos" => 29.99
            ]
        ];

        $builder = $this->db->table('pretplata');
        foreach ($pretplate as $p) {
            $builder->insert($p);
        }

        $privatnici = [
            [
                'SifK' => 3,
                "SifPret" => 1
            ],
            [
                'SifK' => 4,
                "SifPret" => 2
            ]
        ];

        $builder = $this->db->table('privatnik');
        foreach ($privatnici as $p) {
            $builder->insert($p);
        }

        $poruke = [
            [
                "SifPonuda" => 31,
                "SifKor" => 5,
                "SifPriv" => 3,
                "SifPor" => 17,
                "SmerPoruke" => 2
            ],
            [
                "SifPonuda" => 37,
                "SifKor" => 12,
                "SifPriv" => 3,
                "SifPor" => 22,
                "SmerPoruke" => 1
            ]
        ];

        $builder = $this->db->table('poruka');
        foreach ($poruke as $p) {
            $builder->insert($p);
        }

        $zahtevane = [
            [
                "SifP" => 37,
                "CenaOd" => 20,
                "CenaDo" => 30
            ]
        ];

        $builder = $this->db->table('zahtevponuda');
        foreach ($zahtevane as $z) {
            $builder->insert($z);
        }
    }
}
