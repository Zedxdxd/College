import { Component, OnInit } from '@angular/core';
import { Korisnik } from '../models/korisnik';
import { FileService } from '../services/file.service';
import { KorisnikService } from '../services/korisnik.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-ucenik-profil',
  templateUrl: './ucenik-profil.component.html',
  styleUrls: ['./ucenik-profil.component.css']
})
export class UcenikProfilComponent implements OnInit {

  ulogovan: Korisnik = new Korisnik();
  profilnaUrl: string = "";

  biloPromene: boolean = false;
  brGresaka: number = 0;
  greskaIme: string = "";
  greskaPrezime: string = "";
  greskaAdresa: string = "";
  greskaTelefon: string = "";
  greskaEmail: string = "";
  // ako slucajno inkrementuje iz 8. raz u 1. raz pa mu kazem e brt ili vrati na 8. raz ili selektuj neku srednju sk
  greskaTipSkole: string = "Potrebno je promeniti tip skole na srednju.";
  greskaRazred: string = "Potrebno je biti razred 1-4 za srednju skolu.";
  greskaAzuriranje = "";
  azuriranjeUspeh = "";
  SlikaFajl: File | null = null;

  inkrementiran: boolean = false;
  potrebnaPromenaSkole: boolean = false;

  constructor(private router: Router,
    private fileServis: FileService,
    private korisnikServis: KorisnikService) { }

  ngOnInit(): void {
    this.azuriranjeUspeh = "";
    this.inkrementiran = false;
    this.potrebnaPromenaSkole = false;
    this.greskaAzuriranje = "";
    this.biloPromene = false;
    let x = localStorage.getItem('ulogovan');
    if (x != null){
      this.ulogovan = JSON.parse(x);
      if (this.ulogovan.tip == "nastavnik"){
        this.router.navigate(['nastavnikProfil']);
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
    // this.ulogovan.razred = 8;
    // this.ulogovan.tipSkole = "osn";
    if (this.ulogovan.tipSkole.startsWith("sr") && this.ulogovan.razred == 4) {
      this.inkrementiran = true;
    }
  }

  izaberiProfilnu() {
    document.getElementById('inputProfilna')?.click();
  }

  onFileSelected(event: any) {
    this.SlikaFajl = event.target.files[0];
    this.biloPromene = true;
  }

  povecajRazred() {
    this.inkrementiran = true;
    this.ulogovan.razred++;
    if (this.ulogovan.razred == 9) {
      this.ulogovan.razred = 1;
      this.potrebnaPromenaSkole = true;
    }
    this.promena();
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
  }


  sacuvajIzmene() {
    this.korisnikServis.azuriranjeUcenika(this.ulogovan).subscribe(
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
}
