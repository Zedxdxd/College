import { Component, ElementRef, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { Cas } from '../models/cas';
import { Korisnik } from '../models/korisnik';
import { CasService } from '../services/cas.service';
import { NgbModal, NgbModalRef } from '@ng-bootstrap/ng-bootstrap';
import { Router } from '@angular/router';

@Component({
  selector: 'app-nastavnik-moji-ucenici',
  templateUrl: './nastavnik-moji-ucenici.component.html',
  styleUrls: ['./nastavnik-moji-ucenici.component.css']
})
export class NastavnikMojiUceniciComponent implements OnInit{
  @ViewChild('scrollTarget') scrollTarget?: ElementRef;
  @ViewChild('modalOcena', { static: true }) modalOcena!: TemplateRef<any>;

  ulogovan: Korisnik = new Korisnik();
  sviCasovi: Cas[] = [];
  mapaUcenici: Map<string, Korisnik> = new Map();

  selektovanUcenik: Korisnik = new Korisnik();
  ucenikCasovi: Cas[] = [];
  selektovanCas: Cas = new Cas();
  ocena: number = 1;
  greskaOcena: string = "";
  otvorenModal: NgbModalRef | null = null;

  constructor(private router: Router,
    private modal: NgbModal,
    private casServis: CasService) {}

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

    this.casServis.dohvatiOdrzaneCasoveNastavnika(this.ulogovan.korime).subscribe(
      (data) => {
        this.sviCasovi = data;
        this.sviCasovi.forEach(cas => {
          this.mapaUcenici.set(cas.ucenik.korime, cas.ucenik);
        });
        console.log(this.mapaUcenici);
      }
    )
  }

  selektuj(u: Korisnik) {
    this.selektovanUcenik = u;

    this.ucenikCasovi = this.sviCasovi.filter(cas => cas.ucenik.korime == this.selektovanUcenik.korime);

    const targetElement = this.scrollTarget?.nativeElement;
    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  formaOceniUcenika(cas: Cas) {
    this.greskaOcena = "";
    this.selektovanCas = cas;
    this.otvorenModal = this.modal.open(this.modalOcena, { size: 'md' });
  }

  oceniUcenika() {
    this.selektovanCas.ocenaZaUcenika = this.ocena;

    this.casServis.oceniUcenika(this.selektovanCas).subscribe(
      (data) => {
        if (data.message == "ok") {
          this.otvorenModal?.close();
          this.ngOnInit();
        }
        else {
          this.greskaOcena = data.message;
        }
      }
    )
  }
}
