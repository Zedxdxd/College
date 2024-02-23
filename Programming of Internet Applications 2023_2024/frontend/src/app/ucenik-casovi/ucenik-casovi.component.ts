import { Component, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { CasService } from '../services/cas.service';
import { Korisnik } from '../models/korisnik';
import { Cas } from '../models/cas';
import { NgbModal, NgbModalRef } from '@ng-bootstrap/ng-bootstrap';
import { Router } from '@angular/router';

@Component({
  selector: 'app-ucenik-casovi',
  templateUrl: './ucenik-casovi.component.html',
  styleUrls: ['./ucenik-casovi.component.css']
})
export class UcenikCasoviComponent implements OnInit {

  @ViewChild('modalOcena', { static: true }) modalOcena!: TemplateRef<any>;
  ulogovan: Korisnik = new Korisnik();
  prosliCasovi: Cas[] = [];
  buduciCasovi: Cas[] = [];
  selektovanCas: Cas = new Cas();
  otvorenModal: NgbModalRef | null = null;
  ocena: number = 1;
  greskaOcena: string = "";
  currDate: Date = new Date();


  constructor(private router: Router,
    private modal: NgbModal,
    private casServis: CasService) { }

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

    this.prosliCasovi = [];
    this.buduciCasovi = [];
    this.casServis.dohvatiCasoveUcenika(this.ulogovan.korime).subscribe(
      (data) => {
        data.forEach(cas => {
          cas.start = new Date(cas.start);
          if (cas.start <= this.currDate) {
            this.prosliCasovi.push(cas);
          }
          else {
            this.buduciCasovi.push(cas);
          }
        });
        this.prosliCasovi = this.prosliCasovi.reverse();
      }
    )
  }

  formaOceniNastavnika(cas: Cas) {
    this.greskaOcena = "";
    this.selektovanCas = cas;
    this.otvorenModal = this.modal.open(this.modalOcena, { size: 'md' });
  }

  oceniNastavnika() {
    this.selektovanCas.ocenaZaNastavnika = this.ocena;

    this.casServis.oceniNastavnika(this.selektovanCas).subscribe(
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

  otvoriSastanak(cas: Cas){
    // window.open("https://allo.bim.land/" + cas._id)
    window.open("https://meet.jit.si/" + cas.ucenik.korime + cas.nastavnik.korime + cas.start.valueOf())
  }
}
