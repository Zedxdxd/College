<?php

namespace App\Controllers;

use CodeIgniter\Database\Database;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;


class KorisnikControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    protected $DBGroup = 'tests';

    protected $migrate     = false;
    //protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'Tests\Support\Database';

    // For Seeds
    protected $seedOnce = false;
    protected $seed     = 'Tests\Support\Database\Seeds\KorisnikSeeder';
   protected $basePath = 'Tests\Support\Database';
    
    
    protected function setUp(): void
    {
        parent::setUp();
        
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $builder = $this->db->table("admin")->truncate();
        $builder = $this->db->table("jedobio")->truncate();
        $builder = $this->db->table("korisnik")->truncate();
        $builder = $this->db->table("kupljenakarta")->truncate();
        $builder = $this->db->table("mesto")->truncate();
        $builder = $this->db->table("obicankorisnik")->truncate();
        $builder = $this->db->table("ocena")->truncate();
        $builder = $this->db->table("poklon")->truncate();
        $builder = $this->db->table("ponuda")->truncate();
        $builder = $this->db->table("poruka")->truncate();
        $builder = $this->db->table("postavljenaponuda")->truncate();
        $builder = $this->db->table("pretplata")->truncate();
        $builder = $this->db->table("prevoznosredstvo")->truncate();
        $builder = $this->db->table("privatnik")->truncate();
        $builder = $this->db->table("report")->truncate();
        $builder = $this->db->table("rezervacija")->truncate();
        $builder = $this->db->table("uplata")->truncate();
        $builder = $this->db->table("vanrednaponuda")->truncate();
        $builder = $this->db->table("zahtevponuda")->truncate();
    }

    public function testIndexPage(){

        $korisnik = [
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
        ];


        session()->set('korisnik', (object)$korisnik);
        
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('index');
        $this->assertTrue($results->see('Točak sreće'));
    }

    public function testInboxKorisnikNijeSelektovanaPoruka(){
        $korisnik = [
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
        ];

        session()->set('korisnik', (object)$korisnik);
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('inboxKorisnik');
        $this->assertTrue($results->see('milica.c'));
        $this->assertTrue($results->see("Mesto polaska:Valjevo"));
        // var_dump($results);
    }

    public function testObrisiPoruku(){
        $korisnik = [
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
        ];

        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['SifP'] = 31;

        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('obrisiPoruku');
        $poruka = [
            "SifPor" => 1
        ];
        $this->dontSeeInDatabase("poruka", $poruka);
    }

    public function testInboxKorisnikSelektovanaPoruka(){
        $korisnik = [
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
        ];

        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['poruka'] = 1;

        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('inboxKorisnikPoruka');
        $this->assertTrue($results->see("Jagodina"));
        $this->assertTrue($results->see("2023-06-14"));
    }

    public function testIzmenaProfilaPrikaz(){
        $korisnik = [
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
        ];

        session()->set('korisnik', (object)$korisnik);

        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('izmenaProfila');
        $this->assertTrue($results->see("Lozinka"));
        $this->assertTrue($results->see("Izaberite fotografiju"));
    }

    public function testIzmenaProfilaPogresanFormatEmaila(){
        $korisnik = [
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
        ];

        session()->set('korisnik', (object)$korisnik);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['ime'] = 'Aleksa';
        $_POST['prezime'] = 'Trivic';
        $_POST['lozinka'] = 'Trivic123!';
        $_POST['email'] = 'abcd';
        $_FILES['slika']['tmp_name'] = 'abcd';
        $_POST['dugme'] = "Sačuvaj";
        $_POST['ponovljena'] = "Trivic123!";
        $_POST['brTel'] = 38165123456;

        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('izmenaProfila');
        $this->assertTrue($results->see("Email adresa u pogrešnom formatu."));
    }

    public function testIzmenaProfilaPogresanFormatLozinke(){
        $korisnik = [
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
        ];

        session()->set('korisnik', (object)$korisnik);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['ime'] = 'Aleksa';
        $_POST['prezime'] = 'Trivic';
        $_POST['lozinka'] = 'trivic123';
        $_POST['email'] = 'pomocniEPUTUJ2@outlook.com';
        $_FILES['slika']['tmp_name'] = 'abcd';
        $_POST['dugme'] = "Sačuvaj";
        $_POST['ponovljena'] = 'trivic123';
        $_POST['brTel'] = 38165123456;

        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('izmenaProfila');
        $this->assertTrue($results->see("Lozinka mora da sadrži jedno malo slovo, jedno veliko slovo, jedan specijalan karakter, jednu cifru i da je dužine od 8 do 14 karaktera."));
    }

    public function testIzmenaProfilaPonovljenaLozinkaRazlicita(){
        $korisnik = [
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
        ];

        session()->set('korisnik', (object)$korisnik);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['ime'] = 'Aleksa';
        $_POST['prezime'] = 'Trivic';
        $_POST['lozinka'] = 'Trivic123!';
        $_POST['email'] = 'pomocniEPUTUJ2@outlook.com';
        $_FILES['slika']['tmp_name'] = 'abcd';
        $_POST['dugme'] = "Sačuvaj";
        $_POST['lozinka'] = 'Trivic1234!';
        $_POST['brTel'] = 38165123456;

        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('izmenaProfila');
        $this->assertTrue($results->see("Lozinka u polju potvrde nije ista kao prva unesena."));
    }

    public function testIzmenaProfilaUspesno(){
        $korisnik = [
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
        ];

        session()->set('korisnik', (object)$korisnik);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['ime'] = 'Aleksa1';
        $_POST['prezime'] = 'Trivic1';
        $_POST['lozinka'] = 'Trivic1234!';
        $_POST['email'] = 'pomocniEPUTUJ23@outlook.com';
        $_FILES['slika']['tmp_name'] = 'abcd';
        $_POST['dugme'] = "Sačuvaj";
        $_POST['ponovljena'] = 'Trivic1234!';
        $_POST['brTel'] = 38165123456;

        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('izmenaProfila');
        $izmenjeno = [
            "Ime" => "Aleksa1",
            "Prezime" => "Trivic1",
            "Lozinka" => "Trivic1234!",
            "Email" => "pomocniEPUTUJ23@outlook.com"
        ];
        $this->seeInDatabase("korisnik", $izmenjeno);
    }

    
    

    public function testOcenjivanjePrikaz(){
        $korisnik = [
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
        ];

        session()->set('korisnik', (object)$korisnik);
        $this->request->setMethod('GET');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('ocenjivanje');
        $this->assertTrue($results->see("Oceni"));
        $this->assertTrue($results->see("Ime privatnika"));
        $this->assertTrue($results->see("Ocena"));
        $this->assertTrue($results->see("(1-5)"));
        $this->assertTrue($results->see("Komentar"));
    }

    public function testOcenjivanjePrivatnikNePostoji(){
        $korisnik = [
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
        ];
        $_POST['Imeprivatnika'] = "abc";
        $_POST['ocena'] = 3;
        $_POST['komentar'] = "Moze bolje!";

        session()->set('korisnik', (object)$korisnik);
        $this->request->setMethod('POST');
        
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('ocenjivanje');
        $this->assertTrue($results->see("Privatnik ne postoji!"));
    }

    public function testOcenjivanjeUspesno(){
        $korisnik = [
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
        ];
        $_POST['Imeprivatnika'] = "milica.c";
        $_POST['ocena'] = 5;
        $_POST['komentar'] = "Super!";

        session()->set('korisnik', (object)$korisnik);
        $this->request->setMethod('POST');
        
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('ocenjivanje');
        $this->assertTrue($results->see("Uspešno ste ocenili privatnika."));
        $ocena = [
            "Ocena" => 5,
            "Komentar" => "Super!",
            "SifPriv" => 4,
            "SifK" => 12
        ];
        $this->seeInDatabase("ocena", $ocena);
    }

    public function testPrikazPonudeInbox(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['SifP'] = 31;
        $this->request->setMethod('GET');

        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('prikazPonudeInbox');
        $this->assertTrue($results->see("Prihvati"));
        $this->assertTrue($results->see("Odbij"));
    }

    public function ponudaProvider(){
        return [
            [37],
        ];
    }
    /**
     * @dataProvider ponudaProvider
     */
    public function testPrikazPonude($SifP){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['SifP'] = 31;
        $this->request->setMethod('GET');

        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('prikazPonude', $SifP);
        $this->assertTrue($results->see("Rezerviši"));
        $this->assertTrue($results->see("Kupi kartu"));
        
    }

    
    public function prikazPonudeKupiProvider(){
        return [
            [37, "kupi"]
        ];
    }
    /**
     * @dataProvider prikazPonudeKupiProvider
     */
    public function testPrikazPonudeKupiNeuspesno($SifP, $tip){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $this->request->setMethod('POST');
        $_REQUEST["BrMesta"] = 5;
        $_POST["grupa"] = 1;
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('prikazPonude', $SifP, $tip);
        $this->assertTrue($results->see("Neuspešna kupovina karte!"));
    }

    /**
     * @dataProvider prikazPonudeKupiProvider
     */
    public function testPrikazPonudeKupiUspesno($SifP, $tip){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $this->request->setMethod('POST');
        $_REQUEST["BrMesta"] = 1;
        $_POST["grupa"] = 1;
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('prikazPonude', $SifP, $tip);
        $this->assertTrue($results->see("Uspešno ste kupili kartu!"));
    }

    
    public function prikazPonudeRezervisiProvider(){
        return [
            [37, "rezervisi"]
        ];
    }
    /**
     * @dataProvider prikazPonudeRezervisiProvider
     */
    public function testPrikazPonudeRezervisiNeuspesno($SifP, $tip){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $this->request->setMethod('POST');
        $_REQUEST["BrMesta"] = 5;
        $_POST["grupa"] = 1;
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('prikazPonude', $SifP, $tip);
        $this->assertTrue($results->see("Neuspešna rezervacija karte!"));
    }

    /**
     * @dataProvider prikazPonudeRezervisiProvider
     */
    public function testPrikazPonudeRezervisiUspesno($SifP, $tip){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $this->request->setMethod('POST');
        $_REQUEST["BrMesta"] = 1;
        $_POST["grupa"] = 1;
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('prikazPonude', $SifP, $tip);
        $this->assertTrue($results->see("Uspešno ste rezervisali kartu!"));
    }

    public function testPrikazReport(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $this->request->setMethod('GET');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('report');
        $this->assertTrue($results->see("Report"));
    }

    public function testReportNePostojiPrivatnik(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $this->request->setMethod('POST');
        $_POST["SifK"] = "abc";
        $_POST["komentar"] = "Nije odrzana voznja.";
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('report');
        $this->assertTrue($results->see("Privatnik ne postoji!"));

    }

    public function testReportPrazanOpisPrijave(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $this->request->setMethod('POST');
        $_POST["SifK"] = "milica.c";
        $_POST["komentar"] = "";
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('report');
        $this->assertTrue($results->see("Opis prijave je prazan, neophodno ga je popuniti!"));
    }

    public function testReportUspesno(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $this->request->setMethod('POST');
        $_POST["SifK"] = "milica.c";
        $_POST["komentar"] = "Nije odrzana voznja.";
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('report');
        $this->assertTrue($results->see("Uspesno ste prijavili privatnika!"));
        $report = [
            "Razlog" => "Nije odrzana voznja.",
            "SifPrijavio" => 12,
            "SifPrijavljen" => 4,
            "SifRep" => 0
        ];
        $this->seeInDatabase("report", $report);
    }

    public function testRezervacijePrikaz(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('rezervacije');
        
        $this->assertTrue($results->see("Moje rezervacije"));
        $this->assertTrue($results->see("Valjevo"));
    }

    public function testkupi_kartuNeuspesno() {
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $this->request->setMethod('POST');
        $_REQUEST["kupi"] = "kupi";
        $_POST["SifR"] = 1;
        $_POST["BrMesta"] = -1;
        $_POST["grupa"] = 1;
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('kupi_kartu');
        $this->assertTrue($results->see("Neuspešna kupovina karte!"));
    }

    public function testkupi_kartuUspesno() {
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST["kupi"] = "kupi";
        $_POST["SifR"] = 1;
        $_POST["BrMesta"] = 1;
        $_POST["grupa"] = 1;
        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('kupi_kartu');
        // var_dump($results);
        $this->assertTrue($results->see("Uspešno ste kupili kartu!"));
    }

    public function testkupi_kartuOtkaziRezervaciju() {
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST["kupi"] = "odustani";
        $_POST["SifR"] = 1;
        $_POST["BrMesta"] = 1;
        $_POST["grupa"] = 1;
        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('kupi_kartu');
        $rezervacija = [
            "SifR" => 1
        ];
        $this->dontSeeInDatabase("rezervacija", $rezervacija);
    }

    public function testTrazenjeVoznjePraznaForma() {
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $_POST['DatumDo'] = "2023-05-06";
        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('trazenjeVoznje');
        $this->assertTrue($results->see("Neophodno je da popunite sva polja, kako bi uspešno zatražili vožnju!"));
    }

    public function testTrazenjeVoznjeNekiUnetiDatumIVremePrethodeDanasnjem() {
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $_POST['DatumOd'] = "2023-05-06";
        $_POST['VremeOd'] = "22:22:00";
        $_POST['DatumDo'] = "2023-08-06";
        $_POST['VremeDo'] = "14:00:00";
        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('trazenjeVoznje');
        $this->assertTrue($results->see("Greska pri unosu podataka za datum i vreme ostvarivanja voznje"));
    }

    public function testTrazenjeVoznjeBrojPutnikaNegativan() {
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $_POST['DatumOd'] = "2023-07-06";
        $_POST['VremeOd'] = "22:22:00";
        $_POST['DatumDo'] = "2023-08-06";
        $_POST['VremeDo'] = "14:00:00";
        $_POST["BrojPutnika"] = 0;
        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('trazenjeVoznje');
        $this->assertTrue($results->see("Neophodno je da Broj Putnika bude veca od nule"));
    }

    public function testTrazenjeVoznjeNeadekvatneCene() {
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $_POST['DatumOd'] = "2023-07-06";
        $_POST['VremeOd'] = "22:22:00";
        $_POST['DatumDo'] = "2023-08-06";
        $_POST['VremeDo'] = "14:00:00";
        $_POST["BrojPutnika"] = 2;
        $_POST["CenaOd"] = 70;
        $_POST["CenaDo"] = 60;
        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('trazenjeVoznje');
        $this->assertTrue($results->see("Cena mora biti pozitivna i Cena Do mora biti veca od Cene od!"));
    }

    public function testTrazenjeVoznjeMestoPolaskaIDolaskaIsto() {
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $_POST['DatumOd'] = "2023-07-06";
        $_POST['VremeOd'] = "22:22:00";
        $_POST['DatumDo'] = "2023-08-06";
        $_POST['VremeDo'] = "14:00:00";
        $_POST["BrojPutnika"] = 2;
        $_POST["CenaOd"] = 50;
        $_POST["CenaDo"] = 60;
        $_POST["MesOd"] = 3;
        $_POST["MesDo"] = 3;
        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('trazenjeVoznje');
        $this->assertTrue($results->see("Mesto polaska i dolaska se moraju razlikovati!"));
    }

    public function testTrazenjeVoznjeUspesno() {
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $_POST['DatumOd'] = "2023-07-06";
        $_POST['VremeOd'] = "22:22:00";
        $_POST['DatumDo'] = "2023-08-06";
        $_POST['VremeDo'] = "14:00:00";
        $_POST["BrojPutnika"] = 2;
        $_POST["CenaOd"] = 50;
        $_POST["CenaDo"] = 60;
        $_POST["MesOd"] = 3;
        $_POST["MesDo"] = 8;
        $_POST["prevoz"] = 2;
        $this->request->setMethod('POST');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('trazenjeVoznje');
        $this->assertTrue($results->see("Uspešno ste izvršili tražnju vožnje!"));
    }

    public function testSpintheweel(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $this->request->setMethod('GET');
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('spintheweel');
        $this->assertTrue($results->see("Spin"));
    }

    public function testTocakSrece(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $this->request->setMethod('POST');
        $_POST["poklon"] = "10%";
        $results = $this->withURI("http://localhost:8080/KorisnikController")->controller("App\Controllers\KorisnikController")->execute('tocakSrece');
        $this->seeInDatabase("obicankorisnik", [
            "SifK" => 12,
            "token" => 0
        ]);
    }

    public function testKomentarKorisnik1(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['stranica']='trazenjeVoznje';
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\KorisnikController")->execute('komentar');
        $this->assertTrue($results->see('Zatraži vožnju'));
        
    }
    public function testKomentarKorisnik2(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['stranica']='rezervacije';
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\KorisnikController")->execute('komentar');
        $this->assertTrue($results->see('Moje rezervacije'));
    }
    public function testKomentarKorisnik3(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['stranica']='report';
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\KorisnikController")->execute('komentar');
        $this->assertTrue($results->see('Report'));
    }
    public function testKomentarKorisnik4(){
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['stranica']="inboxKorisnik";
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\KorisnikController")->execute('komentar');
        $this->assertTrue($results->see('milica.c'));
    }
    public function testKomentarKorisnik5(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['stranica']="indexkorisnik";
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\KorisnikController")->execute('komentar');
        $this->assertTrue($results->see('Sa nama putujte raznim prevoznim sredstvima, a očekuju vas i brojne nagrade!'));
    }
    public function testKomentarKorisnik6(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['stranica']="izmenaProfila";
        $_REQUEST['ime']='Anja';
        $this->request->setMethod('GET');
        $results = $this->controller("App\Controllers\KorisnikController")->execute('komentar');
        // var_dump($results);
        $this->assertTrue($results->see("Lozinka"));
        $this->assertTrue($results->see("Izaberite fotografiju"));
    }

    public function testKomentarKorisnik7(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['stranica']="prikazPonude";
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\KorisnikController")->execute('komentar');
        $this->assertTrue($results->see("Sortiraj"));
    }

    public function testKomentarKorisnik8(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['stranica']="prikazPonudeInbox";
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\KorisnikController")->execute('komentar');
        $this->assertTrue($results->see('milica.c'));
        $this->assertTrue($results->see("Mesto polaska:Valjevo"));
    }

    public function testKomentarKorisnik9(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['stranica']="ocenjivanje";
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\KorisnikController")->execute('komentar');
        $this->assertTrue($results->see("Oceni"));
        $this->assertTrue($results->see("Ime privatnika"));
        $this->assertTrue($results->see("Ocena"));
        $this->assertTrue($results->see("(1-5)"));
        $this->assertTrue($results->see("Komentar"));
    }

    public function testKomentarKorisnik10(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['stranica']="pregledPonuda";
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\KorisnikController")->execute('komentar');
        $this->assertTrue($results->see("Sortiraj"));
    }

    public function testLogoutKorisnik() {
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);

        $results = $this->controller('App\Controllers\KorisnikController')->execute('logout');
        $this->assertTrue($results->see('Uloguj se'));
    }

    public function testProsekKorisnik(){ 
        $ponuda=[
            
            'SifP' => 0,
            'BrMesta' => 1,
            'DatumOd' => '2023-07-09',
            'DatumDo' => '2023-06-26',
            'VremeOd' => '23:00:00',
            'VremeDo' => '20:00:00',
            'CenaKarte' => 10.00,
            'SifMesDo' => 3,
            'SifMesOd' => 8,
            'SifSred' => 2,
            'SifK' => 1,
            'Slika' => '31_20230605185505_beograd-na-vodi.jpg',
            'SifPriv' => 10
        ];
        $results = $this->controller("App\Controllers\KorisnikController")->execute('prosek',(object)$ponuda);
        $this->assertEquals($results->getResult(),0);
    }

    public function testPretragaPonudaKorisnik1(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['sortiranje']="rastuceCena";
        $_REQUEST["resetPage"]=null;
        $_REQUEST['sort']=true;
        $_REQUEST['page']=1;
        $_REQUEST["prevoznoSredstvo"]=2;

        $results = $this->controller("App\Controllers\KorisnikController")->execute('pretragaPonuda');
        $this->assertTrue($results->see("Jagodina"));
        
    }
    public function testPretragaPonudaKorisnik2(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['sortiranje']="rastuceDatum";
        $_REQUEST["resetPage"]=null;
        $_REQUEST['sort']=true;
        $_REQUEST['page']=1;
        $_REQUEST["prevoznoSredstvo"]=2;

        $results = $this->controller("App\Controllers\KorisnikController")->execute('pretragaPonuda');
        $this->assertTrue($results->see("Jagodina"));
    }
    public function testPretragaPonudaKorisnik3(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['sortiranje']="opadajuceCena";
        $_REQUEST["resetPage"]=null;
        $_REQUEST['sort']=true;
        $_REQUEST['page']=1;
        $_REQUEST["prevoznoSredstvo"]=2;

        session()->set('sort',true);


        $results = $this->controller("App\Controllers\KorisnikController")->execute('pretragaPonuda');
        $this->assertTrue($results->see("Jagodina"));
    }
    public function testPretragaPonudaKorisnik4(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST['sortiranje']="opadajuceDatum";
        $_REQUEST["resetPage"]=null;
        $_REQUEST['page']=1;
        $_REQUEST['sort']=true;
        $_REQUEST["mestoDo"]=3;

        session()->set('sort',true);


        $results = $this->controller("App\Controllers\KorisnikController")->execute('pretragaPonuda');
        $this->assertTrue($results->see("Jagodina"));

    }
    public function testPretragaPonudaKorisnik5(){ 
        $korisnik = [
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
        ];
        session()->set('korisnik', (object)$korisnik);
        $_REQUEST["resetPage"]=true;
        $_REQUEST['page']=1;
        $_REQUEST['sort']=true;
        $_REQUEST["mestoOd"]=8;

        $results = $this->controller("App\Controllers\KorisnikController")->execute('pretragaPonuda');
        $this->assertTrue($results->see("Jagodina"));
    }
}