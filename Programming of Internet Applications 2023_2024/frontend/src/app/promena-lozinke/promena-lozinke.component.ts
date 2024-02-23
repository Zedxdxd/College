import { Component, OnInit } from '@angular/core';
import { KorisnikService } from '../services/korisnik.service';
import { Router } from '@angular/router';
import { Korisnik } from '../models/korisnik';

@Component({
  selector: 'app-promena-lozinke',
  templateUrl: './promena-lozinke.component.html',
  styleUrls: ['./promena-lozinke.component.css']
})
export class PromenaLozinkeComponent implements OnInit{

  staraLozinka: string = "";
  lozinka: string = "";
  ponovljenaLozinka: string = "";
  greskaStaraLozinka: string = "";
  greskaLozinka: string = "";
  greskaPonovljenaLozinka: string = "";
  promenaUspeh: string = "";
  promenaGreska: string = "";
  brGresaka: number = 0;

  constructor(private router: Router,
    private korisnikServis: KorisnikService) { }

  ngOnInit(): void {
    let x = localStorage.getItem("ulogovan");
    if (x == null){
      this.router.navigate(['']);
    }
  }

  promeniLozinku() {
    this.brGresaka = 0;
    if (this.staraLozinka == "") {
      this.brGresaka++;
      this.greskaStaraLozinka = "Stara lozinka je obavezno polje.";
    }
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
    if (this.brGresaka > 0) {
      return;
    }

    let ulogovan: Korisnik = new Korisnik();
    let x = localStorage.getItem("ulogovan");
    if (x != null) [
      ulogovan = JSON.parse(x)
    ]
    this.korisnikServis.proveraLozinke(ulogovan.korime, this.staraLozinka).subscribe(
      (data) => {
        if (data.message != "ok") {
          this.promenaGreska = data.message;
          return;
        }
        this.promenaGreska = "";

        this.korisnikServis.promenaLozinke(ulogovan.korime, this.lozinka).subscribe(
          (message) => {
            if (message.message != "ok") {
              this.promenaGreska = message.message;
              return;
            }
            this.promenaUspeh = "Uspesna promena lozinke. Povratak na pocetnu stranu.";
            localStorage.removeItem("ulogovan");
            setTimeout(() => {
              this.router.navigate(['']);
            }, 2000);
          }
        )

      }
    )

  }
}
