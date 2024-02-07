<?php

namespace App\Controllers;

use CodeIgniter\Database\Database;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;


class GostControllerTest extends CIUnitTestCase
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
    protected $seed     = 'Tests\Support\Database\Seeds\GostSeeder';
   protected $basePath = 'Tests\Support\Database';
    
    
    protected function setUp(): void
    {
        parent::setUp();
        
    }

    // protected function regressDatabase()
    // {
    //     $sql = file_get_contents(SUPPORTPATH. 'Database/' . 'testeputuj.sql');
    //     $this->db->query($sql);
    // }

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
        
        $results = $this->withURI("http://localhost:8080/GostController")->controller("App\Controllers\GostController")->execute('index');
        $this->assertTrue($results->see('Početna'));
    }

    public function testLoginPrivatnik(){ 
        
        $_REQUEST["username-input"]='milica.c';
        $_REQUEST['password-input']='Milica123#';

        $results = $this->controller("App\Controllers\GostController")->execute('loginSubmit');
        
        $this->assertTrue($results->see("Dobrodošli"));
        
    }

    public function testLoginObicanKorisnik(){ 
        $_REQUEST["username-input"]='trivic123';
        $_REQUEST['password-input']='Trivic123!';

        $results = $this->controller("App\Controllers\GostController")->execute('loginSubmit');
        $this->assertTrue($results->see("Točak"));
    }

    public function testLoginAdmin(){ 
        $_REQUEST["username-input"]='admin';
        $_REQUEST['password-input']='Admin123#';

        $results = $this->controller("App\Controllers\GostController")->execute('loginSubmit');
        $this->assertTrue($results->see("Potvrda"));
    }

    public function testNeispravnoKimeLogin(){ 
        $_REQUEST["username-input"]='admin111';
        $_REQUEST['password-input']='Admin123#';

        $results = $this->controller("App\Controllers\GostController")->execute('loginSubmit');
        $this->assertTrue($results->see('Neispravno'));
    }

    public function testNeispravnoaLozinkaLogin(){ 
        $_REQUEST["username-input"]='admin';
        $_REQUEST['password-input']='Admin123*';

        $results = $this->controller("App\Controllers\GostController")->execute('loginSubmit');
        $this->assertTrue($results->see('Neispravna'));
    }

    public function testPraznoPoljeLogin(){ 

        $_REQUEST["username-input"]='admin';
        $_REQUEST['password-input']=null;

        $results = $this->controller("App\Controllers\GostController")->execute('loginSubmit');
        $this->assertTrue($results->see('Popunjavanje'));
    }



    public function testUspesnaRegistracija(){ 
        $_REQUEST["firstname"]="Vanja";
        $_REQUEST["lastname"]="Curic";
        $_REQUEST["phonenumber"]= "065521832";
        $_REQUEST["email"]="pomocnieputuj2@outlook.com";
        $_REQUEST["choice"]="K";
        $_REQUEST["checkpassword"]="Vanja123#";
        $_REQUEST["password"]="Vanja123#";
        $_REQUEST["username"]="vanjica.carica";

        $results = $this->controller("App\Controllers\GostController")->execute('registracijaSubmit');

        $this->assertTrue($results->see("Pretraži ponudu"));

    }

    public function testRegistracijaPraznoPolje(){ 

        $_REQUEST["firstname"]="Vanja";
        $_REQUEST["lastname"]="Curic";
        $_REQUEST["phonenumber"]= "065521832";
        $_REQUEST["email"]="pomocnieputuj2@outlook.com";
        $_REQUEST["choice"]="K";
        $_REQUEST["checkpassword"]="Vanja123#";
        $_REQUEST["password"]="Vanja123#";
        $_REQUEST["username"]=null;

        $results = $this->controller("App\Controllers\GostController")->execute('registracijaSubmit');
        $this->assertTrue($results->see("Popunjavanje"));
    }

    public function testRegistracijaZauzetoKIme(){ 
        $_REQUEST["firstname"]="Vanja";
        $_REQUEST["lastname"]="Curic";
        $_REQUEST["phonenumber"]= "065521832";
        $_REQUEST["email"]="pomocnieputuj2@outlook.com";
        $_REQUEST["choice"]="K";
        $_REQUEST["checkpassword"]="Vanja123#";
        $_REQUEST["password"]="Vanja123#";
        $_REQUEST["username"]="milica.c";

        $results = $this->controller("App\Controllers\GostController")->execute('registracijaSubmit');
        $this->assertTrue($results->see("Korisničko"));
    }

    public function testRegistracijaLozinkaFormat(){ 
        $_REQUEST["firstname"]="Vanja";
        $_REQUEST["lastname"]="Curic";
        $_REQUEST["phonenumber"]= "065521832";
        $_REQUEST["email"]="pomocnieputuj2@outlook.com";
        $_REQUEST["choice"]="K";
        $_REQUEST["checkpassword"]="Vanja123";
        $_REQUEST["password"]="Vanja123";
        $_REQUEST["username"]="vanjica.carica";

        $results = $this->controller("App\Controllers\GostController")->execute('registracijaSubmit');
        $this->assertTrue($results->see("Lozinka mora"));
    }

    public function testRegistracijaEmailFormat(){ 
        $_REQUEST["firstname"]="Vanja";
        $_REQUEST["lastname"]="Curic";
        $_REQUEST["phonenumber"]= "065521832";
        $_REQUEST["email"]="pomocnieputuj";
        $_REQUEST["choice"]="K";
        $_REQUEST["checkpassword"]="Vanja123";
        $_REQUEST["password"]="Vanja123#";
        $_REQUEST["username"]="vanjica.carica";

        $results = $this->controller("App\Controllers\GostController")->execute('registracijaSubmit');
        $this->assertTrue($results->see("Email adresa u pogrešnom formatu."));
    }

    public function testRegistracijaPonovljenaLozinka(){
        $_REQUEST["firstname"]="Vanja";
        $_REQUEST["lastname"]="Curic";
        $_REQUEST["phonenumber"]= "065521832";
        $_REQUEST["email"]="pomocnieputuj2@outlook.com";
        $_REQUEST["choice"]="K";
        $_REQUEST["checkpassword"]="Vanja123";
        $_REQUEST["password"]="Vanja123#";
        $_REQUEST["username"]="vanjica.carica";

        $results = $this->controller("App\Controllers\GostController")->execute('registracijaSubmit');
        $this->assertTrue($results->see("Lozinka u polju"));
    }

    public function testZaboravljenaLozinka(){ 
        $_REQUEST['emailReset']="pomocniEPUTUJ2@outlook.com";
        $results = $this->controller("App\Controllers\GostController")->execute('zaboravljenaLozinkaSubmit');
        $this->assertTrue($results->see("Opcije za putovanja"));
    }
    public function testZaboravljenaLozinka2(){ 
        $results = $this->controller("App\Controllers\GostController")->execute('zaboravljenaLozinka');
        $this->assertFalse($results->see("Opcije za putovanja"));
    }

    public function testPregledPonude(){ 
        $_REQUEST["page"]=1;
        $results = $this->controller("App\Controllers\GostController")->execute('pregledPonuda');
        $this->assertTrue($results->see("Jagodina"));
      
    }
    public function testPrikazPonudeGost(){ 
        $_REQUEST["izabranaPonuda"]=37;
        $results = $this->controller("App\Controllers\GostController")->execute('prikazPonudeGost');
        $this->assertTrue($results->see("Jagodina"));
    }
    public function testPretragaPonuda1(){ 
        $_REQUEST['sortiranje']="rastuceCena";
        $_REQUEST["resetPage"]=null;
        $_REQUEST['sort']=true;
        $_REQUEST['page']=1;
        $_REQUEST["prevoznoSredstvo"]=2;

        $results = $this->controller("App\Controllers\GostController")->execute('pretragaPonuda');
        $this->assertTrue($results->see("Jagodina"));
        
    }
    public function testPretragaPonuda2(){ 
        $_REQUEST['sortiranje']="rastuceDatum";
        $_REQUEST["resetPage"]=null;
        $_REQUEST['sort']=true;
        $_REQUEST['page']=1;
        $_REQUEST["prevoznoSredstvo"]=2;

        $results = $this->controller("App\Controllers\GostController")->execute('pretragaPonuda');
        $this->assertTrue($results->see("Jagodina"));
    }
    public function testPretragaPonuda3(){ 
        $_REQUEST['sortiranje']="opadajuceCena";
        $_REQUEST["resetPage"]=null;
        $_REQUEST['sort']=true;
        $_REQUEST['page']=1;
        $_REQUEST["prevoznoSredstvo"]=2;

        session()->set('sort',true);


        $results = $this->controller("App\Controllers\GostController")->execute('pretragaPonuda');
        $this->assertTrue($results->see("Jagodina"));
    }
    public function testPretragaPonuda4(){ 
        $_REQUEST['sortiranje']="opadajuceDatum";
        $_REQUEST["resetPage"]=null;
        $_REQUEST['page']=1;
        $_REQUEST['sort']=true;
        $_REQUEST["mestoDo"]=3;

        session()->set('sort',true);


        $results = $this->controller("App\Controllers\GostController")->execute('pretragaPonuda');
        $this->assertTrue($results->see("Jagodina"));

    }
    public function testPretragaPonuda5(){ 
        $_REQUEST["resetPage"]=true;
        $_REQUEST['page']=1;
        $_REQUEST['sort']=true;
        $_REQUEST["mestoOd"]=8;

        $results = $this->controller("App\Controllers\GostController")->execute('pretragaPonuda');
        $this->assertTrue($results->see("Jagodina"));
    }

    public function testKomentar1(){ 
        $_REQUEST['stranica']='index';
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\GostController")->execute('komentar');
        $this->assertTrue($results->dontSee('Registruj se'));
    }
    public function testKomentar2(){ 
        $_REQUEST['stranica']='login';
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\GostController")->execute('komentar');
        $this->assertTrue($results->dontSee('ponuda'));
    }
    public function testKomentar3(){ 
        $_REQUEST['stranica']='registracija';
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\GostController")->execute('komentar');
        $this->assertTrue($results->dontSee('ponuda'));
    }
    public function testKomentar4(){ 
        $_REQUEST['stranica']="zaboravljenalozinka";
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\GostController")->execute('komentar');
        $this->assertTrue($results->dontSee('Registruj se'));
    }
    public function testKomentar5(){ 
        $_REQUEST['stranica']="prikazPonudeGost";
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\GostController")->execute('komentar');
        $this->assertTrue($results->dontSee('Registruj se'));
    }
    public function testKomentar6(){ 
        $_REQUEST['stranica']="pregledPonuda";
        $_REQUEST['ime']='Anja';

        $results = $this->controller("App\Controllers\GostController")->execute('komentar');
        $this->assertTrue($results->dontSee('Registruj se'));
    }

    public function testProsek(){ 
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
        $results = $this->controller("App\Controllers\GostController")->execute('prosek',(object)$ponuda);
        $this->assertEquals($results->getResult(),0);
    }
}