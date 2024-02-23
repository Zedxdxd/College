import { Component, OnDestroy, OnInit } from '@angular/core';
import { CasService } from '../services/cas.service';
import { Korisnik } from '../models/korisnik';
import { Cas } from '../models/cas';
import { HttpBackend } from '@angular/common/http';
import { Router } from '@angular/router';

@Component({
  selector: 'app-ucenik-obavestenja',
  templateUrl: './ucenik-obavestenja.component.html',
  styleUrls: ['./ucenik-obavestenja.component.css']
})
export class UcenikObavestenjaComponent implements OnInit{

  ulogovan: Korisnik = new Korisnik();
  svaObavestenja: Cas[] = [];

  constructor(private router: Router,
    private casServis: CasService) {}

  ngOnInit() {
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

    this.casServis.dohvatiObavestenjaUcenika(this.ulogovan.korime).subscribe(
      (data) => {
        this.svaObavestenja = data;
        this.casServis.oznaciProcitano(this.ulogovan.korime).subscribe()
      }
    )
  }

  formatDate(date: Date): string {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${day}.${month}.${year} ${hours}:${minutes}`;
  }
}
