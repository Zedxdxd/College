<?php

namespace App\Controllers;

use CodeIgniter\Database\Database;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;


class AdminControllerTest extends CIUnitTestCase {
    use ControllerTestTrait;
    use DatabaseTestTrait;

    protected $DBGroup = 'tests';

    protected $migrate     = false;
    //protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'Tests\Support\Database';

    // For Seeds
    protected $seedOnce = false;
    protected $seed     = 'Tests\Support\Database\Seeds\AdminSeeder';
    protected $basePath = 'Tests\Support\Database';


    protected function setUp(): void {
        parent::setUp();
    }

    // protected function regressDatabase()
    // {
    //     $sql = file_get_contents(SUPPORTPATH. 'Database/' . 'testeputuj.sql');
    //     $this->db->query($sql);
    // }

    protected function tearDown(): void {
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

    public function testIndexPage() {

        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $results = $this->withURI("http://localhost:8080/AdminController")->controller("App\Controllers\AdminController")->execute('index');
        $this->assertTrue($results->see('Potvrda registracija'));
        $this->assertTrue($results->see('admin'));
        $this->assertTrue($results->see('trivic123'));
        $this->assertTrue($results->see('zeljko123'));
    }

    public function testDodajMestoPrikazForme(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);


        $results = $this->controller('App\Controllers\AdminController')->execute('dodajMesto');
        $this->assertTrue($results->see('Unesite naziv mesta'));
    }

    public function testDodajMestoPostoji() {
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['mesto'] = "Jagodina";

        $results = $this->controller('App\Controllers\AdminController')->execute('dodavanjeMesta');
        $this->assertTrue($results->see('Mesto već postoji!'));
    }

    public function testDodajMestoNijeUneto() {
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['mesto'] = "";
        $results = $this->controller('App\Controllers\AdminController')->execute('dodavanjeMesta');
        $this->assertTrue($results->see('Niste uneli naziv mesta!'));
    }

    public function testDodajMestoUspesno() {
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['mesto'] = "Beograd";
        $results = $this->controller('App\Controllers\AdminController')->execute('dodavanjeMesta');
        $this->assertTrue($results->see('Uspešno dodato mesto!'));
    }

    public function testLogoutAdmin() {
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['mesto'] = "Beograd";
        $results = $this->controller('App\Controllers\AdminController')->execute('logout');
        $this->assertTrue($results->see('Uloguj se'));
    }

    public function testPrikazNalogaZaPotvrduRegistracije(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor'] = "12";
        $results = $this->controller('App\Controllers\AdminController')->execute('potvrdiNalog');
        $this->assertTrue($results->see('trivic123'));
    }

    public function testPotvrdiKreiranjeObicanKorisnik(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor'] = "12";
        $results = $this->controller('App\Controllers\AdminController')->execute('potvrdiKreiranje');
        $podaci = [
            'SifK' => 12
        ];
        $this->seeInDatabase('obicankorisnik', $podaci);
    }

    public function testPotvrdiKreiranjePrivatnik(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor'] = "3";
        $results = $this->controller('App\Controllers\AdminController')->execute('potvrdiKreiranje');
        $podaci = [
            'SifK' => 3
        ];
        $this->seeInDatabase('privatnik', $podaci);
    }

    public function testOdbijGasenjeNaloga(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor'] = "12";
        $results = $this->controller('App\Controllers\AdminController')->execute('Odbij');
        $podaci = [
            'SifK' => 12,
            "TraziBrisanje" => 1
        ];
        $this->dontSeeInDatabase('korisnik', $podaci);
    }

    public function testUkloniNalogPrikaz(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor'] = "";
        $results = $this->controller('App\Controllers\AdminController')->execute('UkloniNalog');
        $this->assertTrue($results->see('milica.c'));
        $this->assertTrue($results->see('lanaIvk'));
    }

    public function testPotvrdiBrisanjePrikaz(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor'] = "2";
        $results = $this->controller('App\Controllers\AdminController')->execute('PotvrdiBrisanje');
        $this->assertTrue($results->see('lanaIvk'));
    }

    public function testObrisiObicanKorisnik(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor'] = "2";
        $results = $this->controller('App\Controllers\AdminController')->execute('Obrisi');
        $podaci = [
            'SifK' => 2,
        ];
        $this->assertTrue($results->see('milica.c'));
        $this->dontSeeInDatabase('korisnik', $podaci);
    }

    public function testObrisiPrivatnik(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor'] = "";
        $_REQUEST['izbor1'] = "3";
        $_REQUEST['r'] = "1";
        $results = $this->controller('App\Controllers\AdminController')->execute('Obrisi');
        $podaci = [
            'SifK' => 3,
        ];
        $this->assertTrue($results->see('lanaIvk'));
        $this->dontSeeInDatabase('korisnik', $podaci);
    }

    public function testDetaljiPrivatnikaPosleReporta(){
        $korisnik =[
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
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor1'] = "2";
        $_REQUEST['izbor2'] = "4";
        $_REQUEST['r'] = "1";
        $_REQUEST['izbor'] =null;
        $results = $this->controller('App\Controllers\AdminController')->execute('detaljiPrivatnikaPosleReporta');
        $this->assertTrue($results->see('Informacije o korisničkom nalogu'));
    }

    public function testReportDetalji(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor1'] = "4";
        $_REQUEST['izbor2'] = "2";
        $_REQUEST['r'] = "1";
        $results = $this->controller('App\Controllers\AdminController')->execute('reportDetalji');
        $this->assertTrue($results->see('Nije odrzana voznja.'));
    }

    public function testOdbijRegistraciju(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor1'] = "";
        $_REQUEST['izbor2'] = "";
        $_REQUEST['r'] = "";
        $_REQUEST['izbor'] = "10";
        $results = $this->controller('App\Controllers\AdminController')->execute('potvrdiOdbijanje');
        $podaci = [
            'SifK' => 10,
        ];
        $this->dontSeeInDatabase('korisnik', $podaci);
    }

    public function testPosaljiEmail(){
        $korisnik = [
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
            'ProfilnaSlika' => ''
        ];


        session()->set('korisnik', (object)$korisnik);

        $_REQUEST['izbor1'] = "4";
        $_REQUEST['izbor2'] = "2";
        $_REQUEST['r'] = "1";
        $results = $this->controller('App\Controllers\AdminController')->execute('posaljiEmail');
        $podaci = [
            'SifRep' => 1,
        ];
        $this->dontSeeInDatabase('report', $podaci);
    }
}
