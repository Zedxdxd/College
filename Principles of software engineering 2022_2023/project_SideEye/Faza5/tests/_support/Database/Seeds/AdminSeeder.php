<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder {
    public function run() {
        $korisnici = [
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
                'SifK' => 2,
                'KorisnickoIme' => 'lanaIvk',
                'Lozinka' => 'lana123',
                'TraziBrisanje' => 1,
                'Ime' => 'Lana',
                'Prezime' => 'Ivkovic',
                'BrTel' => 12345678,
                'Email' => 'pomocniEPUTUJ2@outlook.com',
                'PrivatnikIliKorisnik' => 'K',
                'Novac' => 400,
                'ProfilnaSlika' => '3_20230531220122_RE4wwtb.jpg'
            ],
            [
                'SifK' => 10,
                'KorisnickoIme' => 'lanaIvk1',
                'Lozinka' => 'lana123',
                'TraziBrisanje' => 0,
                'Ime' => 'Lana',
                'Prezime' => 'Ivkovic',
                'BrTel' => 12345678,
                'Email' => 'pomocniEPUTUJ2@outlook.com',
                'PrivatnikIliKorisnik' => 'A',
                'Novac' => 400,
                'ProfilnaSlika' => '3_20230531220122_RE4wwtb.jpg'
            ]
        ];

        $builder = $this->db->table('korisnik');
        foreach ($korisnici as $korisnik) {
            $builder->insert($korisnik);
        }

        $privatnici = [
            [
                'SifK' => 4,
                'SifPret' => 1
            ],
        ];

        $builder = $this->db->table('privatnik');
        foreach ($privatnici as $privatnik) {
            $builder->insert($privatnik);
        }

        $pretplate = [
            [
                'SifPret' => 1,
                'Naziv' => 'Standard',
                'Iznos' => 9.99
            ],
        ];

        $builder = $this->db->table('pretplata');
        foreach ($pretplate as $pretplata) {
            $builder->insert($pretplata);
        }

        $obicnikorisnici = [
            [
                'SifK' => 2,
                'token' => 0
            ]
        ];
        $builder = $this->db->table('obicankorisnik');
        foreach ($obicnikorisnici as $obicankorisnik) {
            $builder->insert($obicankorisnik);
        }

        $admini = [
            [
                'SifK' => 5
            ],
        ];
        $builder = $this->db->table('admin');
        foreach ($admini as $admin) {
            $builder->insert($admin);
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
        foreach ($mesta as $mesto) {
            $builder->insert($mesto);
        }

        $reportovi = [
            [
                'Razlog' => 'Nije odrzana voznja.',
                'SifPrijavio' => 2,
                'SifRep' => 1,
                'SifPrijavljen' => 4
            ]
        ];

        $builder = $this->db->table('report');
        foreach ($reportovi as $report) {
            $builder->insert($report);
        }
    }
}
