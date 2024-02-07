<?php



namespace App\Controllers;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

/**
 * GostController - klasa za pretragu, logovanje i registraciju gosta
 * 
 * @version 1.0
 */

class GostController extends BaseController {

    /**
     * Parametrizovan prikaz stranice $stranica pomocu $podaci
     * 
     * @author Milica Cvetković 2020/0003 
     *
     * @param string $stranica stranica koja se prikazuje
     * @param array $podaci podaci kojima je stranica parametrizovana
     * 
     * @return void
     */
    private function prikaz($stranica, $podaci){
        echo view("sabloni/headergost");
        echo view($stranica, $podaci);
        echo view("sabloni/footer");
    }

    /**
     * Pocetna stranica sajta za korisnika tipa gost
     * @author Milica Cvetković 2020/0003 
     * @return void
     */
    public function index() {
        
        $svePonude = $this->dohvatiSvePonude();
        $svaMesta = $this->dohvatiSvaMesta();
        $svaPrevoznaSredstva = $this->dohvatiSvaPrevoznaSredstva();
        $this->prikaz("index", ["svePonude"=> $svePonude, "svaMesta" => $svaMesta, "svaPrevoznaSredstva" => $svaPrevoznaSredstva,"kontroler"=>"GostController","stranica"=>"index"]);
    }
    
    /**
     * Stranica za login korisnika
     * @author Milica Cvetković 2020/0003 
     * 
     * @param array $poruke
     * 
     * @return void
     */
    public function login($poruke = null){
        $this->prikaz("login.php", ["poruke" => $poruke,"kontroler"=>"GostController","stranica"=>"login"]);
    }
    
    /**
     * Submit forme za login i prijavljivanje korisnika
     * 
     * @author Milica Cvetković 2020/0003 
     * 
     * @return mixed
     */
    public function loginSubmit(){
        
        $korisnickoime = $this->request->getVar("username-input");
        $lozinka = $this->request->getVar("password-input");
        
        if($korisnickoime == null || $lozinka == null){
            $poruke['prazno'] = "Popunjavanje svih polja je obavezno.";
            return $this->login($poruke);
        }
        
        if($this->proveraKorisnickoIme($korisnickoime) == 0){
            $poruke['korisnickoime'] = "Neispravno korisničko ime.";
            return $this->login($poruke);
        }
        
        $korisnik = $this->dohvatiKorisnika($korisnickoime);
        
        if(!$this->registracijaOdobrena($korisnik)){
            return redirect()->to(site_url("GostController/index"));
        }
        
        if($this->proveraLozinka($korisnickoime, $lozinka) == 0){
             $poruke['lozinka'] = "Neispravna lozinka.";
            return $this->login($poruke);
        }
        
        $this->session->set("korisnik", $korisnik);
        if($korisnik->PrivatnikIliKorisnik == "K"){ 
            //return redirect()->to(site_url("KorisnikController/index"));
            $a=new KorisnikController();
            $a->index();
        }
         if($korisnik->PrivatnikIliKorisnik == "P"){ 
            //return redirect()->to(site_url("PrivatnikController/index"));
            $a=new PrivatnikController();
            $a->index();
         }
             
          if($korisnik->PrivatnikIliKorisnik == "A"){
            //return redirect()->to(site_url("AdminController/index"));
            $a=new AdminController();
            $a->index();
          }
    }

    /**
     * Stranica za registraciju korisnika
     * 
     * @author Milica Cvetković 2020/0003 
     * 
     * @param array $poruke
     * 
     * @return void
     */
    public function registracija($poruke = null){
        $this->prikaz("registracija.php", ["poruke" => $poruke,"kontroler"=>"GostController","stranica"=>"registracija"]);
    }

    /**
     * Submit forma za registraciju
     * @author Milica Cvetković 2020/0003 
     * 
     * @return void
     */
    
    public function registracijaSubmit(){
        
        $ime = $this->request->getVar("firstname");
        $prezime = $this->request->getVar("lastname");
        $brtel = $this->request->getVar("phonenumber");
        $email = $this->request->getVar("email");
        $tip = $this->request->getVar("choice");
        
        $korisnickoime = $this->request->getVar("username");
        $lozinka = $this->request->getVar("password");
        $ponovnalozinka = $this->request->getVar("checkpassword");
        
        if($ime == null || $prezime == null || $brtel == null || $email == null || $tip == null
                || $korisnickoime == null || $lozinka == null || $ponovnalozinka == null){
            $poruke['prazno'] = "Popunjavanje svih polja je obavezno.";
            return $this->registracija($poruke);
        }
       
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $poruke['email'] = "Email adresa u pogrešnom formatu.";
            return $this->registracija($poruke);
        }
        
        if($this->proveraKorisnickoIme($korisnickoime) != 0){
            $poruke['korisnickoime'] = "Korisničko ime je zauzeto.";
            return $this->registracija($poruke);
        }
       
        $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,14}$/";
        if(preg_match($regex, $lozinka) == 0){
            $poruke['lozinka'] = "Lozinka mora da sadrži jedno malo slovo, jedno veliko slovo, jedan specijalan karakter, jednu cifru i da je dužine od 8 do 14 karaktera";
            return $this->registracija($poruke);
        }
        
        if($lozinka != $ponovnalozinka){
            $poruke['ponovna'] = "Lozinka u polju potvrde nije ista kao prva unesena.";
            return $this->registracija($poruke);
        }
        
        $this->sacuvajKorisnika($ime, $prezime, $brtel, $email, $korisnickoime, $lozinka, $tip);
        //redirect()->to(site_url("GostController/index"));
        $this->index();
    }
    
    /**
     * Stranica za povratak zaboravljene lozinke
     * 
     * @return void
     */
    public function zaboravljenalozinka(){
        $this->prikaz("zaboravljenalozinka", ["kontroler"=>"GostController","stranica"=>"zaboravljenalozinka"]);
    }
    
    /**
     * Stranica submita forme za izmenu lozinke
     * @author Milica Cvetković 2020/0003 
     * @return void
     */
    public function zaboravljenaLozinkaSubmit(){
        
        
        $transport=new EsmtpTransport("smtp-mail.outlook.com",587);
        $transport->setUsername("sideeyetim@outlook.com");
        $transport->setPassword("RADIMAIL123");
        $mailer=new Mailer($transport);
        $email = (new Email())->from("sideeyetim@outlook.com")->to($this->request->getVar('emailReset'))
        ->subject('Zahtev za reset lozinke')->text('Vaša nova lozinka je '.rand());
        
        $mailer->send($email);
        
        return $this->index();
    }

    /**
     * Stranica za pregled postavljenih ponuda
     * @author Milica Cvetković 2020/0003 
     * 
     * @return mixed
     */
    public function pregledPonuda(){
        
        $totalPages = count($this->dohvatiSvePonude());
        
        $page = $this->request->getVar("page") != null ? $this->request->getVar("page"): 1;
        
        $numOfResultsOnPage = 9;
        
        $ponude = $this->dohvatiSvePonudeLimit($page, $numOfResultsOnPage);
        $svePonude = $this->dohvatiSvePonude();
        $svaMesta = $this->dohvatiSvaMesta();
        $svaPrevoznaSredstva = $this->dohvatiSvaPrevoznaSredstva();
        return $this->prikaz("pregledPonuda", ["ponude" => $ponude, "svePonude" => $svePonude,"svaMesta" => $svaMesta, "svaPrevoznaSredstva" => $svaPrevoznaSredstva, "page"=> $page, "numOfResultsOnPage" => $numOfResultsOnPage, "totalPages" => $totalPages,"kontroler"=>"GostController","stranica"=>"pregledPonuda"]);
    }
   
    /**
     * Pretraga i filtriranje/sortiranje ponuda po izabranim kriterijumima,prikazuju se sve ponude koje
     * zadovoljavaju kriterijum
     * 
     * @author Milica Cvetković 2020/0003 
     * 
     * @return mixed
     */
    public function pretragaPonuda(){
        
        $resetPage = $this->request->getVar("resetPage");
        $sortiranje = $this->request->getVar("sortiranje");
        
        if($sortiranje != null){
            $this->session->set("sortiranje", $sortiranje);
        }
        
        if($resetPage != null || $sortiranje != null){
            $page = 1;
        }
        else{
            $page = $this->request->getVar("page") != null ? $this->request->getVar("page"): 1;
        }

        $numOfResultsOnPage = 9;
                
        if($resetPage != null){
            
            $prevoznoSredstvo = $this->request->getVar("prevoznoSredstvo");
            $mestoOd = $this->request->getVar("mestoOd");
            $mestoDo = $this->request->getVar("mestoDo");
            $minimalnaCena = $this->request->getVar("minimalnaCena");
            $maksimalnaCena = $this->request->getVar("maksimalnaCena");
            $brojPutnika = $this->request->getVar("brojPutnika");
            $datumOd = $this->request->getVar("datumOd");
            $datumDo = $this->request->getVar("datumDo");
            $vremeOd = $this->request->getVar("vremeOd");
            $vremeDo = $this->request->getVar("vremeDo");
           
            $this->session->set("prevoznoSredstvo", $prevoznoSredstvo);
            $this->session->set("mestoOd", $mestoOd);
            $this->session->set("mestoDo", $mestoDo);
            $this->session->set("minimalnaCena", $minimalnaCena);
            $this->session->set("maksimalnaCena", $maksimalnaCena);
            $this->session->set("brojPutnika", $brojPutnika);
            $this->session->set("datumOd", $datumOd);
            $this->session->set("datumDo", $datumDo);
            $this->session->set("vremeOd", $vremeOd);
            $this->session->set("vremeDo", $vremeDo);
            
            $this->session->set("sort", null);
            $this->session->set("sortiranje", null);
        }
        
        $prevoznoSredstvo =$this->session->get("prevoznoSredstvo" );
        $mestoOd = $this->session->get("mestoOd");
        $mestoDo= $this->session->get("mestoDo");
        $minimalnaCena =$this->session->get("minimalnaCena");
        $maksimalnaCena = $this->session->get("maksimalnaCena");
        $brojPutnika = $this->session->get("brojPutnika");
        $datumOd = $this->session->get("datumOd");
        $datumDo = $this->session->get("datumDo");
        $vremeOd = $this->session->get("vremeOd");
        $vremeDo = $this->session->get("vremeDo");
        
        $sortiranje = $this->session->get("sortiranje");
        
        $rastuceCena = null;
        $rastuceDatum = null;
        $opadajuceCena = null;
        $opadajuceDatum = null;
        
        switch($sortiranje){
            case "rastuceCena":
                $rastuceCena = $sortiranje;
                break;
            case "rastuceDatum":
                $rastuceDatum = $sortiranje;
                break;
            case "opadajuceCena":
                $opadajuceCena = $sortiranje;
                break;
            case "opadajuceDatum":
                $opadajuceDatum = $sortiranje;
                break;
            default:
                break;
        }
        
        $svePonude = $this->dohvatiSvePonude();
        $svaMesta = $this->dohvatiSvaMesta();
        $svaPrevoznaSredstva = $this->dohvatiSvaPrevoznaSredstva();
        
        $sort = $this->request->getVar("sort");
        
        if($sort != null || $this->session->get("sort") != null){
            $temp = $this->session->get("sort");
            $this->session->set("sort", $sort);
            $ponude = $this->pretragaSort($prevoznoSredstvo, $mestoOd, $mestoDo, $minimalnaCena, $maksimalnaCena, $brojPutnika, $datumOd, $datumDo, $vremeOd, $vremeDo, $page, $numOfResultsOnPage, $rastuceCena, $rastuceDatum, $opadajuceCena, $opadajuceDatum);
        }else{
            $ponude = $this->pretraga($prevoznoSredstvo, $mestoOd, $mestoDo, $minimalnaCena, $maksimalnaCena, $brojPutnika, $datumOd, $datumDo, $vremeOd, $vremeDo, $page, $numOfResultsOnPage);
        }
        $totalPages = count($this->pretraga($prevoznoSredstvo, $mestoOd, $mestoDo, $minimalnaCena, $maksimalnaCena, $brojPutnika, $datumOd, $datumDo, $vremeOd, $vremeDo, $page, $numOfResultsOnPage));
        return $this->prikaz("pregledPonuda", ["ponude" => $ponude, "svePonude" => $svePonude, "svaMesta" => $svaMesta, "svaPrevoznaSredstva" => $svaPrevoznaSredstva, "page"=> $page, "numOfResultsOnPage" => $numOfResultsOnPage, "totalPages" => $totalPages, "submitted" => "true","kontroler"=>"GostController","stranica"=>"pregledPonuda"]);
                
    }

    /**
     * Prikaz jedne izabrane ponude
     * @author Milica Cvetković 2020/0003 
     * 
     * @return void
     */
    public function prikazPonudeGost(){
        $ponuda = $this->request->getVar("izabranaPonuda");
        $ponuda = $this->dohvatiPonudu($ponuda);
        $prosek = $this->prosek($ponuda);
        $this->prikaz("prikazPonudeGost", ["ponuda"=>$ponuda, "prosek" => $prosek,"kontroler"=>"GostController","stranica"=>"prikazPonudeGost"]);
    }
    
    /**
     * Dohvatanje korisnika iz baze
     * @author Milica Cvetković 2020/0003 
     * 
     * @param string $korisnickoime
     * 
     * @return object
     */
    public function dohvatiKorisnika($korisnickoime){
        
        $db      = \Config\Database::connect();
        $builder = $db->table('korisnik');
        
        $builder->where("KorisnickoIme", $korisnickoime);
        
        return $builder->get()->getResult()[0];
        
    }
    
    /**
     * Cuvanje korisnika u bazu
     * @author Milica Cvetković 2020/0003 
     * 
     * @param string $ime
     * @param string $prezime
     * @param string $brtel
     * @param string $email
     * @param string $korisnickoime
     * @param string $lozinka
     * @param string $tip
     * 
     * @return void
     */
    public function sacuvajKorisnika($ime, $prezime, $brtel, $email, $korisnickoime, $lozinka, $tip){
        
        $db      = \Config\Database::connect();
        $builder = $db->table('korisnik');
        
        $data = [
          "KorisnickoIme" => $korisnickoime,
          "Lozinka" => $lozinka,
          "Ime" => $ime,
          "Prezime" => $prezime,
          "Email" => $email,
          "BrTel" => $brtel,
          "TraziBrisanje" => 0,
          "PrivatnikIliKorisnik" => $tip,
          "Novac" => 0
        ];
        
        $builder->insert($data);
        
    }
    
    /**
     * Provera da li korisnicko ime postoji u bazi
     * @author Milica Cvetković 2020/0003 
     * 
     * @param string $korisnickoIme
     * 
     * @return int
     */
    public function proveraKorisnickoIme($korisnickoIme){
        
        $db      = \Config\Database::connect();
        $builder = $db->table('korisnik');
        
        $builder->where("KorisnickoIme", $korisnickoIme);
        
        $count = $builder->get()->getResult();
        
        return count($count);
        
    }
    
    /**
     * Provera da li se lozinka poklapa sa zadatim korisnickim imenom u bazi
     * @author Milica Cvetković 2020/0003 
     * 
     * @param string $korisnickoIme
     * @param string $lozinka
     * 
     * @return int
     */
    public function proveraLozinka($korisnickoIme, $lozinka){
        
        $db      = \Config\Database::connect();
        $builder = $db->table('korisnik');
        
         $builder->where("KorisnickoIme", $korisnickoIme);
         $builder->where("Lozinka", $lozinka);
         
         $count = $builder->get()->getResult();
        
        return count($count);
        
    }
    
    /**
     * Dohvatanje zadate ponude iz baze
     * @author Milica Cvetković 2020/0003 
     * 
     * @param int $ponuda
     * 
     * @return mixed
     */
    public function dohvatiPonudu($ponuda){
        
        $db      = \Config\Database::connect();
        $builder = $db->table('ponuda');
        
        $builder->select("ponuda.SifP as SifP, mOd.Naziv as MestoOd, mDo.Naziv as MestoDo, ponuda.DatumOd as DatumOd, ponuda.DatumDo as DatumDo, ponuda.VremeOd as VremeOd, ponuda.VremeDo as VremeDo, ponuda.BrMesta as BrMesta, ponuda.CenaKarte as CenaKarte, prevoznosredstvo.Naziv as prevoznoSredstvo, ponuda.Slika as Slika, P.Naziv as NazivPretplate, ponuda.SifK as SifK, korisnik.Ime as Ime, korisnik.Prezime as Prezime, korisnik.KorisnickoIme as KorisnickoIme");
        $builder->join("mesto as mOd", "mOd.SifM = ponuda.SifMesOd");
        $builder->join("mesto as mDo", "mDo.SifM = ponuda.SifMesDo");
        $builder->join("prevoznosredstvo", "prevoznosredstvo.SifSred = ponuda.SifSred");
        $builder->join("korisnik", "korisnik.SifK = ponuda.SifK");
        $builder->join("privatnik", "privatnik.SifK = korisnik.SifK");
        $builder->join("pretplata as P", "P.SifPret = privatnik.SifPret");
        $builder->where("SifP", $ponuda);
        
        return $builder->get()->getResult()[0];
        
    }
    
    /**
     * Pretraga ponuda po zadatim kriterijumima
     * @author Milica Cvetković 2020/0003 
     * 
     * @param string $prevoznoSredstvo
     * @param string $mestoOd
     * @param string $mestoDo
     * @param int $minimalnaCena
     * @param int $maksimalnaCena
     * @param int $brojPutnika
     * @param date $datumOd
     * @param date $datumDo
     * @param time $vremeOd
     * @param time $vremeDo
     * @param int $page
     * @param int $numOfResultsOnPage
     * 
     * @return array
     */
    public function pretraga($prevoznoSredstvo, $mestoOd, $mestoDo, $minimalnaCena, $maksimalnaCena, $brojPutnika, $datumOd, $datumDo, $vremeOd, $vremeDo, $page, $numOfResultsOnPage){
        
        $db      = \Config\Database::connect();
        $builder = $db->table('ponuda');
        
        $builder->select("ponuda.SifP as SifP, mOd.Naziv as MestoOd, mDo.Naziv as MestoDo, ponuda.DatumOd as DatumOd, ponuda.DatumDo as DatumDo, ponuda.VremeOd as VremeOd, ponuda.VremeDo as VremeDo, ponuda.BrMesta as BrMesta, ponuda.CenaKarte as CenaKarte, prevoznosredstvo.Naziv as prevoznoSredstvo, ponuda.Slika as Slika, P.Naziv as NazivPretplate, korisnik.Ime as Ime, korisnik.Prezime as Prezime, korisnik.KorisnickoIme as Korisnik");
        $builder->join("mesto as mOd", "mOd.SifM = ponuda.SifMesOd");
        $builder->join("mesto as mDo", "mDo.SifM = ponuda.SifMesDo");
        $builder->join("prevoznosredstvo", "prevoznosredstvo.SifSred = ponuda.SifSred");
        $builder->join("korisnik", "korisnik.SifK = ponuda.SifK");
        $builder->join("privatnik", "privatnik.SifK = korisnik.SifK");
        $builder->join("pretplata as P", "P.SifPret = privatnik.SifPret");
        
        $zahtevponuda = $db->table('zahtevponuda')->select('SifP');
        $builder->whereNotIn("SifP", $zahtevponuda);
        
        if($prevoznoSredstvo != null)
            $builder->like("prevoznosredstvo.Naziv", $prevoznoSredstvo);
        if($mestoOd != null)
            $builder->like("mOd.Naziv" , $mestoOd);
        if($mestoDo != null)
            $builder->like("mDo.Naziv", $mestoDo);
        if($minimalnaCena != null)
            $builder->where("ponuda.CenaKarte >=", $minimalnaCena);
        if($maksimalnaCena != null)
            $builder->where("CenaKarte <=", (float)$maksimalnaCena);
        if($brojPutnika != null)
            $builder->where ("ponuda.BrMesta <=", $brojPutnika);
        if($datumOd != null)
            $builder->where("ponuda.DatumOd >=", $datumOd);
        if($datumDo != null)
            $builder->where("ponuda.DatumDo <=", $datumDo);
        if($vremeOd != null)
            $builder->where("ponuda.VremeOd >=", $vremeOd);
        if($vremeDo != null)
            $builder->where("ponuda.VremeDo <=", $vremeDo);
        
        $builder->orderBy("NazivPretplate", "asc");
        
        $start = ($page - 1) * $numOfResultsOnPage;
        
        $builder->limit($start, $numOfResultsOnPage);
        
        return $builder->get()->getResult();
        
    }
    
    /**
     * Pretraga ponuda po zadatim kriterijumima i izvrseno sortiranje
     * @author Milica Cvetković 2020/0003 
     * 
     * @param string $prevoznoSredstvo
     * @param string $mestoOd
     * @param string $mestoDo
     * @param int $minimalnaCena
     * @param int $maksimalnaCena
     * @param int $brojPutnika
     * @param date $datumOd
     * @param date $datumDo
     * @param time $vremeOd
     * @param time $vremeDo
     * @param int $page
     * @param int $numOfResultsOnPage
     * @param mixed $rastuceCena
     * @param mixed $rastuceDatum
     * @param mixed $opadajuceCena
     * @param mixed $opadajuceDatum
     * @return type
     */
    public function pretragaSort($prevoznoSredstvo, $mestoOd, $mestoDo, $minimalnaCena, $maksimalnaCena, $brojPutnika, $datumOd, $datumDo, $vremeOd, $vremeDo, $page, $numOfResultsOnPage, $rastuceCena, $rastuceDatum, $opadajuceCena, $opadajuceDatum){
        
        $db      = \Config\Database::connect();
        $builder = $db->table('ponuda');
        
        $builder->select("ponuda.SifP as SifP, mOd.Naziv as MestoOd, mDo.Naziv as MestoDo, ponuda.DatumOd as DatumOd, ponuda.DatumDo as DatumDo, ponuda.BrMesta as BrMesta, ponuda.CenaKarte as CenaKarte, prevoznosredstvo.Naziv as prevoznoSredstvo,ponuda.Slika as Slika, korisnik.Ime as Ime, korisnik.Prezime as Prezime, korisnik.KorisnickoIme as Korisnik, ponuda.SifK as SifK");
        $builder->join("mesto as mOd", "mOd.SifM = ponuda.SifMesOd");
        $builder->join("mesto as mDo", "mDo.SifM = ponuda.SifMesDo");
        $builder->join("prevoznosredstvo", "prevoznosredstvo.SifSred = ponuda.SifSred");
        $builder->join("korisnik", "korisnik.SifK = ponuda.SifK");
        
        $zahtevponuda = $db->table('zahtevponuda')->select('SifP');
        $builder->whereNotIn("SifP", $zahtevponuda);
        
        if($rastuceCena != null)
            $builder->orderBy("ponuda.CenaKarte", "asc");
        if($rastuceDatum != null)
            $builder->orderBy("ponuda.DatumOd", "asc");
        if($opadajuceCena != null)
            $builder->orderBy("ponuda.CenaKarte", "desc");
        if($opadajuceDatum != null)
            $builder->orderBy("ponuda.DatumOd", "desc");
       
        if($prevoznoSredstvo != null)
            $builder->like("prevoznosredstvo.Naziv", $prevoznoSredstvo);
        if($mestoOd != null)
            $builder->like("mOd.Naziv" , $mestoOd);
        if($mestoDo != null)
            $builder->like("mDo.Naziv", $mestoDo);
        if($minimalnaCena != null)
            $builder->where("ponuda.CenaKarte >=", $minimalnaCena);
        if($maksimalnaCena != null)
            $builder->where("CenaKarte <=", (float)$maksimalnaCena);
        if($brojPutnika != null)
            $builder->where ("ponuda.BrMesta <=", $brojPutnika);
        if($datumOd != null)
            $builder->where("ponuda.DatumOd >=", $datumOd);
        if($datumDo != null)
            $builder->where("ponuda.DatumDo <=", $datumDo);
        if($vremeOd != null)
            $builder->where("ponuda.VremeOd >=", $vremeOd);
        if($vremeDo != null)
            $builder->where("ponuda.VremeDo <=", $vremeDo);
                    
        $start = ($page - 1) * $numOfResultsOnPage;
        
        $builder->limit($start, $numOfResultsOnPage);
        
        return $builder->get()->getResult();
        
    }
    
    /**
     * Provera da li privatnik ima odgovarajucu pretplatu
     * @author Milica Cvetković 2020/0003 
     * 
     * @param int $SifK
     * 
     * @return boolean
     */
    public function proveriPretplatu($SifK){
        
        $db      = \Config\Database::connect();
        $builder = $db->table('privatnik');
        
        $builder->where("SifK", $SifK);
        $result = $builder->get()->getResult()[0];
        
        $pretplata = $result->SifPret;
        
        $builder = $db->table('pretplata');
        
        $builder->where("SifPret", $pretplata);
        $result = $builder->get()->getResult()[0];
        
        $pretplata = $result->Naziv;
        
        if($pretplata == "Premium"){
            return true;
        }
        
        return false;
    }
    
    /**
     * Dohvatanje svih ponuda iz baze
     * @author Milica Cvetković 2020/0003 
     * 
     * @return array
     */
    public function dohvatiSvePonude(){
        
        $db      = \Config\Database::connect();
        $builder = $db->table('ponuda');
        
        $builder->select("mOd.Naziv as MestoOd, mDo.Naziv as MestoDo, ponuda.DatumOd as DatumOd, ponuda.DatumDo as DatumDo, ponuda.BrMesta as BrMesta, ponuda.CenaKarte as CenaKarte, prevoznosredstvo.Naziv as prevoznoSredstvo");
        $builder->join("mesto as mOd", "mOd.SifM = ponuda.SifMesOd");
        $builder->join("mesto as mDo", "mDo.SifM = ponuda.SifMesDo");
        $builder->join("prevoznosredstvo", "prevoznosredstvo.SifSred = ponuda.SifSred");
        $builder->join("korisnik", "korisnik.SifK = ponuda.SifK");
        
        return $builder->get()->getResult();
        
    }
    
    /**
     * Dohvatanje svih ponuda sa limitom zbog paginacije
     * @author Milica Cvetković 2020/0003 
     * 
     * @param int $page
     * @param int $numOfResultsOnPage
     * 
     * @return array
     */
    public function dohvatiSvePonudeLimit($page, $numOfResultsOnPage){
        
        $db      = \Config\Database::connect();
        $builder = $db->table('ponuda');
        
        $builder->select("mOd.Naziv as MestoOd, mDo.Naziv as MestoDo, ponuda.DatumOd as DatumOd, ponuda.DatumDo as DatumDo, ponuda.BrMesta as BrMesta, ponuda.CenaKarte as CenaKarte, ponuda.SifP as SifP,ponuda.Slika as Slika, prevoznosredstvo.Naziv as prevoznoSredstvo");
        $builder->join("mesto as mOd", "mOd.SifM = ponuda.SifMesOd");
        $builder->join("mesto as mDo", "mDo.SifM = ponuda.SifMesDo");
        $builder->join("prevoznosredstvo", "prevoznosredstvo.SifSred = ponuda.SifSred");
        $builder->join("korisnik", "korisnik.SifK = ponuda.SifK");
        
        $start = ($page - 1) * $numOfResultsOnPage;
        
        $builder->limit($start, $numOfResultsOnPage);
        return $builder->get()->getResult();
        
    }
    
    /**
     * Provera da li je registracija odobrena
     * @author Milica Cvetković 2020/0003 
     * 
     * @param mixed $korisnik
     * 
     * @return boolean
     */
    public function registracijaOdobrena($korisnik){
        
        $db      = \Config\Database::connect();
        
        if($korisnik->PrivatnikIliKorisnik == "K"){
            $builder = $db->table('obicankorisnik');
            $builder->where("SifK", $korisnik->SifK);
        }
        else if ($korisnik->PrivatnikIliKorisnik == "P"){
            $builder = $db->table('privatnik');
            $builder->where("SifK", $korisnik->SifK);
        }
        else if ($korisnik->PrivatnikIliKorisnik == "A"){
            $builder = $db->table('admin');
            $builder->where("SifK", $korisnik->SifK);
        }
        else{
            return false;
        }
        
        $count = $builder->get()->getResult();
        $count = count($count);
        
        return ($count!=0);
        
    }
    
    /**
     * @author Željko Urošević 2020/0073
     * 
     * Izracunavanje proseka ocena
     * 
     * @param mixed $ponuda
     * 
     * @return double
     */
    public function prosek($ponuda){
        
        $db      = \Config\Database::connect();
        $builder = $db->table("ocena");
        $ocene = $builder->where("SifPriv", $ponuda->SifK)->get()->getResult();
        $broj = 0;
        $suma = 0;
        foreach($ocene as $ocena){
            $suma += $ocena->Ocena;
            $broj++;
        }
        if ($broj == 0) {
            $broj = 1;
        }
        $prosek = $suma * 1.0 / $broj;
        return $prosek;
    }
    
    /**
     * Dohvatanje svih prevoznih sredstava iz baze
     * @author Milica Cvetković 2020/0003 
     * @return array
     */
    public function dohvatiSvaPrevoznaSredstva(){
        
        $db      = \Config\Database::connect();
        $builder = $db->table("prevoznosredstvo");
        
        $builder->select("*");
        
        return $builder->get()->getResult();
        
    }
    
    /**
     * Dohvatanje svih mesta iz baze
     * @author Milica Cvetković 2020/0003 
     * 
     * @return array
     */
    public function dohvatiSvaMesta(){
        
        $db      = \Config\Database::connect();
        $builder = $db->table("mesto");
        
        $builder->select("*");
        
        return $builder->get()->getResult();
        
    }
    /**
     * Komentari,koji se nalaze u footer-u stranice.Realizacija slanja komentara putem mail-a.
     * @author Anja Curic 2020/0513 
     * 
     * @return void
     */
    public function komentar(){ 
        $transport=new EsmtpTransport("smtp-mail.outlook.com",587);
        $transport->setUsername("pomocniEPUTUJ1@outlook.com");
        $transport->setPassword("RADIMAIL123");
        $mailer=new Mailer($transport);
        $email = (new Email())->from("pomocniEPUTUJ1@outlook.com")->to('sideeyetim@outlook.com')
        ->subject('Novi komentar')->text('Ime:'.$this->request->getVar('ime').'
Komentar:'.$this->request->getVar('komentar').'
                ');

        
        $mailer->send($email);

        $stranica=$this->request->getVar("stranica");
        
        
        if($stranica=="login")$this->login();
        else if($stranica=="registracija")$this->registracija();
        else if($stranica=="zaboravljenalozinka")$this->zaboravljenalozinka();
        else if($stranica=="index")$this->index();
        else if($stranica=="prikazPonudeGost")$this->pretragaPonuda();
        else if($stranica=="pregledPonuda")$this->pretragaPonuda();
    }
    
}