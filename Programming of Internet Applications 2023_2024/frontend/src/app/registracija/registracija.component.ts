import { Component, OnInit } from '@angular/core';
import { Pitanje } from '../models/pitanje';
import { Predmet } from '../models/predmet';
import { PitanjeService } from '../services/pitanje.service';
import { PredmetService } from '../services/predmet.service';
import { FileService } from '../services/file.service';
import { Korisnik } from '../models/korisnik';
import { KorisnikService } from '../services/korisnik.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-registracija',
  templateUrl: './registracija.component.html',
  styleUrls: ['./registracija.component.css']
})
export class RegistracijaComponent implements OnInit {

  timeout: number = 2000;

  svaPitanja: Pitanje[] = [];
  sviPredmeti: Predmet[] = [];
  omogucenoPoljeZaCustomPredmet: boolean = false

  korime: string = "";
  lozinka: string = "";
  ponovljenaLozinka: string = "";
  izabranoPitanje: Pitanje = new Pitanje();
  odgovor: string = "";
  ime: string = "";
  prezime: string = "";
  pol: string = "";
  adresa: string = "";
  telefon: string = "";
  email: string = "";

  tipSkole: string = "";
  razred: number = 1;

  imeCustomPredmet: string = "";
  uzrastOsn14: boolean = false;
  uzrastOsn58: boolean = false;
  uzrastSr14: boolean = false;
  gdeSteCuli: string = "";

  tip: string = "ucenik";

  CVFajl: File | null = null;
  SlikaFajl: File | null = null;

  // za greske
  brGresaka: number = 0;
  greskaKorime: string = "";
  greskaLozinka: string = "";
  greskaPonovljenaLozinka: string = "";
  greskaIzabranoPitanje: string = "";
  greskaOdgovor: string = ""; // ako je pitanje za onaj kurac mesec i godina pa format jelte
  greskaIme: string = "";
  greskaPrezime: string = "";
  greskaPol: string = "";
  greskaAdresa: string = "";
  greskaTelefon: string = "";
  greskaEmail: string = "";

  greskaTipSkole: string = "";
  greskaRazred: string = "";

  greskaPredmeti: string = "";
  greskaCustomPredmet: string = "";
  greskaUzrast: string = "";
  greskaGdeSteCuli: string = "";
  greskaCV: string = "";
  registracijaGreska: string = "";
  registracijaUspeh: string = "";

  constructor(private router: Router,
    private pitanjeServis: PitanjeService,
    private predmetServis: PredmetService,
    private korisnikServis: KorisnikService) { }

  ngOnInit(): void {
    let x = localStorage.getItem("ulogovan");
    if (x != null) {
      let ulogovan: Korisnik = JSON.parse(x);
      if (ulogovan.tip == "nastavnik"){
        this.router.navigate(['nastavnikProfil']);
      }
      else if (ulogovan.tip == 'ucenik'){
        this.router.navigate(['ucenikProfil']);
      }
      else if (ulogovan.tip == "admin"){
        this.router.navigate(['adminPocetna']);
      }
    }
    this.pitanjeServis.dohvatiSvaPitanja().subscribe(
      (data) => {
        this.svaPitanja = data;
      }
    )
    this.predmetServis.dohvatiSvePredmete().subscribe(
      (data) => {
        this.sviPredmeti = data;
      }
    )
  }

  onFileSelected(event: any, tipInput: string) {
    if (tipInput == "profilna") {
      this.SlikaFajl = event.target.files[0];
    }
    else if (tipInput == "CV") {
      this.CVFajl = event.target.files[0];
    }
  }

  private getFileBlob(filePath: string): Promise<Blob> {
    return fetch(filePath).then(response => response.blob());
  }

  registracija() {
    this.brGresaka = 0;
    if (this.korime == "") {
      this.brGresaka++;
      this.greskaKorime = "Korisnicko ime je obavezno polje.";
    }
    else {
      this.greskaKorime = "";
    }

    const lozinkaRegex = /^(?=.*[A-Z])(?=.*[a-z].*[a-z].*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z][A-Za-z\d!@#$%^&*]{5,9}$/;
    if (this.lozinka == "") {
      this.brGresaka++;
      this.greskaLozinka = "Lozinka je obavezno polje.";
    }
    else if (!lozinkaRegex.test(this.lozinka)) {
      this.brGresaka++;
      this.greskaLozinka = "Lozinka mora da bude minimalne duzine 6, a maksimalne 10. Takodje mora da ima bar jedno veliko slovo,\
      bar tri mala slova, bar jedan broj i bar jedan specijalni karakter i mora pocinjati slovom."
    }
    else {
      this.greskaLozinka = "";
    }
    console.log(this.lozinka);

    if (this.ponovljenaLozinka == "") {
      this.brGresaka++;
      this.greskaPonovljenaLozinka = "Ponovljena lozinka je obavezno polje.";
    }
    else if (this.ponovljenaLozinka != this.lozinka) {
      this.brGresaka++;
      this.greskaPonovljenaLozinka = "Lozinka i ponovljena lozinka nisu iste."
    }
    else {
      this.greskaPonovljenaLozinka = "";
    }

    if (this.izabranoPitanje.tekst == "") {
      this.brGresaka++;
      this.greskaIzabranoPitanje = "Nije izabrano bezbednosno pitanje.";
    }
    else {
      this.greskaIzabranoPitanje = "";
    }

    const odgovorRegex = /^(0[1-9]|1[0-2])\/\d{4}$/;
    if (this.odgovor == "") {
      this.brGresaka++;
      this.greskaOdgovor = "Nije dat odgovor na bezbednosno pitanje."
    }
    else if (this.izabranoPitanje.tekst == "Koji je mesec i godina venčanja vaših roditelja? (MM/YYYY)" &&
      !odgovorRegex.test(this.odgovor)) {
      this.brGresaka++;
      this.greskaOdgovor = "Odgovor nije u dobrom formatu.";
    }
    else {
      this.greskaOdgovor = "";
    }

    if (this.ime == ""){
      this.brGresaka++;
      this.greskaIme = "Ime je obavezno polje."
    }
    else {
      this.greskaIme = "";
    }

    if (this.prezime == ""){
      this.brGresaka++;
      this.greskaPrezime = "Prezime je obavezno polje."
    }
    else {
      this.greskaPrezime = "";
    }

    if (this.pol == ""){
      this.brGresaka++;
      this.greskaPol = "Pol je obavezno polje.";
    }
    else {
      this.greskaPol = "";
    }

    if (this.adresa == ""){
      this.brGresaka++;
      this.greskaAdresa = "Adresa je obavezno polje.";
    }
    else {
      this.greskaAdresa = "";
    }

    const telefonRegex = /^\d{3}-\d{3}-\d{2}-\d{2}$/;
    if (this.telefon == ""){
      this.brGresaka++;
      this.greskaTelefon = "Telefon je obavezno polje."
    }
    else if (!telefonRegex.test(this.telefon)) {
      this.brGresaka++;
      this.greskaTelefon = "Telefon nije unet u dobrom formatu.";
    }
    else {
      this.greskaTelefon = "";
    }

    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (this.email == ""){
      this.brGresaka++;
      this.greskaEmail = "Email je obavezno polje.";
    }
    else if (!emailRegex.test(this.email)){
      this.brGresaka++;
      this.greskaEmail = "Email nije unet u dobrom formatu.";
    }
    else {
      this.greskaEmail = "";
    }

    const korisnik = new Korisnik();
    korisnik.korime = this.korime;
    korisnik.lozinka = this.lozinka;
    korisnik.pitanje = this.izabranoPitanje.tekst;
    korisnik.odgovor = this.odgovor;
    korisnik.ime = this.ime;
    korisnik.prezime = this.prezime;
    korisnik.pol = this.pol;
    korisnik.adresa = this.adresa;
    korisnik.telefon = this.telefon;
    korisnik.email = this.email;
    korisnik.tip = this.tip;

    if (this.tip == "ucenik") {
      korisnik.tipSkole = this.tipSkole;

      if (this.tipSkole == ""){
        this.brGresaka++;
        this.greskaTipSkole = "Tip skole je obavezno polje.";
      }
      else {
        this.greskaTipSkole = "";
      }
      korisnik.razred = this.razred;
      if (!(this.tipSkole == "osn" && this.razred >= 1 && this.razred <= 8 ||
      this.tipSkole.startsWith("sr") && this.razred >= 1 && this.razred <= 4) && this.tipSkole != "") {
        this.brGresaka++;
        this.greskaRazred = "Uneti razred nije dobar. Ili je negativan ili izabran tip skole nema toliko razreda.";
      }
      else {
        this.greskaRazred = "";
      }
      korisnik.odobrenaRegistracija = "da";

      if (this.brGresaka > 0) {
        this.registracijaGreska = "Ima neke greske u formi. Proverite svako polje da li je ispravno uneto.";
        return;
      }
      else {
        this.registracijaGreska = "";
      }

      if (!this.SlikaFajl) {
        const filePath = ('../../assets/default_profile.jpg');
        this.getFileBlob(filePath).then((blob: Blob) => {
          this.SlikaFajl = new File([blob], 'default_profile.jpg');
          this.korisnikServis.registracijaUcenika(korisnik, this.SlikaFajl).subscribe(
            (data) => {
              if (data.message != 'ok') {
                this.registracijaGreska = data.message;
              }
              else {
                this.registracijaUspeh = "Uspesna registracija. Povratak na pocetnu stranu.";
                setTimeout(() => {
                  this.router.navigate(['']);
                }, this.timeout);
              }
            }
          )
        })
      }
      else {
        this.korisnikServis.registracijaUcenika(korisnik, this.SlikaFajl).subscribe(
          (data) => {
            if (data.message != 'ok') {
              this.registracijaGreska = data.message;
            }
            else {
              this.registracijaUspeh = "Uspesna registracija. Povratak na pocetnu stranu.";
              setTimeout(() => {
                this.router.navigate(['']);
              }, this.timeout);
            }
          }
        )
      }
    }
    else if (this.tip == "nastavnik") {
      korisnik.predajePredmete = [];
      for (let i = 0; i < this.sviPredmeti.length; i++) {
        if (this.sviPredmeti[i].selektovan) {
          korisnik.predajePredmete.push(this.sviPredmeti[i].naziv);
        }
      }

      if (korisnik.predajePredmete.length == 0 && !this.omogucenoPoljeZaCustomPredmet){
        this.brGresaka++;
        this.greskaPredmeti = "Mora da se izabere bar jedan predmet sa liste ili da se unese predmet koji se ne nalazi na listi u polje ispod."
      }
      else {
        this.greskaPredmeti = "";
      }

      if (this.omogucenoPoljeZaCustomPredmet && this.imeCustomPredmet == ""){
        this.brGresaka++;
        this.greskaCustomPredmet = "Nije uneto ime za predmet koji se ne nalazi na ovoj listi a zelite da ga predajete.";
      }
      else {
        this.greskaCustomPredmet = "";
      }

      korisnik.predajeUzrastu = [];
      if (this.uzrastOsn14) {
        korisnik.predajeUzrastu.push("osn14");
      }
      if (this.uzrastOsn58) {
        korisnik.predajeUzrastu.push("osn58");
      }
      if (this.uzrastSr14) {
        korisnik.predajeUzrastu.push("sr14");
      }

      if (korisnik.predajeUzrastu.length == 0){
        this.brGresaka++;
        this.greskaUzrast = "Uzrast je obavezno polje.";
      }
      else {
        this.greskaUzrast = "";
      }

      korisnik.gdeSteCuli = this.gdeSteCuli;
      if (this.gdeSteCuli == ""){
        this.brGresaka++;
        this.greskaGdeSteCuli = "Gde ste culi za nas sajt je obavezno polje."
      }
      else {
        this.greskaGdeSteCuli = "";
      }
      korisnik.odobrenaRegistracija = "ne";

      if (!this.CVFajl) {
        this.brGresaka++;
        this.greskaCV = "Kacenje CV je obavezno.";
      }
      if (this.brGresaka > 0) {
        this.registracijaGreska = "Ima neke greske u formi. Proverite svako polje da li je ispravno uneto.";
        return;
      }
      else {
        this.registracijaGreska = "";
      }
      if (!this.omogucenoPoljeZaCustomPredmet) {
        this.imeCustomPredmet = "";
      }
      if (!this.SlikaFajl) {
        const filePath = ('../../assets/default_profile.jpg');
        console.log('abcdef');
        this.getFileBlob(filePath).then((blob: Blob) => {
          this.SlikaFajl = new File([blob], 'default_profile.jpg');
          if (!this.CVFajl) {
            return
          };
          this.korisnikServis.registracijaNastavnika(korisnik, this.SlikaFajl, this.CVFajl, this.imeCustomPredmet).subscribe(
            (data) => {
              if (data.message != 'ok') {
                this.registracijaGreska = data.message;
              }
              else {
                this.registracijaUspeh = "Uspesno poslat zahtev za registraciju. Admin mora potvrditi vas zahtev da biste pristupili svom nalogu.";
                setTimeout(() => {
                  this.router.navigate(['']);
                }, this.timeout + 1000);
              }
            }
          )
        })
      }
      else {
        if (!this.CVFajl) {
          return
        };
        this.korisnikServis.registracijaNastavnika(korisnik, this.SlikaFajl, this.CVFajl, this.imeCustomPredmet).subscribe(
          (data) => {
            if (data.message != 'ok') {
              this.registracijaGreska = data.message;
            }
            else {
              this.registracijaUspeh = "Uspesno poslat zahtev za registraciju. Admin mora potvrditi vas zahtev da biste pristupili svom nalogu.";
              setTimeout(() => {
                this.router.navigate(['']);
              }, this.timeout + 1000);
            }
          }
        )
      }
    }
  }
}
