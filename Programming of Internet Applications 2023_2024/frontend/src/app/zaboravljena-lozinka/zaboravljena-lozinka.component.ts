import { Component, OnInit } from '@angular/core';
import { KorisnikService } from '../services/korisnik.service';
import { Korisnik } from '../models/korisnik';
import { Router } from '@angular/router';

@Component({
  selector: 'app-zaboravljena-lozinka',
  templateUrl: './zaboravljena-lozinka.component.html',
  styleUrls: ['./zaboravljena-lozinka.component.css']
})
export class ZaboravljenaLozinkaComponent implements OnInit{

  korime: string = "";
  greskaKorime: string = "";
  korisnik: Korisnik = new Korisnik();
  odgovor: string = "";
  greskaOdgovor: string = "";
  tacanOdgovor: boolean = false;
  lozinka: string = "";
  ponovljenaLozinka: string = "";
  greskaLozinka: string = "";
  greskaPonovljenaLozinka: string = "";
  brGresaka: number = 0;
  promenaUspeh: string = "";
  promenaGreska: string = "";

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
  }

  constructor(private router: Router,
    private korisnikServis: KorisnikService) {}

  otvoriPitanje(){
    this.tacanOdgovor = false;
    this.korisnik = new Korisnik();
    if (this.korime == ""){
      this.greskaKorime = "Korisnicko ime je obavezno polje.";
      return;
    }
    this.korisnikServis.dohvatiKorisnika(this.korime).subscribe(
      (data) => {
        if (data == null) {
          this.greskaKorime = "Ne postoji korisnik sa tim korisnickim imenom.";
          return;
        }
        this.greskaKorime = "";
        this.korisnik = data;
      }
    )
  }

  dajOdgovor(){
    const odgovorRegex = /^(0[1-9]|1[0-2])\/\d{4}$/;
    this.tacanOdgovor = false;
    if (this.odgovor == ""){
      this.greskaOdgovor = "Odgovor nije unet.";
      return;
    }
    else if (this.korisnik.pitanje == "Koji je mesec i godina venčanja vaših roditelja? (MM/YYYY)" &&
      !odgovorRegex.test(this.odgovor)) {
      this.greskaOdgovor = "Odgovor nije u dobrom formatu.";
      return;
    }
    else if (this.odgovor != this.korisnik.odgovor){
      this.greskaOdgovor = "Odgovor nije tacan.";
      return;
    }
    this.greskaOdgovor = "";
    this.tacanOdgovor = true;
  }

  promeniLozinku(){
    this.brGresaka = 0;
    const lozinkaRegex = /^(?=.*[A-Z])(?=.*[a-z]{3,})(?=.*\d)(?=.*[^a-zA-Z\d])([a-zA-Z].{5,9})$/;
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
    if (this.brGresaka > 0){
      return;
    }
    this.korisnikServis.promenaLozinke(this.korisnik.korime, this.lozinka).subscribe(
      (data) => {
        if (data.message != 'ok') {
          this.promenaGreska = data.message;
        }
        else {
          this.promenaUspeh = "Uspesna promena lozinke. Povratak na pocetnu stranu.";
          setTimeout(() => {
            this.router.navigate(['']);
          }, 2000);
        }
      }
    )
  }
}
