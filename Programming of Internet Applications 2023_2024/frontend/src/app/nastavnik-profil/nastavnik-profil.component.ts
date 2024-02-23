import { Component, OnInit } from '@angular/core';
import { Korisnik } from '../models/korisnik';
import { FileService } from '../services/file.service';
import { KorisnikService } from '../services/korisnik.service';
import { PredmetService } from '../services/predmet.service';
import { Predmet } from '../models/predmet';
import { Router } from '@angular/router';

@Component({
  selector: 'app-nastavnik-profil',
  templateUrl: './nastavnik-profil.component.html',
  styleUrls: ['./nastavnik-profil.component.css']
})
export class NastavnikProfilComponent implements OnInit {

  ulogovan: Korisnik = new Korisnik();
  profilnaUrl: string = "";

  biloPromene: boolean = false;
  brGresaka: number = 0;
  greskaIme: string = "";
  greskaPrezime: string = "";
  greskaAdresa: string = "";
  greskaTelefon: string = "";
  greskaEmail: string = "";
  greskaPredmeti: string = "";
  greskaUzrast: string = "";

  greskaAzuriranje = "";
  azuriranjeUspeh = "";
  SlikaFajl: File | null = null;

  sviPredmeti: Predmet[] = [];

  uzrastOsn14: boolean = false;
  uzrastOsn58: boolean = false;
  uzrastSr14: boolean = false;

  constructor(private router: Router,
    private fileServis: FileService,
    private korisnikServis: KorisnikService,
    private predmetServis: PredmetService) { }

  ngOnInit(): void {
    this.azuriranjeUspeh = "";
    this.biloPromene = false;
    this.greskaAzuriranje = "";
    let x = localStorage.getItem('ulogovan');
    if (x != null){
      this.ulogovan = JSON.parse(x);
      if (this.ulogovan.tip == "ucenik"){
        this.router.navigate(['ucenikProfil']);
      }
      else if (this.ulogovan.tip == "admin"){
        this.router.navigate(['adminPocetna']);
      }
    }
    else {
      this.router.navigate(['']);
    }

    this.fileServis.dohvatiUrlSlika(this.ulogovan.profilna).subscribe(
      (data) => {
        this.profilnaUrl = data.imageUrl;
      }
    )
    this.predmetServis.dohvatiSvePredmete().subscribe(
      (data) => {
        this.sviPredmeti = data;
        for (let i = 0; i < this.sviPredmeti.length; i++) {
          let predmet = this.ulogovan.predajePredmete.find(element => element == this.sviPredmeti[i].naziv);
          if (predmet !== undefined) {
            this.sviPredmeti[i].selektovan = true;
          }
        }
      }
    )

    if (this.ulogovan.predajeUzrastu.find(element => element == "osn14")) {
      this.uzrastOsn14 = true;
    }
    if (this.ulogovan.predajeUzrastu.find(element => element == "osn58")) {
      this.uzrastOsn58 = true;
    }
    if (this.ulogovan.predajeUzrastu.find(element => element == "sr14")) {
      this.uzrastSr14 = true;
    }
  }

  promena() {
    this.brGresaka = 0;
    this.biloPromene = true;

    if (this.ulogovan.ime == "") {
      this.brGresaka++;
      this.greskaIme = "Ime je obavezno polje.";
    }
    else {
      this.greskaIme = "";
    }
    if (this.ulogovan.prezime == "") {
      this.brGresaka++;
      this.greskaPrezime = "Prezime je obavezno polje.";
    }
    else {
      this.greskaPrezime = "";
    }
    if (this.ulogovan.adresa == "") {
      this.brGresaka++;
      this.greskaAdresa = "Adresa je obavezno polje.";
    }
    else {
      this.greskaAdresa = "";
    }
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (this.ulogovan.email == "") {
      this.brGresaka++;
      this.greskaEmail = "Email je obavezno polje.";
    }
    else if (!emailRegex.test(this.ulogovan.email)) {
      this.brGresaka++;
      this.greskaEmail = "Email mora biti u formatu example@example.example";
    }
    else {
      this.greskaEmail = "";
    }

    const telefonRegex = /^\d{3}-\d{3}-\d{2}-\d{2}$/;
    if (this.ulogovan.telefon == "") {
      this.brGresaka++;
      this.greskaTelefon = "Telefon je obavezno polje."
    }
    else if (!telefonRegex.test(this.ulogovan.telefon)) {
      this.brGresaka++;
      this.greskaTelefon = "Telefon mora biti u formatu ddd-ddd-dd-dd, gde je d cifra";
    }
    else {
      this.greskaTelefon = "";
    }

    this.ulogovan.predajePredmete = [];
    for (let i = 0; i < this.sviPredmeti.length; i++) {
      if (this.sviPredmeti[i].selektovan) {
        this.ulogovan.predajePredmete.push(this.sviPredmeti[i].naziv);
      }
    }

    if (this.ulogovan.predajePredmete.length == 0) {
      this.brGresaka++;
      this.greskaPredmeti = "Mora da se izabere bar jedan predmet sa liste.";
    }
    else {
      this.greskaPredmeti = "";
    }

    this.ulogovan.predajeUzrastu = [];
    if (this.uzrastOsn14) {
      this.ulogovan.predajeUzrastu.push("osn14");
    }
    if (this.uzrastOsn58) {
      this.ulogovan.predajeUzrastu.push("osn58");
    }
    if (this.uzrastSr14) {
      this.ulogovan.predajeUzrastu.push("sr14");
    }

    if (this.ulogovan.predajeUzrastu.length == 0) {
      this.brGresaka++;
      this.greskaUzrast = "Uzrast je obavezno polje.";
    }
    else {
      this.greskaUzrast = "";
    }

  }

  sacuvajIzmene() {
    this.korisnikServis.azuriranjeNastavnika(this.ulogovan).subscribe(
      (data) => {
        if (data.message != 'ok') {
          this.greskaAzuriranje = data.message;
          return;
        }
        if (this.SlikaFajl != null) {
          this.korisnikServis.azuriranjeProfilne(this.ulogovan, this.SlikaFajl).subscribe(
            (data1) => {
              if (data1.message != 'ok') {
                this.greskaAzuriranje = data1.message;
                return;
              }
              localStorage.setItem("ulogovan", JSON.stringify(data1));
              this.azuriranjeUspeh = "Profil je uspesno izmenjen.";
              setTimeout(() => {
                this.ngOnInit();
              }, 2000);
            }
          )
        }
        else {
          localStorage.setItem("ulogovan", JSON.stringify(this.ulogovan));
          this.azuriranjeUspeh = "Profil je uspesno izmenjen.";
          setTimeout(() => {
            this.ngOnInit();
          }, 2000);
        }
      }
    )
  }

  izaberiProfilnu() {
    document.getElementById('inputProfilna')?.click();
  }

  onFileSelected(event: any) {
    this.SlikaFajl = event.target.files[0];
    this.biloPromene = true;
  }

}
