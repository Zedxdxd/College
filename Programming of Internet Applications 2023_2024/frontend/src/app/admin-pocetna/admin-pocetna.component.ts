import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { Korisnik } from '../models/korisnik';
import { KorisnikService } from '../services/korisnik.service';
import { Predmet } from '../models/predmet';
import { PredmetService } from '../services/predmet.service';
import { FileService } from '../services/file.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-admin-pocetna',
  templateUrl: './admin-pocetna.component.html',
  styleUrls: ['./admin-pocetna.component.css']
})
export class AdminPocetnaComponent implements OnInit {
  @ViewChild('scrollTarget') scrollTarget?: ElementRef;

  sviUcenici: Korisnik[] = [];
  sviOdobreniNastavnici: Korisnik[] = [];
  sviZahteviNastavnici: Korisnik[] = [];
  sviPredmeti: Predmet[] = [];
  zahtevaniPredmeti: Predmet[] = [];
  selektovanNastavnik: Korisnik = new Korisnik();

  biloPromene: boolean = false;
  brGresaka: number = 0;
  greskaNastavnikIme: string = "";
  greskaNastavnikPrezime: string = "";
  greskaNastavnikAdresa: string = "";
  greskaNastavnikTelefon: string = "";
  greskaNastavnikEmail: string = "";
  greskaNastavnikPredmeti: string = "";
  greskaNastavnikUzrast: string = "";
  uzrastOsn14: boolean = false;
  uzrastOsn58: boolean = false;
  uzrastSr14: boolean = false;
  greskaAzuriranje = "";
  azuriranjeUspeh = "";
  greskaRegistracija = "";
  uspehRegistracija = "";

  imeNovPredmet: string = "";
  greskaNovPredmet: string = "";
  greskaOdobravanje: string = "";
  uspehOdobravanje: string = "";
  dodavanjeNovUspeh: string = "";

  constructor(private router: Router,
    private korisnikServis: KorisnikService,
    private predmetServis: PredmetService,
    private fileServis: FileService) { }

  ngOnInit(): void {
    let x = localStorage.getItem("ulogovan");
    if (x == null){
      this.router.navigate(['']);
    }
    else {
      let ulogovan: Korisnik = JSON.parse(x);
      if (ulogovan.tip == "nastavnik"){
        this.router.navigate(['nastavnikProfil']);
      }
      else if (ulogovan.tip == 'ucenik'){
        this.router.navigate(['ucenikProfil']);
      }
    }

    this.selektovanNastavnik = new Korisnik();
    this.azuriranjeUspeh = "";
    this.biloPromene = false;
    this.greskaAzuriranje = "";
    this.greskaRegistracija = "";
    this.uspehRegistracija = "";
    this.greskaOdobravanje = "";
    this.uspehOdobravanje = "";
    this.dodavanjeNovUspeh = "";
    this.korisnikServis.dohvatiSveUcenike().subscribe(
      (data) => {
        this.sviUcenici = data;
      }
    )
    this.korisnikServis.dohvatiSveOdobreneNastavnike().subscribe(
      (data) => {
        this.sviOdobreniNastavnici = data;

      }
    )
    this.korisnikServis.dohvatiSveZahteveNastavnike().subscribe(
      (data) => {
        this.sviZahteviNastavnici = data;
        this.sviZahteviNastavnici.forEach(nastavnik => {
          this.fileServis.dohvatiUrlCV(nastavnik.cv).subscribe(
            (data1) => {
              nastavnik.cvurl = data1.cvUrl;
              console.log(nastavnik.cvurl);
            }
          )
        });
      }
    )
    this.predmetServis.dohvatiSvePredmete().subscribe(
      (predmeti) => {
        this.sviPredmeti = predmeti;
      }
    )
    this.predmetServis.dohvatiZahtevanePredmete().subscribe(
      (predmeti) => {
        this.zahtevaniPredmeti = predmeti;
        console.log(this.zahtevaniPredmeti);
      }
    )
  }

  selektuj(nastavnik: Korisnik) {
    if (nastavnik.korime == this.selektovanNastavnik.korime) {
      this.selektovanNastavnik = new Korisnik();
      return;
    }
    this.selektovanNastavnik = nastavnik;
    for (let i = 0; i < this.sviPredmeti.length; i++) {
      this.sviPredmeti[i].selektovan = false;
      let predmet = this.selektovanNastavnik.predajePredmete.find(element => element == this.sviPredmeti[i].naziv);
      if (predmet !== undefined) {
        this.sviPredmeti[i].selektovan = true;
      }
    }

    this.uzrastOsn14 = false;
    this.uzrastOsn58 = false;
    this.uzrastSr14 = false;
    if (this.selektovanNastavnik.predajeUzrastu.find(element => element == "osn14")) {
      this.uzrastOsn14 = true;
    }
    if (this.selektovanNastavnik.predajeUzrastu.find(element => element == "osn58")) {
      this.uzrastOsn58 = true;
    }
    if (this.selektovanNastavnik.predajeUzrastu.find(element => element == "sr14")) {
      this.uzrastSr14 = true;
    }

    const targetElement = this.scrollTarget?.nativeElement;
    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  odobriPredmet(predmet: Predmet) {
    this.predmetServis.odobriPredmet(predmet.naziv).subscribe(
      (data) => {
        if (data.message != 'ok'){
          this.greskaOdobravanje = data.message;
          return;
        }
        this.uspehOdobravanje = "Uspesno je odobren predmet.";
        setTimeout(() => {
          this.ngOnInit();
        }, 1000);
      }
    )
  }

  dodajPredmet() {
    if (this.sviPredmeti.find(e => e.naziv == this.imeNovPredmet) !== undefined ||
      this.zahtevaniPredmeti.find(e => e.naziv == this.imeNovPredmet) !== undefined) {
        this.greskaNovPredmet = "Predmet sa tim imenom vec postoji.";
        return;
    }
    this.greskaNovPredmet = "";
    this.predmetServis.dodavanjeNovPredmet(this.imeNovPredmet).subscribe(
      (data) => {
        if (data.message != 'ok'){
          this.greskaNovPredmet = data.message;
          return;
        }
        this.dodavanjeNovUspeh = "Uspesno dodat predmet.";
        setTimeout(() => {
          this.ngOnInit();
        }, 1000);
      }
    )
  }

  promena() {
    this.brGresaka = 0;
    this.biloPromene = true;

    if (this.selektovanNastavnik.ime == "") {
      this.brGresaka++;
      this.greskaNastavnikIme = "Ime je obavezno polje.";
    }
    else {
      this.greskaNastavnikIme = "";
    }
    if (this.selektovanNastavnik.prezime == "") {
      this.brGresaka++;
      this.greskaNastavnikPrezime = "Prezime je obavezno polje.";
    }
    else {
      this.greskaNastavnikPrezime = "";
    }
    if (this.selektovanNastavnik.adresa == "") {
      this.brGresaka++;
      this.greskaNastavnikAdresa = "Adresa je obavezno polje.";
    }
    else {
      this.greskaNastavnikAdresa = "";
    }
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (this.selektovanNastavnik.email == "") {
      this.brGresaka++;
      this.greskaNastavnikEmail = "Email je obavezno polje.";
    }
    else if (!emailRegex.test(this.selektovanNastavnik.email)) {
      this.brGresaka++;
      this.greskaNastavnikEmail = "Email mora biti u formatu example@example.example";
    }
    else {
      this.greskaNastavnikEmail = "";
    }

    const telefonRegex = /^\d{3}-\d{3}-\d{2}-\d{2}$/;
    if (this.selektovanNastavnik.telefon == "") {
      this.brGresaka++;
      this.greskaNastavnikTelefon = "Telefon je obavezno polje."
    }
    else if (!telefonRegex.test(this.selektovanNastavnik.telefon)) {
      this.brGresaka++;
      this.greskaNastavnikTelefon = "Telefon mora biti u formatu ddd-ddd-dd-dd, gde je d cifra";
    }
    else {
      this.greskaNastavnikTelefon = "";
    }

    this.selektovanNastavnik.predajePredmete = [];
    for (let i = 0; i < this.sviPredmeti.length; i++) {
      if (this.sviPredmeti[i].selektovan) {
        this.selektovanNastavnik.predajePredmete.push(this.sviPredmeti[i].naziv);
      }
    }

    if (this.selektovanNastavnik.predajePredmete.length == 0) {
      this.brGresaka++;
      this.greskaNastavnikPredmeti = "Mora da se izabere bar jedan predmet sa liste.";
    }
    else {
      this.greskaNastavnikPredmeti = "";
    }

    this.selektovanNastavnik.predajeUzrastu = [];
    if (this.uzrastOsn14) {
      this.selektovanNastavnik.predajeUzrastu.push("osn14");
    }
    if (this.uzrastOsn58) {
      this.selektovanNastavnik.predajeUzrastu.push("osn58");
    }
    if (this.uzrastSr14) {
      this.selektovanNastavnik.predajeUzrastu.push("sr14");
    }

    if (this.selektovanNastavnik.predajeUzrastu.length == 0) {
      this.brGresaka++;
      this.greskaNastavnikUzrast = "Uzrast je obavezno polje.";
    }
    else {
      this.greskaNastavnikUzrast = "";
    }
  }

  sacuvajIzmene() {
    this.korisnikServis.azuriranjeNastavnika(this.selektovanNastavnik).subscribe(
      (data) => {
        if (data.message != 'ok') {
          this.greskaAzuriranje = data.message;
          return;
        }
        this.azuriranjeUspeh = "Nastavnik je uspesno izmenjen.";
        setTimeout(() => {
          this.ngOnInit();
        }, 1000);
      }
    )
  }

  deaktiviraj() {
    this.korisnikServis.deaktivacijaNastavnika(this.selektovanNastavnik).subscribe(
      (data) => {
        if (data.message != 'ok') {
          this.greskaAzuriranje = data.message;
          return;
        }
        this.azuriranjeUspeh = "Nastavnik je uspesno deaktiviran.";
        setTimeout(() => {
          this.ngOnInit();
        }, 1000);
      }
    )
  }

  odobriRegistraciju(nastavnik: Korisnik) {
    this.korisnikServis.odobravanjeRegistracije(nastavnik).subscribe(
      (data) => {
        if (data.message != 'ok') {
          this.greskaRegistracija = data.message;
          return;
        }
        this.uspehRegistracija = "Nastavnik je uspesno odobren!";
        setTimeout(() => {
          this.ngOnInit();
        }, 1000);
      }
    )
  }

  odbijRegistraciju(nastavnik: Korisnik) {
    this.korisnikServis.odbijanjeRegistracije(nastavnik).subscribe(
      (data) => {
        if (data.message != 'ok') {
          this.greskaRegistracija = data.message;
          return;
        }
        this.uspehRegistracija = "Nastavnik je uspesno odbijen!";
        setTimeout(() => {
          this.ngOnInit();
        }, 1000);
      }
    )
  }

}
