import { Component, OnInit } from '@angular/core';
import { Predmet } from '../models/predmet';
import { Korisnik } from '../models/korisnik';
import { KorisnikService } from '../services/korisnik.service';
import { PredmetService } from '../services/predmet.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-nastavnici',
  templateUrl: './nastavnici.component.html',
  styleUrls: ['./nastavnici.component.css']
})
export class NastavniciComponent implements OnInit{

  sviPredmeti: Predmet[] = [];
  ulogovan: Korisnik = new Korisnik();
  prosecneOcene: Map<string, number> = new Map();

  nazivPredmeta: string = "";
  imeNastavnika: string = "";
  prezimeNastavnika: string = "";


  constructor(private router: Router,
    private korisnikServis: KorisnikService,
    private predmetServis: PredmetService) {}

  ngOnInit(): void {
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

    this.predmetServis.dohvatiSvePredmete().subscribe(
      (data) => {
        this.sviPredmeti = data;
        this.sviPredmeti.forEach(pr => {
          let tmp = pr.nastavnici.filter(nast => nast.odobrenaRegistracija == "da");
          pr.nastavnici = tmp;
        })

        this.korisnikServis.izracunajProsecneOceneNastavnika().subscribe(
          (data) => {
            data.forEach(elem => {
              this.prosecneOcene.set(elem.korime, elem.prosecnaOcena);
            });
            this.sviPredmeti.forEach(p => {
              p.nastavnici.forEach(n => {
                let x = this.prosecneOcene.get(n.korime);
                if (x !== undefined) {
                  n.prosecnaOcena = x;
                }
              })
            })
          }
        )
      }
    )



    if (this.ulogovan.tipSkole == "osn"){
      if (this.ulogovan.razred >= 1 && this.ulogovan.razred <= 4){
        this.ulogovan.uzrast = "osn14";
      }
      else {
        this.ulogovan.uzrast = "osn58";
      }
    }
    else {
      this.ulogovan.uzrast = "sr14";
    }
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

  otvoriDetalje(nastavnik: Korisnik){
    localStorage.setItem('nastavnik', JSON.stringify(nastavnik));
    this.router.navigate(['nastavnikDetalji']);
  }
}
