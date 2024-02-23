import { Component, OnInit } from '@angular/core';
import { Korisnik } from '../models/korisnik';
import { KorisnikService } from '../services/korisnik.service';
import { Router } from '@angular/router';
import { PredmetService } from '../services/predmet.service';
import { Predmet } from '../models/predmet';
import { CasService } from '../services/cas.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  korime: string = "";
  lozinka: string = "";
  greskaLogin = "";
  sviPredmeti: Predmet[] = [];

  nazivPredmeta: string = "";
  imeNastavnika: string = "";
  prezimeNastavnika: string = "";

  brojUcenika: number = 0;
  brojNastavnika: number = 0;
  brojOdrzanihCasovaSedamDana: number = 0;
  brojOdrzanihCasovaMesecDana: number = 0;

  constructor(private router: Router,
    private korisnikServis: KorisnikService,
    private predmetServis: PredmetService,
    private casServis: CasService) { }

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
    this.predmetServis.dohvatiSvePredmete().subscribe(
      (data) => {
        this.sviPredmeti = data;
        this.sviPredmeti.forEach(pr => {
          let tmp = pr.nastavnici.filter(nast => nast.odobrenaRegistracija == "da");
          pr.nastavnici = tmp;
        })
      }
    )

    this.korisnikServis.dohvatiSveUcenike().subscribe(
      (data) => {
        this.brojUcenika = data.length;
      }
    )

    this.korisnikServis.dohvatiSveOdobreneNastavnike().subscribe(
      (data) => {
        this.brojNastavnika = data.length;
      }
    )

    this.casServis.odrzaniCasoviSedamDana().subscribe(
      (data) => {
        this.brojOdrzanihCasovaSedamDana = data.broj
      }
    )

    this.casServis.odrzaniCasoviMesecDana().subscribe(
      (data) => {
        this.brojOdrzanihCasovaMesecDana = data.broj
      }
    )
  }

  sortPredmeti(asc: boolean) {
    if (asc) {
      this.sviPredmeti.sort((a, b) => {
        if (a.naziv < b.naziv) return -1;
        else if (a.naziv == b.naziv) return 0;
        else return 1;
      });
    }
    else {
      this.sviPredmeti.sort((a, b) => {
        if (a.naziv < b.naziv) return 1;
        else if (a.naziv == b.naziv) return 0;
        else return -1;
      });
    }
  }

  sortIme(predmet: Predmet, asc: boolean) {
    if (asc) {
      predmet.nastavnici.sort((a, b) => {
        if (a.ime < b.ime) return -1;
        else if (a.ime == b.ime) return 0;
        else return 1;
      })
    }
    else {
      predmet.nastavnici.sort((a, b) => {
        if (a.ime < b.ime) return 1;
        else if (a.ime == b.ime) return 0;
        else return -1;
      })
    }
  }

  sortPrezime(predmet: Predmet, asc: boolean) {
    if (asc) {
      predmet.nastavnici.sort((a, b) => {
        if (a.prezime < b.prezime) return -1;
        else if (a.prezime == b.prezime) return 0;
        else return 1;
      })
    }
    else {
      predmet.nastavnici.sort((a, b) => {
        if (a.prezime < b.prezime) return 1;
        else if (a.prezime == b.prezime) return 0;
        else return -1;
      })
    }
  }

  login() {
    if (this.korime == "" && this.lozinka == "") {
      this.greskaLogin = "Korisnicko ime i lozinka su obavezna polja.";
      return;
    }
    else if (this.korime == "") {
      this.greskaLogin = "Korisnicko ime je obavezno polje.";
      return;
    }
    else if (this.lozinka == "") {
      this.greskaLogin = "Lozinka je obavezno polje.";
      return;
    }

    this.korisnikServis.login(this.korime, this.lozinka).subscribe(
      (data) => {
        if (data == null) {
          this.greskaLogin = "Korisnik sa tim kredencijalima ne postoji.";
          return;
        }
        if (data.tip == "ucenik") {
          localStorage.setItem("ulogovan", JSON.stringify(data));
          this.router.navigate(['/ucenikProfil']);
        }
        else if (data.tip == "nastavnik") {
          if (data.odobrenaRegistracija == "ne") {
            this.greskaLogin = "Administrator vam jos nije odobrio registraciju.";
          }
          else if (data.odobrenaRegistracija == "odbijen") {
            this.greskaLogin = "Administrator je odbio vas zahtev za registracijom.";
          }
          else if (data.odobrenaRegistracija == "deaktiviran") {
            this.greskaLogin = "Administrator je deaktivirao vas nalog.";
          }
          else {
            localStorage.setItem("ulogovan", JSON.stringify(data));
            this.router.navigate(['nastavnikProfil']);
          }
        }
      }
    )
  }
}
