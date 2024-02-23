import { Component, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { Korisnik } from '../models/korisnik';
import { Cas } from '../models/cas';
import { CasService } from '../services/cas.service';
import { NgbModal, NgbModalRef } from '@ng-bootstrap/ng-bootstrap';
import { KorisnikService } from '../services/korisnik.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-nastavnik-casovi',
  templateUrl: './nastavnik-casovi.component.html',
  styleUrls: ['./nastavnik-casovi.component.css']
})
export class NastavnikCasoviComponent implements OnInit {

  @ViewChild('modalOtkazivanje', { static: true }) modalOtkazivanje!: TemplateRef<any>;
  @ViewChild('modalOdbijanje', { static: true }) modalOdbijanje!: TemplateRef<any>;

  ulogovan: Korisnik = new Korisnik();
  sviCasovi: Cas[] = [];
  casovi3Dana: Cas[] = [];
  zahteviCasovi: Cas[] = [];
  currDate: Date = new Date();
  brojCasova: number = 5;
  prosecneOcene: Map<string, number> = new Map();

  otvorenModal: NgbModalRef | null = null;
  selektovanCas: Cas = new Cas();
  strDatumPocetak: string = "";
  greskaOtkaz: string = "";

  datum: string | null = null;
  greskaDatum: string = "";
  vremeOd: string | null = null;
  greskaVremeOd: string = "";
  greskaVremeDo: string = "";
  vremeDo: string | null = null;
  greskaRadnoVreme: string = "";

  constructor(private router: Router,
    private modal: NgbModal,
    private casServis: CasService,
    private korisnikServis: KorisnikService) { }

  ngOnInit(): void {
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

    this.casServis.dohvatiCasoveNastavnika(this.ulogovan.korime).subscribe(
      (data) => {
        this.sviCasovi = data;
        this.sviCasovi.forEach(cas => cas.start = new Date(cas.start));
        this.casovi3Dana = this.sviCasovi.filter(cas => {
          return cas.status == 'potvrdjen' && new Date(cas.start) > this.currDate &&
            new Date(cas.start).valueOf() - this.currDate.valueOf() <= 3 * 24 * 60 * 60 * 1000;
        })
        this.zahteviCasovi = this.sviCasovi.filter(cas => {
          return cas.status == 'cekanje' && new Date(cas.start) > this.currDate
        })

        this.korisnikServis.izracunajProsecneOceneUcenika().subscribe(
          (data) => {
            data.forEach(elem => {
              this.prosecneOcene.set(elem.korime, elem.prosecnaOcena);
            });
            this.zahteviCasovi.forEach(p => {
              let x = this.prosecneOcene.get(p.ucenik.korime);
              if (x !== undefined) {
                p.ucenik.prosecnaOcena =x;
              }
            })
          }
        )
      }
    )
  }

  formatDate(date: Date): string {
    // Format date as 'yyyy-MM-ddThh:mm'
    return date.toISOString().slice(0, 16);
  }

  formaOtkaziCas(cas: Cas) {
    this.selektovanCas = cas;
    this.strDatumPocetak = this.formatDate(cas.start);
    this.otvorenModal = this.modal.open(this.modalOtkazivanje, { size: 'md' });
  }

  formaOdbijCas(cas: Cas){
    this.selektovanCas = cas;
    this.strDatumPocetak = this.formatDate(cas.start);
    this.otvorenModal = this.modal.open(this.modalOdbijanje, { size: 'md' });
  }

  otkaziCas(){
    this.selektovanCas.status = "otkazan";
    this.casServis.promeniStatusCasa(this.selektovanCas).subscribe(
      (data) => {
        if (data.message == "ok"){
          this.otvorenModal?.close();
          window.location.reload()
        }
        else {
          this.greskaOtkaz = data.message;
        }
      }
    )
  }

  odbijCas(){
    this.selektovanCas.status = "odbijen";
    this.casServis.promeniStatusCasa(this.selektovanCas).subscribe(
      (data) => {
        if (data.message == "ok"){
          this.otvorenModal?.close();
          window.location.reload()
        }
        else {
          this.greskaOtkaz = data.message;
        }
      }
    )
  }

  prihvatiCas(cas: Cas) {
    cas.status = "potvrdjen";
    this.casServis.promeniStatusCasa(cas).subscribe(
      (data) => {
        window.location.reload();
      }
    )
  }

  definisiRadnoVreme(){
    this.greskaRadnoVreme = "";
    let brGresaka = 0;
    if (this.datum == null){
      this.greskaDatum = "Datum je obavezno polje.";
      brGresaka++;
    }
    else {
      this.greskaDatum = "";
    }
    if (this.vremeOd == null){
      this.greskaVremeOd = "Vreme od je obavezno polje.";
      brGresaka++;
    }
    else {
      this.greskaVremeOd = "";
    }
    if (this.vremeDo == null) {
      this.greskaVremeDo = "Vreme do je obavezno polje.";
      brGresaka++;
    }
    else {
      this.greskaVremeDo = "";
    }

    if (brGresaka > 0){
      this.greskaRadnoVreme = "Neka polja u formi nisu popunjena.";
      return;
    }

    if (this.datum == null || this.vremeOd == null || this.vremeDo == null){
      return;
    }

    let datumPocetka: Date = new Date(this.datum);
    let datumKraja: Date = new Date(this.datum);

    if (datumPocetka <= new Date()){
      this.greskaRadnoVreme = "Mozete definisati radno vreme samo za buduce dane."
      return;
    }

    datumPocetka.setHours(Number.parseInt(this.vremeOd.split(":")[0]));
    datumPocetka.setMinutes(Number.parseInt(this.vremeOd.split(":")[1]));
    datumKraja.setHours(Number.parseInt(this.vremeDo.split(":")[0]));
    datumKraja.setMinutes(Number.parseInt(this.vremeDo.split(":")[1]));

    if (datumKraja < datumPocetka){
      this.greskaRadnoVreme = "Vreme do mora biti posle vremena od.";
    }

    let datumGranicaStart = new Date(datumKraja);
    datumGranicaStart.setHours(1);
    datumGranicaStart.setMinutes(0);
    datumGranicaStart.setSeconds(0);
    datumGranicaStart.setMilliseconds(0);
    let datumGranicaEnd = new Date(datumKraja);
    datumGranicaEnd.setHours(23);
    datumGranicaEnd.setMinutes(0);
    datumGranicaEnd.setSeconds(0);
    datumGranicaEnd.setMilliseconds(0);

    if (datumPocetka < datumGranicaStart) {
      this.greskaRadnoVreme = "Radno vreme moze najranije poceti u 01:00.";
      return;
    }
    if (datumKraja > datumGranicaEnd) {
      this.greskaRadnoVreme = "Radno vreme moze najkasnije da se zavrsi u 23:00.";
      return;
    }

    this.casServis.dodajRadnoVreme(datumPocetka, datumKraja, this.ulogovan).subscribe(
      (data) => {
        if (data.message == "ok"){
          window.location.reload();
        }
        else {
          this.greskaRadnoVreme = data.message;
        }
      }
    )
  }

  otvoriSastanak(cas: Cas){
    // window.open("https://allo.bim.land/" + cas._id)
    window.open("https://meet.jit.si/" + cas.ucenik.korime + cas.nastavnik.korime + cas.start.valueOf())
  }
}
