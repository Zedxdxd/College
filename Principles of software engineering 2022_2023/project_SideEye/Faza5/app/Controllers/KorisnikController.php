<?php

namespace App\Controllers;

use App\Models\ModelPoruka;
use App\Models\ModelObicanKorisnikLana;

use PhpParser\Node\Stmt\Echo_;

use App\Models\ModelKorisnik;
use App\Models\ModelVanrednaPonuda;
use App\Models\ModelPonuda;
use App\Models\ModelMesto;
use App\Models\ModelSredstvo;

use  App\Controllers\GostController;


use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

/**
 * KorisnikController - klasa za pretragu,slanje zahteva za ponudu,rezervacije i kupovinu karte za putovanja
 * 
 * @version 1.0
 */


class KorisnikController extends BaseController {
    /**
     * Parametrizovan prikaz stranice $stranica pomocu $podaci
     * 
     * @author Lana Ivkovic 2020/0513
     *
     * @param string $stranica stranica koja se prikazuje
     * @param array $podaci podaci kojima je stranica parametrizovana
     * 
     * @return void
     */
    private function prikaz($stranica, $podaci) {
        $SifK = session()->get("korisnik")->SifK;
        $model = new ModelPoruka();
        $br = $model->select("count(*) as br")->where("SifKor", $SifK)->where("SmerPoruke", 2)->findAll()[0]->br;
        echo view("sabloni/headerkorisnik", ["brPoruka" => $br]);
        echo view($stranica, $podaci);
        echo view("sabloni/footer");
    }
    /**
     * Prikazivanje glavne starnice korisnika
     * @author  Lana Ivkovic 2020/0480
     * 
     * @return void
     */

    public function index() {
        $svePonude = $this->dohvatiSvePonude();
        $svaMesta = $this->dohvatiSvaMesta();
        $svaPrevoznaSredstva = $this->dohvatiSvaPrevoznaSredstva();
        $this->prikaz("indexkorisnik", ["svePonude" => $svePonude, "svaMesta" => $svaMesta, "svaPrevoznaSredstva" => $svaPrevoznaSredstva, "kontroler" => "GostController", "stranica" => "indexkorisnik"]);
    }

    /** 
     * Inbox korisnika
     * 
     *  @author  Anja Curic 2020/0513
     * 
     * @return void
     */


    public function inboxKorisnik() {
        $Kime = session()->get("korisnik")->KorisnickoIme;
        $model = new ModelPoruka();
        $modelK = new ModelKorisnik();
        $modelP = new ModelPonuda();
        $modelM = new ModelMesto();

        $korisnik = $modelK->where("KorisnickoIme", $Kime)->findAll()[0];

        $poruke = $model->where("SifKor", $korisnik->SifK)->where("SmerPoruke", 2)->findAll();
        foreach ($poruke as $poruka) {
            $ponuda = $modelP->where("SifP", $poruka->SifPonuda)->findAll()[0];
            $poruka->mestoOd = $modelM->where("SifM", $ponuda->SifMesOd)->findAll()[0];
            $poruka->korisnik = $modelK->where("SifK", $poruka->SifPriv)->findAll()[0]->KorisnickoIme;
        }
        $this->prikaz("inboxKorisnik", ["poruke" => $poruke, "kontroler" => "KorisnikController", "stranica" => "inboxKorisnik"]);
    }


    /**
     * Brisanje poruke iz inboxa
     * @author  Anja Curic 2020/0513
     * @return void
     */
    public function obrisiPoruku() {
        $SifP = $this->request->getVar("SifP");
        $SifK = session()->get("korisnik")->SifK;

        $model = new ModelPoruka();

        $SifPor = $model->where("SifKor", $SifK)->where("SifPonuda", $SifP)->findAll()[0]->SifPor;
        $model->delete($SifPor);
        /* $db      = \Config\Database::connect();
        $builder = $db->table('rezervacija');

        $data = ["SifK"=>$SifK,"SifP"=>$SifP,"DatumRezervacije"=>];
        
        $builder->insert($data);//dodavanje rezervacije*/
        $this->inboxKorisnik();
        return;
    }


    /** 
     * Inbox korisnika prikaz izabrane poruke.
     * @author  Anja Curic 2020/0513
     * @return void
     */
    public function inboxKorisnikPoruka() {
        $izbor = $this->request->getVar("poruka");
        $Kime = session()->get("korisnik")->KorisnickoIme;
        $model = new ModelPoruka();
        $modelK = new ModelKorisnik();
        $modelZ = new ModelVanrednaPonuda();
        $modelP = new ModelPonuda();
        $modelM = new ModelMesto();
        $modelS = new ModelSredstvo();

        $korisnik = $modelK->where("KorisnickoIme", $Kime)->findAll()[0];

        $poruke = $model->where("SifKor", $korisnik->SifK)->where("SmerPoruke", 2)->findAll();
        foreach ($poruke as $poruka) {
            $ponuda = $modelP->where("SifP", $poruka->SifPonuda)->findAll()[0];
            $poruka->mestoOd = $modelM->where("SifM", $ponuda->SifMesOd)->findAll()[0];
            $poruka->korisnik = $modelK->where("SifK", $poruka->SifPriv)->findAll()[0]->KorisnickoIme;
        }

        $izbor = $model->where("SifPor", $izbor)->findAll()[0];
        $odabrana = $modelP->where("SifP", $izbor->SifPonuda)->findAll()[0];
        $odabrana->RokZaOtkazivanje = $modelZ->where("SifP", $izbor->SifPonuda)->findAll()[0]->RokZaOtkazivanje;
        $odabrana->mestoOd = $modelM->where("SifM", $odabrana->SifMesOd)->findAll()[0];
        $odabrana->mestoDo = $modelM->where("SifM", $odabrana->SifMesDo)->findAll()[0];
        $odabrana->sredstvo = $modelS->where("SifSred", $odabrana->SifSred)->findAll()[0]->Naziv;
        $this->prikaz("inboxKorisnik", ["poruke" => $poruke, "odabrana" => $odabrana, "kontroler" => "KorisnikController", "stranica" => "inboxKorisnik"]);
    }


    /**
     * Izmena profila korisnika
     * @author  Lana Ivkovic 2020/0480
     * @return void
     */
    public function izmenaProfila() {
        $data = [];
        $db = db_connect();
        $model = new ModelObicanKorisnikLana($db);
        $SifK =  session()->get("korisnik")->SifK;
        //$SifK = "2";
        $poruka = "";
        if ($this->request->getMethod() == 'post') {
            if ($_POST) {
                $dugme = $_POST['dugme'];
                if ($dugme == "Sačuvaj") {
                    $data['poruka'] = "post";
                    $ime = $_POST['ime'];
                    $prezime = $_POST['prezime'];
                    $lozinka = $_POST['lozinka'];
                    $email = $_POST['email'];
                    $brTel = $_POST['brTel'];
                    $ponovljena = $_POST['ponovljena'];

                    $profilna = null;
                    $imeSlike = null;
                    if (is_uploaded_file($_FILES['slika']['tmp_name'])) {
                        // cuvanje slike na serveru
                        $destinacioniFolder = FCPATH . "images\profilne\\";
                        $imeSlike = $SifK . "_" . date("YmdHis") . "_" . basename($_FILES['slika']['name']);
                        $destinacioniFajl = $destinacioniFolder . $imeSlike;
                        if (!move_uploaded_file($_FILES['slika']['tmp_name'], $destinacioniFajl)) {
                            $poruka = "Nije uspelo ubacivanje slike";
                        }
                    }
                    $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,14}$/";

                    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $poruka = "Email adresa u pogrešnom formatu.";
                    } else if (!empty($lozinka) && preg_match($regex, $lozinka) == 0) {
                        $poruka = "Lozinka mora da sadrži jedno malo slovo, jedno veliko slovo, jedan specijalan karakter, jednu cifru i da je dužine od 8 do 14 karaktera.";
                    } else if ((!empty($lozinka) && !empty($ponovljena) && $lozinka != $ponovljena) || (empty($ponovljena) && !empty($lozinka))) {
                        $poruka = "Lozinka u polju potvrde nije ista kao prva unesena.";
                    } else {
                        if ($imeSlike != null) {
                            $imeStareSlike = ($db->table("korisnik")->where("SifK", $SifK)->get()->getResult())[0]->ProfilnaSlika;
                            if (file_exists(FCPATH . "images\profilne\\" . $imeStareSlike) && $imeStareSlike != "") {
                                unlink(FCPATH . "images\profilne\\" . $imeStareSlike);
                            }
                        }
                        $profilna = $imeSlike;
                        $model->izmenaProfila($ime, $prezime, $lozinka, $email, $profilna, $SifK, $brTel);
                        session()->set("korisnik", ($db->table("korisnik")->where("SifK", $SifK)->get()->getResult())[0]);
                    }

                    $data["poruka"] = $poruka;
                } else {
                    $model->zahtevBrisanje($SifK);
                }
            }
        }


        $data["kontroler"] = "KorisnikController";
        $data["stranica"] = "izmenaProfila";
        $this->prikaz("izmenaProfila", $data);
    }
    /**
     * Ocenjivanje privatnika od strane korisnika
     * @author  Lana Ivkovic 2020/0480
     * @return void
     */
    public function ocenjivanje() {
        $data = [];
        $db = db_connect();
        $model = new ModelObicanKorisnikLana($db);
        if ($this->request->getMethod() == 'post') {
            if ($_POST) {
                $Imeprivatnika = $_POST['Imeprivatnika'];
                $ocena = $_POST['ocena'];
                $komentar = $_POST['komentar'];
                $SifK =  session()->get("korisnik")->SifK;
                $ocena = $model->ocenjivanje($Imeprivatnika, $komentar, $ocena, $SifK);
                if ($ocena == null) {
                    $data['poruka'] = "Privatnik ne postoji!";
                } else {
                    $data['porukaUspeh'] = "Uspešno ste ocenili privatnika.";
                    $db->table('ocena')->insert($ocena);
                }
            }
        }

        $data["kontroler"] = "KorisnikController";
        $data["stranica"] = "ocenjivanje";
        $this->prikaz("ocenjivanje", $data);
    }


    /**
     * 
     * Prikaz ponude u inboxu korisnika
     * @author  Anja Curic 2020/0513
     * @return void
     */
    public function prikazPonudeInbox() {
        $SifP = $this->request->getVar("SifP");
        $model = new ModelPonuda();
        $modelK = new ModelKorisnik();
        $ponuda = $model->where("SifP", $SifP)->findAll()[0];
        $ponuda->Korisnik = $modelK->where("SifK", $ponuda->SifK)->findAll()[0]->KorisnickoIme;
        $this->prikaz("prikazPonudeInbox", ["ponuda" => $ponuda, "kontroler" => "KorisnikController", "stranica" => "prikazPonudeInbox"]);
    }
    /**
     * Prikaz odabrane ponude korisnika: sa dijelom za rezervaciju i kupovinu
     * @author  Lana Ivkovic 2020/0480
     * 
     * @param int sifra ponude koja se prikazuje
     * @param string tip akcije koji ce se izvrsiti: kupovina ili rezervacija
     * @return array
     */
    public function prikazPonude($sifP, $tip = "nista") {
        $SifP = $sifP;
        $SifK = session()->get("korisnik")->SifK;


        // $this->inboxKorisnik();


        $db      = \Config\Database::connect();

        $model = new ModelObicanKorisnikLana($db);
        // $builder = $db->table("postavljenaponuda");
        // $ponuda = ($builder->where("SifP", $sifP)->get()->getResult());

        // if (count($ponuda) == 0) {

        //     $this->pretragaPonuda();
        //     return;
        // }
        $ponuda = $db->table("ponuda")->where("SifP", $sifP)->get()->getResult()[0];



        if ($this->request->getMethod() == 'post') {
            if ($_POST) {

                $BrMesta = $this->request->getVar("BrMesta");
                if (!empty($BrMesta)) {
                    $SifPokl = "";

                    if (isset($_POST['grupa'])) {
                        $SifPokl = $_POST['grupa'];
                    }

                    $modelP = new ModelPoruka();
                    if ($tip == "kupi") {

                        $uspijeh =  $model->kupovina_karata($sifP, $SifK, $BrMesta, $SifPokl);
                        if ($uspijeh) {
                            $data['porukaUspeh'] = "Uspešno ste kupili kartu!";
                            $SifPor = $modelP->where("SifKor", $SifK)->where("SifPonuda", $SifP)->findAll();
                            if (!empty($SifPor)) {
                                $SifPor = $SifPor[0]->SifPor;
                                $modelP->delete($SifPor);
                            }
                        } else {
                            $data['poruka'] = "Neuspešna kupovina karte!";
                        }
                    }
                    if ($tip == "rezervisi") {
                        $uspijeh = $model->rezervacija_karata($sifP, $SifK, $BrMesta);
                        if ($uspijeh) {
                            $data['porukaUspeh'] = "Uspešno ste rezervisali kartu!";
                            $SifPor = $modelP->where("SifKor", $SifK)->where("SifPonuda", $SifP)->findAll();
                            if (!empty($SifPor)) {
                                $SifPor = $SifPor[0]->SifPor;
                                $modelP->delete($SifPor);
                            }
                        } else {
                            $data['poruka'] = "Neuspešna rezervacija karte!";
                        }
                    }
                }
            }
        }
        $res = $db->table('jedobio')->select("SifPokl")->where('Sifk', $SifK)->get()->getResult();

        $data['ponuda'] = ($db->table('ponuda')->where("SifP", $sifP)->get()->getResult())[0];
        $new_res = [];
        foreach ($res as $r) {
            array_push($new_res, $r->SifPokl);
        }

        $res_1 = array_unique($new_res);
        $res = [];
        foreach ($res_1 as $r) {
            array_push($res, $db->table('poklon')->where("SifPokl", $r)->get()->getResult()[0]);
        }



        $data['mojenagrade'] = $res;

        $data["kontroler"] = "KorisninikController";
        $data["stranica"] = "prikazPonude";

        $this->prikaz("prikazPonude", $data);
    }


    /**
     * 
     * Prijavljivanje privatnika
     * @author  Lana Ivkovic 2020/0480
     * @return void
     */
    public function report() {
        $db = db_connect();
        $model = new ModelObicanKorisnikLana($db);
        $data = ["kontroler" => "KorisnikController"];
        $SifPrijavitelja =  session()->get("korisnik")->SifK;

        if ($this->request->getMethod() == 'post') {
            if ($_POST) {
                $SifK = $_POST['SifK'];
                $komentar = $_POST['komentar'];
                $prijava =  $model->report($SifK, $komentar, $SifPrijavitelja);
                if ($prijava == null) {
                    $data['poruka'] = "Privatnik ne postoji!";
                } else {
                    if ($komentar != "") {
                        $data['porukaUspeh'] = "Uspesno ste prijavili privatnika!";
                        $db->table('report')->insert($prijava);
                    } else {
                        $data['poruka'] = "Opis prijave je prazan, neophodno ga je popuniti!";
                    }
                }
            }
        }

        $data["kontroler"] = "KorisnikController";
        $data["stranica"] = "report";
        $this->prikaz("report", $data);
    }
    /**
     * 
     * Rezervacije ulogovanog korisnika
     * 
     * @author  Lana Ivkovic 2020/0480
     * 
     * @return void
     */
    public function rezervacije() {

        $db = db_connect();
        $SifK = session()->get("korisnik")->SifK;  // dohvati 
        $model = new ModelObicanKorisnikLana($db);
        $res = $db->table('jedobio')->select("SifPokl")->where('Sifk', $SifK)->get()->getResult();

        $new_res = [];
        foreach ($res as $r) {
            array_push($new_res, $r->SifPokl);
        }
        // print_r($new_res);
        $res_1 = array_unique($new_res);
        $res = [];
        foreach ($res_1 as $r) {
            array_push($res, $db->table('poklon')->where("SifPokl", $r)->get()->getResult()[0]);
        }


        $data['mojenagrade'] = $res;


        $data["mojeRezervacije"] =  $model->mojeRezervacije($SifK);


        $data["kontroler"] = "KorisnikController";
        $data["stranica"] = "rezervacije";

        // print_r($data["mojeRezervacije"]);
        $this->prikaz("rezervacije", $data);
    }
    /**
     * 
     * Kupovina karte kao i mogucnost otkazivanja rezervacije od strane ulogovanog korisnika
     * @author  Lana Ivkovic 2020/0480
     * @return void
     */

    public function kupi_kartu() {
        $db = db_connect();
        $model = new ModelObicanKorisnikLana($db);
        $data['poruka'] = "";

        $dugme = $this->request->getVar("kupi");



        $SifK = session()->get("korisnik")->SifK;  // dohvati 
        $res = $db->table('jedobio')->select("SifPokl")->where('Sifk', $SifK)->get()->getResult();

        $new_res = [];
        if (count($res)) {
            foreach ($res as $r) {
                array_push($new_res, $r->SifPokl);
            }
            // print_r($new_res);
            $res_1 = array_unique($new_res);
            $res = [];
            foreach ($res_1 as $r) {
                array_push($res, $db->table('poklon')->where("SifPokl", $r)->get()->getResult()[0]);
            }
        } else {
            $res = [];
        }

        $data['mojenagrade'] = $res;


        if ($this->request->getMethod() == 'post') {


            if ($_POST) {
                $SifR = $_POST['SifR'];
                $BrMesta = $_POST['BrMesta'];
                $SifPokl = "";
                if ($dugme == 'kupi') {


                    if (isset($_POST['grupa'])) {
                        $SifPokl = $_POST['grupa'];
                    }
                    $uspijeh = $model->kupi_kartu($SifR, $SifK, $BrMesta, $SifPokl);
                    if ($uspijeh) {
                        $data['porukaUspeh'] = "Uspešno ste kupili kartu!";
                    } else {
                        $data['poruka'] = "Neuspešna kupovina karte!";
                    }
                } else if ($dugme == 'odustani') {
                    $model->otkazi_rezervaciju($SifR);
                }
            }
        }
        $data["mojeRezervacije"] =  $model->mojeRezervacije($SifK);


        $data["kontroler"] = "KorisnikController";
        $data["stranica"] = "rezervacije";

        $this->prikaz("rezervacije", $data);
    }
    /**
     * 
     * Podnosenje zahteva za vandrednu voznju od strane ulogovanog korisnika
     * @author  Lana Ivkovic 2020/0480
     * @return void
     */
    public function trazenjeVoznje() {

        $db = db_connect();
        $model = new ModelObicanKorisnikLana($db);
        $data['mesta'] = $model->svaMesta();
        $data['sredstva'] = $model->svaSredstva();

        $SifK = session()->get("korisnik")->SifK;  // dohvati 
        if ($this->request->getMethod() == 'post') {


            if ($_POST) {



                if (
                    !empty($_POST['DatumDo']) && !empty($_POST['DatumOd']) && !empty($_POST['VremeDo']) && !empty($_POST['VremeOd'])
                ) {
                    if (empty($_POST['CenaOd'])) {
                        $_POST['CenaOd'] = 0;
                    }
                    if (empty($_POST['CenaDo'])) {
                        $_POST['CenaDo'] = 0;
                    }
                    if (empty($_POST['BrojPutnika'])) {
                        $_POST['BrojPutnika'] = 0;
                    }
                    if (
                        $_POST['DatumOd'] . " " . $_POST['VremeOd'] <= date("Y-m-d H:i:s") ||
                        $_POST['DatumDo'] . " " . $_POST['VremeDo'] <= date("Y-m-d H:i:s")
                    ) {

                        $data['poruka'] = "Greska pri unosu podataka za datum i vreme ostvarivanja voznje";
                    } else if ($_POST['BrojPutnika'] <= 0) {
                        $data['poruka'] = "Neophodno je da Broj Putnika bude veca od nule";
                    } else if ($_POST['CenaDo'] <= $_POST['CenaOd'] || $_POST['CenaDo'] < 0 || $_POST['CenaOd'] < 0) {

                        $data['poruka'] = "Cena mora biti pozitivna i Cena Do mora biti veca od Cene od!";
                    } else if ($_POST['MesOd'] == $_POST['MesDo']) {
                        $data['poruka'] = "Mesto polaska i dolaska se moraju razlikovati!";
                    } else {

                        $ponuda = [
                            'Sred' => $_POST['prevoz'],
                            'SifMesDo' => $_POST['MesDo'],
                            'SifMesOd' => $_POST['MesOd'],
                            'CenaOd' => $_POST['CenaOd'],
                            'CenaDo' => $_POST['CenaDo'],
                            'BrMesta' => $_POST['BrojPutnika'],
                            'DatumDo' => $_POST['DatumDo'],
                            'DatumOd' => $_POST['DatumOd'],
                            'VremeDo' => $_POST['VremeDo'],
                            'VremeOd' => $_POST['VremeOd'],
                            'SifK' => $SifK

                        ];

                        $data['porukaUspeh'] = "Uspešno ste izvršili tražnju vožnje!";
                        $data['poruka'] = "";

                        $model = new ModelObicanKorisnikLana($db);
                        $model->posaljiVandrednuVoznju($ponuda);
                    }
                } else {
                    $data['poruka'] = "Neophodno je da popunite sva polja, kako bi uspešno zatražili vožnju!";
                }
            }
        }


        $data["kontroler"] = "KorisnikController";
        $data["stranica"] = "trazenjeVoznje";

        $this->prikaz("trazenjeVoznje", $data);
    }
    /**
     * 
     * 
     *Tocak srece prikaz stranice
     *@author  Lana Ivkovic 2020/0480
     * 
     * @return void
     */
    public function spintheweel() {
        $data = [];
        $db = db_connect();
        $model = new ModelObicanKorisnikLana($db);
        $SifK = session()->get("korisnik")->SifK;  // dohvati 
        $data['tokena'] = $db->table('obicankorisnik')->where('SifK=', $SifK)->get()->getResult()[0]->token; // iz baze za korisnika
        $data["kontroler"] = "KorisnikController";
        $data["stranica"] = "tocakSrece";

        echo view('tocakSrece', $data);
    }
    /**
     * 
     * 
     * Obrada nagrade(Tocak srece) ulogovanog korisnika
     * @author  Lana Ivkovic 2020/0480
     * @return void
     */
    public function tocakSrece() {
        ////----->

        // echo "lana";
        $SifK = session()->get("korisnik")->SifK;  // dohvati 
        // $SifK = "2";
        $db = db_connect();


        $model = new ModelObicanKorisnikLana($db);
        $data['poruka'] = "";

        if ($this->request->getMethod() == 'post') {

            if ($_POST) {

                $poklon = $_POST['poklon'];
                $model->azuriraj_poklone_i_tokene($poklon, $SifK);
                $data['tokena'] = $db->table('obicankorisnik')->where('SifK=', $SifK)->get()->getResult()[0]->token; // iz baze za korisnika
            }
        }
        $data['tokena'] = $db->table('obicankorisnik')->where('SifK=', $SifK)->get()->getResult()[0]->token; // iz baze za korisnika


        $data["kontroler"] = "KorisnikController";
        $data["stranica"] = "tocakSrece";

        /// modalni prozor: modal-> otkazi ponudu samo mu reci gdje ce
        /// PREBACII I LIJEPO POSLAJI ZA INDEX -> PODATKE I PROMJENI U HEADERU
        $this->index();
    }
    /**
     * Komentari,koji se nalaze u footer-u stranice.Realizacija slanja komentara putem mail-a.
     * @author Anja Curic 2020/0513 
     * 
     * @return void
     */
    public function komentar() {
        $transport = new EsmtpTransport("smtp-mail.outlook.com", 587);
        $transport->setUsername("pomocniEPUTUJ1@outlook.com");
        $transport->setPassword("RADIMAIL123");
        $mailer = new Mailer($transport);
        $email = (new Email())->from("pomocniEPUTUJ1@outlook.com")->to('sideeyetim@outlook.com')
            ->subject('Novi komentar')->text('Ime:' . $this->request->getVar('ime') . '
Komentar:' . $this->request->getVar('komentar') . '
                ');


        $mailer->send($email);

        $stranica = $this->request->getVar("stranica");


        if ($stranica == "trazenjeVoznje") $this->trazenjeVoznje();
        else if ($stranica == "rezervacije") $this->rezervacije();
        else if ($stranica == "report") $this->report();
        else if ($stranica == "inboxKorisnik") $this->inboxKorisnik();
        else if ($stranica == "indexkorisnik") $this->index();
        else if ($stranica == "izmenaProfila") $this->izmenaProfila();
        else if ($stranica == "prikazPonude") $this->pregledPonuda();
        else if ($stranica == "prikazPonudeInbox") $this->inboxKorisnik();
        else if ($stranica == "ocenjivanje") $this->ocenjivanje();
        else if ($stranica == "pregledPonuda") $this->pregledPonuda();
    }
    /**
     * 
     * 
     * Logout za korisnike
     * @author  Anja Curic 2020/0513
     * @return void
     */

    public function logout() {
        session()->remove("korisnik");
        $gostController = new GostController();
        $gostController->index();
    }


    /**
     * 
     * 
     * Pretraga i filtriranje/sortiranje ponuda po izabranim kriterijumima
     * @author Milica Cvetković 2020/0003
     * @return mixed
     */
    public function pretragaPonuda() {

        $resetPage = $this->request->getVar("resetPage");
        $sortiranje = $this->request->getVar("sortiranje");

        if ($sortiranje != null) {
            $this->session->set("sortiranje", $sortiranje);
        }

        if ($resetPage != null || $sortiranje != null) {
            $page = 1;
        } else {
            $page = $this->request->getVar("page") != null ? $this->request->getVar("page") : 1;
        }

        $numOfResultsOnPage = 9;

        if ($resetPage != null) {

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

        $prevoznoSredstvo = $this->session->get("prevoznoSredstvo");
        $mestoOd = $this->session->get("mestoOd");
        $mestoDo = $this->session->get("mestoDo");
        $minimalnaCena = $this->session->get("minimalnaCena");
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

        switch ($sortiranje) {
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

        if ($sort != null || $this->session->get("sort") != null) {
            $temp = $this->session->get("sort");
            $this->session->set("sort", $sort);
            $ponude = $this->pretragaSort($prevoznoSredstvo, $mestoOd, $mestoDo, $minimalnaCena, $maksimalnaCena, $brojPutnika, $datumOd, $datumDo, $vremeOd, $vremeDo, $page, $numOfResultsOnPage, $rastuceCena, $rastuceDatum, $opadajuceCena, $opadajuceDatum);
        } else {
            $ponude = $this->pretraga($prevoznoSredstvo, $mestoOd, $mestoDo, $minimalnaCena, $maksimalnaCena, $brojPutnika, $datumOd, $datumDo, $vremeOd, $vremeDo, $page, $numOfResultsOnPage);
        }
        $totalPages = count($this->pretraga($prevoznoSredstvo, $mestoOd, $mestoDo, $minimalnaCena, $maksimalnaCena, $brojPutnika, $datumOd, $datumDo, $vremeOd, $vremeDo, $page, $numOfResultsOnPage));
        return $this->prikaz("pregledPonudaKorisnik", ["ponude" => $ponude, "svePonude" => $svePonude, "svaMesta" => $svaMesta, "svaPrevoznaSredstva" => $svaPrevoznaSredstva, "page" => $page, "numOfResultsOnPage" => $numOfResultsOnPage, "totalPages" => $totalPages, "submitted" => "true", "kontroler" => "KorisnikController", "stranica" => "pregledPonudaKorisnik"]);
    }

    /**
     * 
     * 
     * Dohvatanje svih prevoznih sredstava iz baze
     * @author Milica Cvetković 2020/0003
     * @return array
     */
    public function dohvatiSvaPrevoznaSredstva() {

        $db      = \Config\Database::connect();
        $builder = $db->table("prevoznosredstvo");

        $builder->select("*");

        return $builder->get()->getResult();
    }

    /**
     * @author Milica Cvetković 2020/0003
     * 
     * Dohvatanje svih mesta iz baze
     * 
     * @return array
     */
    public function dohvatiSvaMesta() {

        $db      = \Config\Database::connect();
        $builder = $db->table("mesto");

        $builder->select("*");

        return $builder->get()->getResult();
    }

    /**
     * @author Milica Cvetković 2020/0003
     * 
     * Dohvatanje svih ponuda iz baze
     * 
     * @return array
     */
    public function dohvatiSvePonude() {

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
     * 
     * 
     * Pretraga ponuda po zadatim kriterijumima
     * 
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
    public function pretraga($prevoznoSredstvo, $mestoOd, $mestoDo, $minimalnaCena, $maksimalnaCena, $brojPutnika, $datumOd, $datumDo, $vremeOd, $vremeDo, $page, $numOfResultsOnPage) {

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

        if ($prevoznoSredstvo != null)
            $builder->like("prevoznosredstvo.Naziv", $prevoznoSredstvo);
        if ($mestoOd != null)
            $builder->like("mOd.Naziv", $mestoOd);
        if ($mestoDo != null)
            $builder->like("mDo.Naziv", $mestoDo);
        if ($minimalnaCena != null)
            $builder->where("ponuda.CenaKarte >=", $minimalnaCena);
        if ($maksimalnaCena != null)
            $builder->where("CenaKarte <=", (float)$maksimalnaCena);
        if ($brojPutnika != null)
            $builder->where("ponuda.BrMesta <=", $brojPutnika);
        if ($datumOd != null)
            $builder->where("ponuda.DatumOd >=", $datumOd);
        if ($datumDo != null)
            $builder->where("ponuda.DatumDo <=", $datumDo);
        if ($vremeOd != null)
            $builder->where("ponuda.VremeOd >=", $vremeOd);
        if ($vremeDo != null)
            $builder->where("ponuda.VremeDo <=", $vremeDo);

        $builder->orderBy("NazivPretplate", "asc");

        $start = ($page - 1) * $numOfResultsOnPage;

        $builder->limit($start, $numOfResultsOnPage);

        return $builder->get()->getResult();
    }

    /** 
     * Pretraga ponuda po zadatim kriterijumima i izvrseno sortiranje
     * 
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
    public function pretragaSort($prevoznoSredstvo, $mestoOd, $mestoDo, $minimalnaCena, $maksimalnaCena, $brojPutnika, $datumOd, $datumDo, $vremeOd, $vremeDo, $page, $numOfResultsOnPage, $rastuceCena, $rastuceDatum, $opadajuceCena, $opadajuceDatum) {

        $db      = \Config\Database::connect();
        $builder = $db->table('ponuda');

        $builder->select("ponuda.SifP as SifP, mOd.Naziv as MestoOd, mDo.Naziv as MestoDo, ponuda.DatumOd as DatumOd, ponuda.DatumDo as DatumDo, ponuda.BrMesta as BrMesta, ponuda.CenaKarte as CenaKarte, prevoznosredstvo.Naziv as prevoznoSredstvo,ponuda.Slika as Slika, korisnik.Ime as Ime, korisnik.Prezime as Prezime, korisnik.KorisnickoIme as Korisnik, ponuda.SifK as SifK");
        $builder->join("mesto as mOd", "mOd.SifM = ponuda.SifMesOd");
        $builder->join("mesto as mDo", "mDo.SifM = ponuda.SifMesDo");
        $builder->join("prevoznosredstvo", "prevoznosredstvo.SifSred = ponuda.SifSred");
        $builder->join("korisnik", "korisnik.SifK = ponuda.SifK");

        $zahtevponuda = $db->table('zahtevponuda')->select('SifP');
        $builder->whereNotIn("SifP", $zahtevponuda);

        if ($rastuceCena != null)
            $builder->orderBy("ponuda.CenaKarte", "asc");
        if ($rastuceDatum != null)
            $builder->orderBy("ponuda.DatumOd", "asc");
        if ($opadajuceCena != null)
            $builder->orderBy("ponuda.CenaKarte", "desc");
        if ($opadajuceDatum != null)
            $builder->orderBy("ponuda.DatumOd", "desc");

        if ($prevoznoSredstvo != null)
            $builder->like("prevoznosredstvo.Naziv", $prevoznoSredstvo);
        if ($mestoOd != null)
            $builder->like("mOd.Naziv", $mestoOd);
        if ($mestoDo != null)
            $builder->like("mDo.Naziv", $mestoDo);
        if ($minimalnaCena != null)
            $builder->where("ponuda.CenaKarte >=", $minimalnaCena);
        if ($maksimalnaCena != null)
            $builder->where("CenaKarte <=", (float)$maksimalnaCena);
        if ($brojPutnika != null)
            $builder->where("ponuda.BrMesta <=", $brojPutnika);
        if ($datumOd != null)
            $builder->where("ponuda.DatumOd >=", $datumOd);
        if ($datumDo != null)
            $builder->where("ponuda.DatumDo <=", $datumDo);
        if ($vremeOd != null)
            $builder->where("ponuda.VremeOd >=", $vremeOd);
        if ($vremeDo != null)
            $builder->where("ponuda.VremeDo <=", $vremeDo);

        $start = ($page - 1) * $numOfResultsOnPage;

        $builder->limit($start, $numOfResultsOnPage);

        return $builder->get()->getResult();
    }

    /** 
     * 
     * Provera da li privatnik ima odgovarajucu pretplatu
     * @author Milica Cvetković 2020/0003
     * @param int $SifK
     * 
     * @return boolean
     */
    public function proveriPretplatu($SifK) {

        $db      = \Config\Database::connect();
        $builder = $db->table('privatnik');

        $builder->where("SifK", $SifK);
        $result = $builder->get()->getResult()[0];

        $pretplata = $result->SifPret;

        $builder = $db->table('pretplata');

        $builder->where("SifPret", $pretplata);
        $result = $builder->get()->getResult()[0];

        $pretplata = $result->Naziv;

        if ($pretplata == "Premium") {
            return true;
        }

        return false;
    }

    /**
     * 
     * Dohvatanje svih ponuda sa limitom zbog paginacije
     * @author Milica Cvetković 2020/0003
     * 
     * @param int $page
     * @param int $numOfResultsOnPage
     * 
     * @return array
     */
    public function dohvatiSvePonudeLimit($page, $numOfResultsOnPage) {

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
     * 
     * Izracunavanje proseka ocena
     * @author Željko Urošević 2020/0073
     * @param mixed $ponuda
     * 
     * @return double
     */
    public function prosek($ponuda) {

        $db      = \Config\Database::connect();
        $builder = $db->table("ocena");
        $ocene = $builder->where("SifPriv", $ponuda->SifK)->get()->getResult();
        $broj = 0;
        $suma = 0;
        foreach ($ocene as $ocena) {
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
     *
     * 
     * Stranica za pregled postavljenih ponuda
     * @author Milica Cvetković 2020/0003
     * @return mixed
     */
    public function pregledPonuda() {

        $totalPages = count($this->dohvatiSvePonude());

        $page = $this->request->getVar("page") != null ? $this->request->getVar("page") : 1;

        $numOfResultsOnPage = 9;

        $ponude = $this->dohvatiSvePonudeLimit($page, $numOfResultsOnPage);
        $svePonude = $this->dohvatiSvePonude();
        $svaMesta = $this->dohvatiSvaMesta();
        $svaPrevoznaSredstva = $this->dohvatiSvaPrevoznaSredstva();
        return $this->prikaz("pregledPonudaKorisnik", ["ponude" => $ponude, "svePonude" => $svePonude, "svaMesta" => $svaMesta, "svaPrevoznaSredstva" => $svaPrevoznaSredstva, "page" => $page, "numOfResultsOnPage" => $numOfResultsOnPage, "totalPages" => $totalPages, "kontroler" => "GostController", "stranica" => "pregledPonuda"]);
    }


    public function metoda() {
        echo 'lanaa';
        // Dohvati podatke poslane putem AJAX zahtjeva
        $data = $this->request->getPost(); // ili $this->request->getJSON() za JSON zahtjeve


        $response = [
            'status' => 'success',
            'message' => 'Ajax zahtjev je uspješno primljen!',
            'data' => $data
        ];
    }
}
