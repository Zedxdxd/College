import { Component, OnDestroy, OnInit } from '@angular/core';
import { Korisnik } from '../models/korisnik';
import { FileService } from '../services/file.service';
import { CasService } from '../services/cas.service';
import { Cas } from '../models/cas';
import { Router } from '@angular/router';

@Component({
  selector: 'app-nastavnik-detalji',
  templateUrl: './nastavnik-detalji.component.html',
  styleUrls: ['./nastavnik-detalji.component.css']
})
export class NastavnikDetaljiComponent implements OnInit, OnDestroy {

  nastavnik: Korisnik = new Korisnik();
  profilnaUrl: string = "";
  ulogovan: Korisnik = new Korisnik();
  casoviNastavnika: Cas[] = [];

  izabranPredmet: string = "";
  datumVreme: Date | null = null;
  opis: string = "";
  dupli: boolean = false;

  greskaIzabranPredmet: string = "";
  greskaIzabranDatum: string = "";
  greskaOpis: string = "";
  greskaZakazivanje: string = "";
  brGresaka: number = 0;
  uspehZakazivanje: string = "";

  constructor(private router: Router,
    private fileServis: FileService,
    private casServis: CasService) { }

  ngOnDestroy(): void {
    localStorage.removeItem("nastavnik");
  }

  ngOnInit(): void {
    let x = localStorage.getItem('nastavnik');
    if (x != null) {
      this.nastavnik = JSON.parse(x);
    }
    x = localStorage.getItem('ulogovan');
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

    this.fileServis.dohvatiUrlSlika(this.nastavnik.profilna).subscribe(
      (data) => {
        this.profilnaUrl = data.imageUrl;
      }
    )

    if (this.nastavnik.predajePredmete.length == 1) {
      this.izabranPredmet = this.nastavnik.predajePredmete[0];
    }

    this.casServis.dohvatiCasoveNastavnika(this.nastavnik.korime).subscribe(
      (data) => {
        this.casoviNastavnika = data.filter(cas => cas.ocenaZaNastavnika != 0);
      }
    )

  }

  formatDate(date: Date): string {
    // Format date as 'yyyy-MM-ddThh:mm'
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${day}.${month}.${year} ${hours}:${minutes}`;
  }

  zakazi() {
    this.brGresaka = 0;
    if (this.izabranPredmet == "") {
      this.brGresaka++;
      this.greskaIzabranPredmet = "Predmet je obavezno polje.";
    }
    else {
      this.greskaIzabranPredmet = "";
    }

    if (this.datumVreme == null) {
      this.brGresaka++;
      this.greskaIzabranDatum = "Datum i vreme casa je obavezno polje.";
    }
    else if (new Date(this.datumVreme).getMinutes() != 30 && new Date(this.datumVreme).getMinutes() != 0) {
      this.brGresaka++;
      this.greskaIzabranDatum = "Cas mora da pocne na pola sata ili na pun sat."
    }
    else if (new Date() >= this.datumVreme) {
      this.brGresaka++;
      this.greskaIzabranDatum = "Cas mora biti zakazan bar sat vremena pre njegovog pocetka.";
    }
    else if (new Date(this.datumVreme).valueOf() - new Date().valueOf() <= 60 * 60 * 1000) {
      this.brGresaka++;
      this.greskaIzabranDatum = "Cas mora biti zakazan bar sat vremena pre njegovog pocetka.";
    }
    else {
      this.greskaIzabranDatum = "";
    }

    if (this.brGresaka > 0) {
      return;
    }
    this.greskaZakazivanje = "";

    if (this.datumVreme == null) {
      return;
    }

    let cas = new Cas();
    cas.start = new Date(this.datumVreme);
    if (this.dupli) {
      cas.end = new Date(cas.start.valueOf() + 2 * 60 * 60 * 1000)
    }
    else {
      cas.end = new Date(cas.start.valueOf() + 60 * 60 * 1000)
    }
    cas.opis = this.opis;
    cas.nastavnik = this.nastavnik;
    cas.ucenik = this.ulogovan;
    cas.predmet = this.izabranPredmet;
    cas.status = "cekanje";

    this.casServis.dodavanjeCas(cas).subscribe(
      (data) => {
        if (data.message != 'ok') {
          this.greskaZakazivanje = data.message + '<br>Radno vreme te nedelje: <br>';
          this.casServis.dohvatiRadnaVremena(this.nastavnik.korime, cas.start).subscribe(
            (radnaVremena) => {
              radnaVremena.forEach(radnoVreme => {
                this.greskaZakazivanje += this.formatDate(new Date(radnoVreme.start)) + " - ";
                this.greskaZakazivanje += this.formatDate(new Date(radnoVreme.end)) + "<br>";
              });
              this.greskaZakazivanje += "Ostalim radnim danima mu je radno vreme 10-18h, a vikendom ne radi.<br>";

              this.greskaZakazivanje += "Casovi te nedelje: <br>";
              this.casServis.dohvatiCasoveNedelja(this.nastavnik.korime, cas.start).subscribe(
                (casovi) => {
                  casovi.forEach(cas => {
                    this.greskaZakazivanje += this.formatDate(new Date(cas.start)) + " - ";
                    if (cas.end === undefined) return;
                    this.greskaZakazivanje += this.formatDate(new Date(cas.end)) + "<br>";
                  });
                }
              )
            }
          )
          return;
        }
        this.uspehZakazivanje = "Uspesno poslat zahtev za zakazivanje. Bicete obavesteni kada nastavnik potvrdi cas.";
        // setTimeout(() => {
        //   this.ngOnInit();
        // }, 2000);
        window.location.reload();
      }
    )
  }
}
