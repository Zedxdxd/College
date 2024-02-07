<?php


namespace App\Controllers;

use App\Models\ModelObicanKorisnikLana;
use App\Models\ModelPoruka;
use App\Models\ModelKorisnik;
use App\Models\ModelZahtevPonuda;
use App\Models\ModelPonuda;
use App\Models\ModelMesto;
use App\Models\ModelSredstvo;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

/**
 * PrivatnikController - klasa koja daje funkcionalnosti privatniku - pregled svojih ponuda,
 * pravljenje, otkazivanje i azuriranje ponuda, inbox gde dobija zahteve za voznje, promena
 * pretplate i izmena profila
 * 
 * @version 1.0
 */

class PrivatnikController extends BaseController {

    /**
     * Parametrizovan prikaz stranice $stranica pomocu $podaci
     * 
     * @author Zeljko Urosevic 2020/0073
     * 
     * @param string $stranica stranica koja se prikazuje
     * @param array $podaci podaci kojima je stranica parametrizovana
     * 
     * @return void
     */
    private function prikaz($stranica, $podaci) {

        $SifK = session()->get("korisnik")->SifK;
        $model = new ModelPoruka();
        $br = $model->select("count(*) as br")->where("SifPriv", $SifK)->where("SmerPoruke", 1)->findAll()[0]->br;
        echo view("sabloni/headerprivatnik", ["brPoruka" => $br]);
        echo view($stranica, $podaci);
        echo view("sabloni/footer");
    }

    /**
     * Pocetna stranica za privatnika
     * @author Zeljko Urosevic 2020/0073
     * 
     * @return void
     * 
     */
    public function index() {

        $db      = \Config\Database::connect();

        // dohvataju se ponude privatnika da bi se prikazale
        $korisnik = session()->get("korisnik");
        $builder = $db->table("ponuda");
        $ponude = $builder->where("SifK", $korisnik->SifK)->join("postavljenaponuda", "postavljenaponuda.SifP = ponuda.SifP")->get()->getResult();
        $builder = $db->table("ponuda");
        $vanredneponude = $builder->where("SifK", $korisnik->SifK)->join("vanrednaponuda", "vanrednaponuda.SifP = ponuda.SifP")->get()->getResult();
        foreach ($vanredneponude as $ponuda) {
            array_push($ponude, $ponuda);
        }
        $this->prikaz("indexprivatnik", ["ponude" => $ponude, "kontroler" => "PrivatnikController", "stranica" => "indexprivatnik"]);
    }

    /**
     *
     * Stranica na kojoj se privatniku prikazuju ponude koje je postavio i omogucava
     * izbor jedne od ponuda za azuriranje
     * 
     * @author Zeljko Urosevic 2020/0073
     * 
     * @return void
     */
    public function izborPonudeAzuriranje() {

        $db      = \Config\Database::connect();

        // dohvataju se ponude da bi se prikazale
        $korisnik = session()->get("korisnik");
        $builder = $db->table("ponuda");
        $ponude = $builder->where("SifK", $korisnik->SifK)->get()->getResult();

        $this->prikaz("izborPonudeAzuriranje", ["ponude" => $ponude, "kontroler" => "PrivatnikController", "stranica" => "izborPonudeAzuriranje"]);
    }

    /**
     * Prikaz detalja ponude koju je privatnik selektovao za pregled
     * 
     * @param int $sifP identifikator ponude koja se prikazuje
     * 
     * @return void
     */
    public function prikazPonude($sifP) {
        $db      = \Config\Database::connect();
        // dohvata se ponuda koja treba da se prikaze
        $builder = $db->table("ponuda");
        $ponuda = ($builder->where("SifP", $sifP)->get()->getResult())[0];
        $this->prikaz("prikazPonudePrivatnik", ["ponuda" => $ponuda, "kontroler" => "PrivatnikController", "stranica" => "prikazPonudePrivatnik"]);
    }

    /**
     * Prikaz forme za azuriranje ponude koju je privatnik selektovao
     * 
     * @author Zeljko Urosevic 2020/0073
     * 
     * @param int $sifP identifikator ponude koja se azurira
     */
    public function azurirajPonudu($sifP) {
        $db      = \Config\Database::connect();

        // dohvata se ponuda koja treba da se azurira kako bi se popunila forma
        $builder = $db->table("ponuda");
        $ponuda = ($builder->where("SifP", $sifP)->get()->getResult())[0];

        $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
    }

    /**
     * Poziva se kada privatnik popuni formu, vrsi odgovarajuce provere i ako 
     * prodju sve provere azurira ponudu u bazi
     * 
     * @author Zeljko Urosevic 2020/0073
     * 
     * @param int $sifP identifikator ponude koja se azurira
     * 
     * @return void
     */
    public function azuriranjePonudeSubmit($sifP) {
        $db      = \Config\Database::connect();
        $builder = $db->table("ponuda");
        $ponuda = ($builder->where("SifP", $sifP)->get()->getResult())[0];

        // dohvatanje svih podataka iz forme
        $prevoznosredstvo = $this->request->getVar("prevoznoSredstvo"); // ne moze da se apdejtuje
        $mestoOd = $this->request->getVar("mestoPolaska");
        $mestoDo = $this->request->getVar("mestoDolaska");
        $cena = $this->request->getVar("cenaKarte");
        $brMesta = $this->request->getVar("brMesta");
        $datumOd = $this->request->getVar("datumOd");
        $datumDo = $this->request->getVar("datumDo");
        $vremeOd = $this->request->getVar("vremeOd");
        $vremeDo = $this->request->getVar("vremeDo");
        $rokZaOtkazivanje = $this->request->getVar("rokZaOtkazivanje");

        $builder = $db->table("postavljenaponuda");
        if (!empty($builder->where("SifP", $ponuda->SifP)->get()->getResult())) {
            $builder = $db->table("postavljenaponuda");
            $trenutniRokZaOtkaz = ($builder->where("SifP", $ponuda->SifP)->get()->getResult())[0]->RokZaOtkazivanje;
        } else {
            $builder = $db->table("vanrednaponuda");
            $trenutniRokZaOtkaz = ($builder->where("SifP", $ponuda->SifP)->get()->getResult())[0]->RokZaOtkazivanje;
        }

        // broje se rezervacije jer neka polja treba proveriti samo ako nema rezervacija (videti SSU)
        $builder = $db->table("rezervacija");
        $rezervacije = $builder->where("SifP", $ponuda->SifP)->get()->getResult();
        $brojRezervisanihMesta = 0;
        foreach ($rezervacije as $rezervacija) {
            $brojRezervisanihMesta += $rezervacija->BrMesta;
        }

        //provere ispravnosti forme
        if (strpos($_FILES['slika']['type'], "image") === false) {
            $poruka = "Ubacen fajl nije slika";
            $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
        } else if ($_FILES["slika"]["size"] > 1000000) {
            $poruka = "Maksimalna dozvoljena veličina fajla je 1000000 bajtova.";
            $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
        } else if ($brMesta <= 0) {
            $poruka = "Broj slobodnih mesta mora biti nenegativan broj.";
            $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
        } else if ($cena <= 0) {
            $poruka = "Cena mora da bude pozitivan broj.";
            $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
        } else if (
            $brojRezervisanihMesta == 0 &&
            ($datumOd . " " . $vremeOd <= date("Y-m-d H:i:s") || $datumDo . " " . $vremeDo <= date("Y-m-d H:i:s"))
        ) {
            $poruka = "Uneti datum i vreme moraju biti kasnije od trenutnog datuma i vremena.";
            $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
        } else if ($brojRezervisanihMesta == 0 && $datumOd > $datumDo) {
            $poruka = "Datum dolaska mora biti kasnije od datuma polaska.";
            $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
        } else if ($brojRezervisanihMesta == 0 && $datumOd == $datumDo && $vremeOd >= $vremeDo) {
            $poruka = "Vreme dolaska mora biti kasnije od vremena polaska.";
            $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
        } else if ($brojRezervisanihMesta == 0 && $mestoOd == $mestoDo) {
            $poruka = "Mesto polaska i dolaska moraju biti različiti.";
            $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
        } else if ($rokZaOtkazivanje <= 0 || $rokZaOtkazivanje > $trenutniRokZaOtkaz) {
            $poruka = "Rok za otkazivanje rezervacije mora da bude pozitivan broj i ne veći od trenutnog roka.";
            $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
        } else {

            // cuvanje slike na serveru
            $destinacioniFolder = FCPATH . "images\ponude\\";
            $imeSlike = $sifP . "_" . date("YmdHis") . "_" . basename($_FILES['slika']['name']);
            $destinacioniFajl = $destinacioniFolder . $imeSlike;
            if (!move_uploaded_file($_FILES['slika']['tmp_name'], $destinacioniFajl)) {
                $poruka = "Nije uspelo ubacivanje slike";
                $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
            } else {
                // moze da se azurira ponuda

                $builder = $db->table("mesto");
                $SifMesOd = ($builder->where("Naziv", $mestoOd)->get()->getResult())[0]->SifM;
                $SifMesDo = ($builder->where("Naziv", $mestoDo)->get()->getResult())[0]->SifM;

                $builder = $db->table("ponuda");
                // brisanje stare slike ponude
                $imeStareSlike = ($builder->where("SifP", $sifP)->get()->getResult())[0]->Slika;
                if (file_exists(FCPATH . "images\ponude\\" . $imeStareSlike)) {
                    unlink(FCPATH . "images\ponude\\" . $imeStareSlike);
                }

                $data = [
                    "BrMesta" => $brMesta,
                    "DatumOd" => $datumOd,
                    "DatumDo" => $datumDo,
                    "VremeOd" => $vremeOd,
                    "VremeDo" => $vremeDo,
                    "CenaKarte" => $cena,
                    "SifMesDo" => $SifMesDo,
                    "SifMesOd" => $SifMesOd,
                    "Slika" => $imeSlike
                ];

                $builder->where("SifP", $sifP);
                $builder->update($data);

                $builder = $db->table("ponuda");
                $ponuda = ($builder->where("SifP", $sifP)->get()->getResult())[0];

                $builder = $db->table("postavljenaponuda");
                $data = [
                    "RokZaOtkazivanje" => $rokZaOtkazivanje
                ];
                $builder->where("SifP", $sifP);
                $builder->update($data);

                $this->prikaz("azurirajPonudu", ["ponuda" => $ponuda, "porukaUspeh" => "Uspesno azurirana ponuda!", "kontroler" => "PrivatnikController", "stranica" => "azurirajPonudu"]);
            }
        }
    }

    
    /**
     * Prikaz inbox-a privatnika,bez otvorene poruke
     * 
     * @author Anja Curic 2020/0513
     * @return void
     */
    public function inboxPrivatnik() {
        $Kime = session()->get("korisnik")->KorisnickoIme;
        $model = new ModelPoruka();
        $modelK = new ModelKorisnik();
        $modelP = new ModelPonuda();
        $modelM = new ModelMesto();

        $korisnik = $modelK->where("KorisnickoIme", $Kime)->findAll()[0];

        $poruke = $model->where("SifPriv", $korisnik->SifK)->where("SmerPoruke", 1)->findAll();
        foreach ($poruke as $poruka) {
            $ponuda = $modelP->where("SifP", $poruka->SifPonuda)->findAll()[0];
            $poruka->mestoOd = $modelM->where("SifM", $ponuda->SifMesOd)->findAll()[0];
            $poruka->korisnik = $modelK->where("SifK", $poruka->SifKor)->findAll()[0]->KorisnickoIme;
        }

        $this->prikaz("inboxPrivatnik", ["poruke" => $poruke, "kontroler" => "PrivatnikController", "stranica" => "inboxPrivatnik", "odabrana" => ""]);
    }


     /**
     * Prikaz selektovane poruke iz inbox-a privatnika
     * 
     * 
     * @author Anja Curic 2020/0513
     * @return void
     */
    public function inboxPrivatnikPoruka() {
        $izbor = $this->request->getVar("poruka");
        $Kime = session()->get("korisnik")->KorisnickoIme;
        $model = new ModelPoruka();
        $modelK = new ModelKorisnik();
        $modelZ = new ModelZahtevPonuda();
        $modelP = new ModelPonuda();
        $modelM = new ModelMesto();
        $modelS = new ModelSredstvo();

        $korisnik = $modelK->where("KorisnickoIme", $Kime)->findAll()[0];

        $poruke = $model->where("SifPriv", $korisnik->SifK)->where("SmerPoruke", 1)->findAll();
        foreach ($poruke as $poruka) {
            $ponuda = $modelP->where("SifP", $poruka->SifPonuda)->findAll()[0];
            $poruka->mestoOd = $modelM->where("SifM", $ponuda->SifMesOd)->findAll()[0];
            $poruka->korisnik = $modelK->where("SifK", $poruka->SifKor)->findAll()[0]->KorisnickoIme;
        }

        $izbor = $model->where("SifPor", $izbor)->findAll()[0];
        $odabrana = $modelP->where("SifP", $izbor->SifPonuda)->findAll()[0];
        $odabrana->CenaOd = $modelZ->where("SifP", $izbor->SifPonuda)->findAll()[0]->CenaOd;
        $odabrana->CenaDo = $modelZ->where("SifP", $izbor->SifPonuda)->findAll()[0]->CenaDo;
        $odabrana->mestoOd = $modelM->where("SifM", $odabrana->SifMesOd)->findAll()[0];
        $odabrana->mestoDo = $modelM->where("SifM", $odabrana->SifMesDo)->findAll()[0];
        $odabrana->sredstvo = $modelS->where("SifSred", $odabrana->SifSred)->findAll()[0]->Naziv;
        $this->prikaz("inboxPrivatnik", ["poruke" => $poruke, "odabrana" => $odabrana, "kontroler" => "PrivatnikController", "stranica" => "inboxPrivatnik"]);
    }

    /**
     * Otvara formu za pravljenje ponude
     * @author Zeljko Urosevic 2020/0073
     * 
     * @return void
     */
    public function napraviPonudu() {
        $SifK = $this->request->getVar("SifK");

        $this->prikaz("napraviPonudu", ["SifK" => $SifK, "kontroler" => "PrivatnikController", "stranica" => "napraviPonudu"]);
    }

    /**
     * Poziva se prilikom zavrsetka popunjavanja forme za kreiranje ponude;
     * Vrsi sve provere i ako prodju sve provere pravi novu ponudu u bazi
     * 
     * @author Zeljko Urosevic 2020/0073
     * 
     * @return void
     */
    public function napraviPonuduSubmit() {

        // dohvatanje svih podataka iz forme i cuvanje u sesiji kako bi se prilikom neuspeha opet popunila forma
        $prevoznosredstvo = $this->request->getVar("prevoznoSredstvo");
        session()->set("prevoznoSredstvo", $prevoznosredstvo);
        $mestoOd = $this->request->getVar("mestoPolaska");
        session()->set("mestoOd", $mestoOd);
        $mestoDo = $this->request->getVar("mestoDolaska");
        session()->set("mestoDo", $mestoDo);
        $cena = $this->request->getVar("cenaKarte");
        session()->set("cenaKarte", $cena);
        $brMesta = $this->request->getVar("brMesta");
        session()->set("brMesta", $brMesta);
        $datumOd = $this->request->getVar("datumOd");
        session()->set("datumOd", $datumOd);
        $datumDo = $this->request->getVar("datumDo");
        session()->set("datumDo", $datumDo);
        $vremeOd = $this->request->getVar("vremeOd");
        session()->set("vremeOd", $vremeOd);
        $vremeDo = $this->request->getVar("vremeDo");
        session()->set("vremeDo", $vremeDo);
        $rokZaOtkazivanje = $this->request->getVar("rokZaOtkazivanje");
        session()->set("rokZaOtkazivanje", $rokZaOtkazivanje);
        $SifK = $this->request->getVar("SifK");

        // provere ispravnosti forme
        if ($cena <= 0) {
            $poruka = "Cena mora da bude pozitivan broj.";
            $this->prikaz("napraviPonudu", ["poruka" => $poruka, "SifK" => $SifK, "kontroler" => "PrivatnikController", "stranica" => "napraviPonudu"]);
        } else if ($datumOd . " " . $vremeOd <= date("Y-m-d H:i:s") || $datumDo . " " . $vremeDo <= date("Y-m-d H:i:s")) {
            $poruka = "Uneti datum i vreme moraju biti kasnije od trenutnog datuma i vremena.";
            $this->prikaz("napraviPonudu", ["poruka" => $poruka, "SifK" => $SifK, "kontroler" => "PrivatnikController", "stranica" => "napraviPonudu"]);
        } else if ($datumOd > $datumDo) {
            $poruka = "Datum dolaska mora biti kasnije od datuma polaska.";
            $this->prikaz("napraviPonudu", ["poruka" => $poruka, "SifK" => $SifK, "kontroler" => "PrivatnikController", "stranica" => "napraviPonudu"]);
        } else if ($datumOd == $datumDo && $vremeOd >= $vremeDo) {
            $poruka = "Vreme dolaska mora biti kasnije od vremena polaska.";
            $this->prikaz("napraviPonudu", ["poruka" => $poruka, "SifK" => $SifK, "kontroler" => "PrivatnikController", "stranica" => "napraviPonudu"]);
        } else if ($mestoOd == $mestoDo) {
            $poruka = "Mesto polaska i dolaska moraju biti različiti.";
            $this->prikaz("napraviPonudu", ["poruka" => $poruka, "SifK" => $SifK, "kontroler" => "PrivatnikController", "stranica" => "napraviPonudu"]);
        } else if ($rokZaOtkazivanje <= 0 || (strtotime($datumOd) - strtotime(date("Y-m-d"))) / (60 * 60 * 24) < $rokZaOtkazivanje - 1) {
            $poruka = "Rok za otkazivanje rezervacije mora da bude pozitivan broj i da se uklapa u period do realizacije ponude.";
            $this->prikaz("napraviPonudu", ["poruka" => $poruka, "SifK" => $SifK, "kontroler" => "PrivatnikController", "stranica" => "napraviPonudu"]);
        } else if ($brMesta <= 0) {
            $poruka = "Broj slobodnih mesta mora da bude pozitivan broj.";
            $this->prikaz("napraviPonudu", ["poruka" => $poruka, "SifK" => $SifK, "kontroler" => "PrivatnikController", "stranica" => "napraviPonudu"]);
        } else {

            // prvo se pravi ponuda kako bismo znali pod kojim imenom treba da sacuvamo sliku
            // ako ne uspe cuvanje slike ti podaci se brisu i vraca se na formu
            $db      = \Config\Database::connect();
            $builder = $db->table("mesto");
            $SifMesOd = ($builder->where("Naziv", $mestoOd)->get()->getResult())[0]->SifM;
            $SifMesDo = ($builder->where("Naziv", $mestoDo)->get()->getResult())[0]->SifM;

            $builder = $db->table("prevoznosredstvo");
            $SifSred = ($builder->where("Naziv", $prevoznosredstvo)->get()->getResult())[0]->SifSred;


            $builder = $db->table("ponuda");
            $data = [
                "BrMesta" => $brMesta,
                "DatumOd" => $datumOd,
                "DatumDo" => $datumDo,
                "VremeOd" => $vremeOd,
                "VremeDo" => $vremeDo,
                "CenaKarte" => $cena,
                "SifMesDo" => $SifMesDo,
                "SifMesOd" => $SifMesOd,
                "SifSred" => $SifSred,
                "SifK" => session()->get("korisnik")->SifK,
                "SifPriv"=>session()->get("korisnik")->SifK
            ];

            $builder->insert($data);
            $sifP = $db->insertID();
            // cuvanje slike
            $destinacioniFolder = FCPATH . "images\ponude\\";
            $imeSlike = $sifP . "_" . date("YmdHis") . "_" . basename($_FILES['slika']['name']);
            $destinacioniFajl = $destinacioniFolder . $imeSlike;
            if (!move_uploaded_file($_FILES['slika']['tmp_name'], $destinacioniFajl)) {
                $builder = $db->table("ponuda");
                $builder->where("SifP", $sifP)->delete();
                $poruka = "Nije uspelo ubacivanje slike";
                $this->prikaz("napraviPonudu", ["poruka" => $poruka, "SifK" => $SifK, "kontroler" => "PrivatnikController", "stranica" => "napraviPonudu"]);
            } else {
                // prosle su provere, moze da se cuva ponuda u bazi
                if ($SifK != null && $SifK != -1) {
                    // ovo znaci da je Korisnik zatrazio ovu ponudu pa se cuva kao vanrednaponuda
                    $model = new ModelPoruka();
                    $modelP = new ModelPonuda();

                    $SifPriv = session()->get("korisnik")->SifK;
                    $model->insert(["SifPriv" => $SifPriv, "SifKor" => $SifK, "SifPonuda" => $sifP, "SmerPoruke" => "2"]);

                    $data = [
                        "Slika" => $imeSlike
                    ];
                    $builder = $db->table("ponuda");
                    $builder->where("SifP", $sifP)->update($data);

                    $builder = $db->table("vanrednaponuda");
                    $data = [
                        "SifP" => $sifP,
                        "RokZaOtkazivanje" => $rokZaOtkazivanje
                    ];
                    $builder->insert($data);

                    $ponuda = session()->get("zatrazenaPonuda");

                    $SifPor = $model->where("SifPonuda", $ponuda->SifP)->where("SifKor", $SifK)->where("SifPriv", $SifPriv)->findAll()[0]->SifPor;
                    $model->delete($SifPor);
                } else {
                    // privatnik je ipak inicirao pravljenje ponude
                    $data = [
                        "Slika" => $imeSlike
                    ];
                    $builder = $db->table("ponuda");
                    $builder->where("SifP", $sifP)->update($data);

                    $builder = $db->table("postavljenaponuda");
                    $data = [
                        "SifP" => $sifP,
                        "RokZaOtkazivanje" => $rokZaOtkazivanje
                    ];
                    $builder->insert($data);
                }

                // izbacivanje sadrzaja forme iz sesije
                session()->remove('zatrazenaPonuda');
                session()->remove("prevoznoSredstvo");
                session()->remove("mestoOd");
                session()->remove("mestoDo");
                session()->remove("cenaKarte");
                session()->remove("brMesta");
                session()->remove("datumOd");
                session()->remove("datumDo");
                session()->remove("vremeOd");
                session()->remove("vremeDo");
                session()->remove("rokZaOtkazivanje");
                $this->prikaz("napraviPonudu", ["porukaUspeh" => "Napravljena ponuda!", "SifK" => -1, "kontroler" => "PrivatnikController", "stranica" => "napraviPonudu"]);
            }
        }
    }

    /**
     * Prikaz svih ponuda koje je privatnik postavio sa mogucnoscu otkazivanja odredjene ponude
     * 
     * @author Zeljko Urosevic 2020/0073
     * 
     * @return void
     */
    public function otkaziPonudu() {
        $db      = \Config\Database::connect();

        // dohvatanje ponuda za prikaz
        $korisnik = session()->get("korisnik");
        $builder = $db->table("ponuda");
        $ponude = $builder->where("SifK", $korisnik->SifK)->get()->getResult();
        $this->prikaz("otkaziPonudu", ["ponude" => $ponude, "kontroler" => "PrivatnikController", "stranica" => "otkaziPonudu"]);
    }

    /**
     * Poziva se kada privatnik odluci da otkazuje odgovarajucu ponudu
     * 
     * @author Zeljko Urosevic 2020/0073
     * 
     * @param int $sifP identifikator ponude koja se otkazuje
     * 
     * @return void
     */
    public function otkaziPonuduSubmit($sifP) {
        $db      = \Config\Database::connect();
        $transport = new EsmtpTransport("smtp-mail.outlook.com", 587);
        $transport->setUsername("sideeyetim@outlook.com");
        $transport->setPassword("RADIMAIL123");
        $mailer = new Mailer($transport);

        $builder = $db->table("ponuda");
        $ponuda = ($builder->where("SifP", $sifP)->get()->getResult())[0];
        $builder = $db->table("mesto");
        $mestoOd = ($builder->where("SifM", $ponuda->SifMesOd)->get()->getResult())[0]->Naziv;
        $builder = $db->table("mesto");
        $mestoDo = ($builder->where("SifM", $ponuda->SifMesDo)->get()->getResult())[0]->Naziv;
        $builder = $db->table("korisnik");
        $privatnik = ($builder->where("SifK", $ponuda->SifK)->get()->getResult())[0];

        $builder = $db->table("kupljenakarta");
        $karte = $builder->where("SifP", $sifP)->get()->getResult();
        // prolazi se kroz svaku kupljenu kartu, i za svaku uplatu se vraca novac korisnicima koji su uplatili
        foreach ($karte as $karta) {
            $builder = $db->table("korisnik");
            $korisnik = ($builder->where("SifK", $karta->SifK)->get()->getResult())[0];

            $builder = $db->table("uplata");
            $uplate = $builder->where("SifKar", $karta->SifKar)->get()->getResult();
            foreach ($uplate as $uplata) {
                $builder = $db->table("korisnik");
                $korisnik = ($builder->where("SifK", $karta->SifK)->get()->getResult())[0];
                $data = [
                    "Novac" => $korisnik->Novac + $uplata->Iznos
                ];
                $builder->where("SifK", $korisnik->SifK);
                $builder->update($data);

                // slanje mejla da je otkazana ponuda; ruzno formatirano kako bi mejl bio lepo formatiran
                $email = (new Email())->from("sideeyetim@outlook.com")->to($korisnik->Email)
                    ->subject('Obaveštenje o otkazivanju ponude ' . $sifP)->text('Poštovani/a,

Privatnik ' . $privatnik->KorisnickoIme . ' je odlucio da otkaže ponudu ' . $sifP . ', od ' . $mestoOd . ' do ' . $mestoDo . ', 
zakazanu za ' . $ponuda->DatumOd . ' u ' . $ponuda->VremeOd . '. Sav uplaćen novac (' . $uplata->Iznos . '€) je vraćen.
                    
S poštovanjem,
Tim Side-eye.');
                $mailer->send($email);

                $builder = $db->table("korisnik");
                $privatnik = ($builder->where("SifK", $ponuda->SifK)->get()->getResult())[0];
                $data = [
                    "Novac" => $privatnik->Novac - $uplata->Iznos
                ];
                $builder->where("SifK", $privatnik->SifK);
                $builder->update($data);
            }

            // brisanje uplata vezanih za kartu
            $builder = $db->table("uplata");
            $builder->where("SifKar", $karta->SifKar);
            $builder->delete();
        }

        // brisanje karata vezanih za ponudu
        $builder = $db->table("kupljenakarta");
        $builder->where("SifP", $sifP);
        $builder->delete();

        // brisanje rezervacija, ali pre toga se za svaku rezervaciju salje mejl da je ponuda otkazana
        $builder = $db->table("rezervacija");
        $rezervacije = $builder->get()->getResult();
        foreach ($rezervacije as $rezervacija) {
            $builder=$db->table("korisnik");
            $korisnik=($builder->where("SifK",$rezervacija->SifK)->get()->getResult())[0];
            $email = (new Email())->from("sideeyetim@outlook.com")->to($korisnik->Email)
                ->subject('Obaveštenje o otkazivanju ponude ' . $sifP)->text('Poštovani/a,

Privatnik ' . $privatnik->KorisnickoIme . ' je odlucio da otkaže ponudu ' . $sifP . ', od ' . $mestoOd . ' do ' . $mestoDo . ', 
zakazanu za ' . $ponuda->DatumOd . ' u ' . $ponuda->VremeOd . '. Vaša rezervacija je opozvana.
            
S poštovanjem,
Tim Side-eye.');



            $mailer->send($email);
        }
        $builder = $db->table("rezervacija");
        $builder->where("SifP", $sifP);
        $builder->delete();

        // brise se slika sa servera
        $builder = $db->table("ponuda");
        $imeSlike = ($builder->where("SifP", $sifP)->get()->getResult())[0]->Slika;
        if (file_exists(FCPATH . "images\ponude\\" . $imeSlike)) {
            unlink(FCPATH . "images\ponude\\" . $imeSlike);
        }

        // finalno se brise ponuda
        $builder = $db->table("ponuda");
        $builder->where("SifP", $sifP);
        $builder->delete();

        // dohvataju se sve preostale ponude za prikaz
        $builder = $db->table("ponuda");
        $ponude = $builder->where("SifK", session()->get("korisnik")->SifK)->get()->getResult();
        $this->prikaz("otkaziPonudu", ["ponude" => $ponude, "poruka" => "Ponuda je otkazana!", "kontroler" => "PrivatnikController", "stranica" => "otkaziPonudu"]);
    }


    /**
     * Prikaz stranice sa informacijama o promeni pretplate kao i mogucnost prelaska sa jedne na drugu pretplatu
     * 
     * @author Zeljko Urosevic 2020/0073
     * 
     * @return void
     */
    public function promenaPretplate() {
        $this->prikaz("promenaPretplate", ["kontroler" => "PrivatnikController", "stranica" => "promenaPretplate"]);
    }

    /**
     * Poziva se kada privatnik odluci da promeni pretplatu, i tada se proverava da li ima
     * uslova za promenu pretplate i ako ima, vrsi se promena u bazi
     * 
     * @author Zeljko Urosevic 2020/0073
     * 
     * @return void
     */
    public function promenaPretplateSubmit() {
        $dugme = $this->request->getVar("dugme");

        $db      = \Config\Database::connect();
        $korisnikSifK = session()->get("korisnik")->SifK;
        $builder = $db->table("privatnik");
        $privatnikSifPretplata = ($builder->where("SifK", $korisnikSifK)->get()->getResult())[0]->SifPret;
        $builder = $db->table("pretplata");
        $pretplata = ($builder->where("SifPret", $privatnikSifPretplata)->get()->getResult())[0];
        if ($dugme == "Premium") {
            if ($pretplata->Naziv == "Premium") {
                $poruka = "Vec imate premium pretplatu.";
                $this->prikaz("promenaPretplate", ["poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "promenaPretplate"]);
            } else {
                $data = [
                    "SifPret" => ($builder->where("Naziv", "Premium")->get()->getResult())[0]->SifPret
                ];

                $builder = $db->table("privatnik");

                $builder->where("SifK", $korisnikSifK);
                $builder->update($data);
                $poruka = "Uspesno ažuriranje";
                $this->prikaz("promenaPretplate", ["porukaUspeh" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "promenaPretplate"]);
            }
        } else {
            if ($pretplata->Naziv == "Standard") {
                $poruka = "Vec imate standard pretplatu.";
                $this->prikaz("promenaPretplate", ["poruka" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "promenaPretplate"]);
            } else {
                $data = [
                    "SifPret" => ($builder->where("Naziv", "Standard")->get()->getResult())[0]->SifPret
                ];

                $builder = $db->table("privatnik");

                $builder->where("SifK", $korisnikSifK);
                $builder->update($data);
                $poruka = "Uspesno ažuriranje";
                $this->prikaz("promenaPretplate", ["porukaUspeh" => $poruka, "kontroler" => "PrivatnikController", "stranica" => "promenaPretplate"]);
            }
        }
    }

    /**
     * Funkcionalnost izmene profila privatnika (ime, prezime, sifra, email, broj telefona i slika)
     * 
     * @author Lana Ivkovic 2020/0480
     * 
     * @return void
     */
    public function izmenaProfila()
    {
        $data = [];
        $db = db_connect();
        $model = new ModelObicanKorisnikLana($db);
        $SifK =  session()->get("korisnik")->SifK;
        //$SifK = "2";
        $poruka = "";
        if ($this->request->getMethod() == 'post') {
            if ($_POST) {
                $dugme=$_POST['dugme'];
                if($dugme=="Sačuvaj"){ 
                    $data['poruka'] = "post";
                $ime = $_POST['ime'];
                $prezime = $_POST['prezime'];
                $lozinka = $_POST['lozinka'];
                $email = $_POST['email'];
                $brTel = $_POST['brTel'];
                $ponovljena=$_POST['ponovljena'];
               
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

                if(!empty($email) && !filter_var($email,FILTER_VALIDATE_EMAIL)){
                    $poruka = "Email adresa u pogrešnom formatu.";
                }
                else if(!empty($lozinka) && preg_match($regex, $lozinka) == 0){
                    $poruka = "Lozinka mora da sadrži jedno malo slovo, jedno veliko slovo, jedan specijalan karakter, jednu cifru i da je dužine od 8 do 14 karaktera.";
                }
                else if((!empty($lozinka) && !empty($ponovljena) && $lozinka!=$ponovljena) || (empty($ponovljena) && !empty($lozinka))){ 
                    $poruka="Lozinka u polju potvrde nije ista kao prva unesena.";
                }
                else{
                    if ($imeSlike != null) {
                        $imeStareSlike = ($db->table("korisnik")->where("SifK", $SifK)->get()->getResult())[0]->ProfilnaSlika;
                        if (file_exists(FCPATH . "images\profilne\\" . $imeStareSlike) && $imeStareSlike != "") {
                            unlink(FCPATH . "images\profilne\\" . $imeStareSlike);
                        }
                    }
                    $profilna = $imeSlike;
                    $model->izmenaProfila($ime, $prezime, $lozinka, $email, $profilna, $SifK,$brTel);
                    session()->set("korisnik", ($db->table("korisnik")->where("SifK", $SifK)->get()->getResult())[0]);
                } 

                $data["poruka"] = $poruka;
            }


            

            else{ 
                $model->zahtevBrisanje($SifK);
            }
            }


            $data["poruka"] = $poruka;
        }
    
        $data["kontroler"] = "PrivatnikController";
        $data["stranica"] = "izmenaProfila";
        $this->prikaz("izmenaProfila", $data);
    }

    /**
     * Poziva se prilikom odjavljivanja privatnika
     * 
     * @return void
     */
    public function logout() {
        session()->remove("korisnik");
        $gostController = new GostController();
        $gostController->index();
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


        if ($stranica == "izmenaProfila") $this->izmenaProfila();
        else if ($stranica == "inboxPrivatnik") $this->inboxPrivatnik();
        else if ($stranica == "napraviPonudu") $this->napraviPonudu();
        else if ($stranica == "azurirajPonudu") $this->izborPonudeAzuriranje();
        else if ($stranica == "izborPonudeAzuriranje") $this->izborPonudeAzuriranje();
        else if ($stranica == "promenaPretplate") $this->promenaPretplate();
        else if ($stranica == "otkaziPonudu") $this->otkaziPonudu();
        else if ($stranica == "prikazPonudePrivatnik") $this->index();
    }
}
