import { Component, OnInit, ChangeDetectionStrategy, ViewChild, TemplateRef } from '@angular/core';
import { addHours, startOfWeek, addDays, subWeeks, addWeeks, subDays } from 'date-fns';
import { Subject } from 'rxjs';
import { CalendarView, CalendarDateFormatter, DAYS_OF_WEEK } from 'angular-calendar';
import { EventColor } from 'calendar-utils';
import { NgbModal, NgbModalRef } from '@ng-bootstrap/ng-bootstrap'
import { Korisnik } from '../models/korisnik';
import { Cas } from '../models/cas';
import { MilitaryDateFormatter } from '../military-date-formatter.provider';
import { CasService } from '../services/cas.service';

const colors: Record<string, EventColor> = {
  red: {
    primary: '#8B0000',
    secondary: '#FAE3E3',
  },
  blue: {
    primary: '#000000',
    secondary: '#D1E8FF',
  },
  yellow: {
    primary: '#FFD700',
    secondary: '#FDF1BA',
  },
  green: {
    primary: "#006400",
    secondary: "#bff5bc",
  }
};

@Component({
  selector: 'app-ucenik-kalendar',
  changeDetection: ChangeDetectionStrategy.OnPush,
  templateUrl: './ucenik-kalendar.component.html',
  styleUrls: ['./ucenik-kalendar.component.css'],
  providers: [
    {
      provide: CalendarDateFormatter,
      useClass: MilitaryDateFormatter
    }
  ]
})
export class UcenikKalendarComponent implements OnInit {

  // MOJ KOD START
  @ViewChild('modalZakazivanje', { static: true }) modalZakazivanje!: TemplateRef<any>;
  locale: string = 'gb';
  weekStartsOn: number = DAYS_OF_WEEK.MONDAY;
  view: CalendarView = CalendarView.Week;
  CalendarView = CalendarView;
  viewDate: Date = new Date();
  refresh = new Subject<void>();
  events: Cas[] = [];
  showingDate: Date = new Date(); // ovo je za previous i next da se dohvataju nedostupnosti
  otvorenModal: NgbModalRef | null = null;

  datumPocetak: Date = new Date();
  datumKraj: Date = new Date();
  strDatumPocetak: string = "";
  strDatumKraj: string = "";
  opis: string = "";
  izabranPredmet: string = "";
  nastavnik: Korisnik = new Korisnik();
  ulogovan: Korisnik = new Korisnik();
  greskaIzabranPredmet: string = "";
  greskaZakazivanje: string = "";


  formatDate(date: Date): string {
    // Format date as 'yyyy-MM-ddThh:mm'
    return date.toISOString().slice(0, 16);
  }

  constructor(private modal: NgbModal,
    private casServis: CasService) { }

  ngOnInit() {
    let x = localStorage.getItem('nastavnik');
    if (x != null) {
      this.nastavnik = JSON.parse(x);
    }
    x = localStorage.getItem("ulogovan");
    if (x != null) {
      this.ulogovan = JSON.parse(x);
    }
    if (this.nastavnik.predajePredmete.length == 1) {
      this.izabranPredmet = this.nastavnik.predajePredmete[0];
    }

    this.casServis.dohvatiCasoveNastavnika(this.nastavnik.korime).subscribe(
      (data) => {
        this.events = data;
        this.events.forEach(event => {
          event.start = new Date(event.start);
          if (event.end !== undefined) {
            event.end = new Date(event.end);
          }
          if (event.predmet !== undefined) {
            event.title = event.predmet;
          }
          if (event.status == "cekanje") {
            event.color = colors['yellow'];
          }
          else if (event.status == "potvrdjen") {
            event.color = colors['green'];
          }
        });

        this.osveziNedostupnosti(this.viewDate);
      }
    )
  }

  zakazi(event: any) {
    this.greskaIzabranPredmet = "";
    this.greskaZakazivanje = "";
    this.datumPocetak = addHours(event.date, 1);
    this.strDatumPocetak = this.formatDate(this.datumPocetak);
    this.datumKraj = addHours(this.datumPocetak, 1);
    this.strDatumKraj = this.formatDate(this.datumKraj);
    this.otvorenModal = this.modal.open(this.modalZakazivanje, { size: 'md' });
  }

  potvrdi() {
    if (this.izabranPredmet == "") {
      this.greskaIzabranPredmet = "Predmet je obavezno polje.";
      return;
    }
    else {
      this.greskaIzabranPredmet = "";
    }

    if (new Date() >= new Date(this.strDatumPocetak)) {
      this.greskaZakazivanje = "Cas mora biti zakazan bar sat vremena pre njegovog pocetka.";
    }
    else if (new Date(this.strDatumPocetak).valueOf() - new Date().valueOf() <= 60 * 60 * 1000) {
      this.greskaZakazivanje = "Cas mora biti zakazan bar sat vremena pre njegovog pocetka.";
    }
    else {
      this.greskaZakazivanje = "";
    }

    if (this.greskaIzabranPredmet != "" || this.greskaZakazivanje != ""){
      return;
    }

    let cas = new Cas();
    cas.opis = this.opis;
    cas.nastavnik = this.nastavnik;
    cas.ucenik = this.ulogovan;
    cas.predmet = this.izabranPredmet;
    cas.status = "cekanje";
    cas.start = new Date(this.strDatumPocetak);
    cas.end = new Date(this.strDatumKraj);

    this.casServis.dodavanjeCas(cas).subscribe(
      (data) => {
        if (data.message != "ok"){
          this.greskaZakazivanje = data.message;
          return;
        }
        this.otvorenModal?.close();
        this.ngOnInit();
      }
    )
  }

  prosli() {
    if (this.view == CalendarView.Week) {
      this.showingDate = subWeeks(this.showingDate, 1);
    }
    else {
      this.showingDate = subDays(this.showingDate, 1);
    }
    this.osveziNedostupnosti(this.showingDate);
  }

  danas() {
    this.showingDate = new Date(this.viewDate);
    this.osveziNedostupnosti(this.showingDate);
  }

  sledeci() {
    if (this.view == CalendarView.Week) {
      this.showingDate = addWeeks(this.showingDate, 1);
    }
    else {
      this.showingDate = addDays(this.showingDate, 1);
    }
    this.osveziNedostupnosti(this.showingDate);
  }

  osveziNedostupnosti(datum: Date) {
    let tmp = this.events.filter(elem => elem.title != "NEDOSTUPAN");
    this.events = tmp;
    this.casServis.dohvatiRadnaVremena(this.nastavnik.korime, datum).subscribe(
      (data1) => {
        let i = 0;
        let days = 0;
        data1.forEach(d => {
          d.start = new Date(d.start);
          d.end = new Date(d.end);
        });
        let currDate = startOfWeek(datum, { weekStartsOn: DAYS_OF_WEEK.MONDAY });
        while (days < 7 && i <= data1.length) {
          if (i == data1.length || new Date(data1[i].start).getDate() != currDate.getDate()) {

            if (currDate.getDay() == DAYS_OF_WEEK.SATURDAY || currDate.getDay() == DAYS_OF_WEEK.SUNDAY) {
              // ako je vikend
              let event = new Cas();
              event.start = new Date(currDate);
              event.start.setHours(0);
              event.start.setMinutes(1);
              event.end = new Date(currDate);
              event.end.setHours(23);
              event.end.setMinutes(59);
              event.color = colors['red'];
              event.title = "NEDOSTUPAN";
              this.events.push(event)
            }
            else {
              // dodaje se default radno vreme
              let event = new Cas();
              event.start = new Date(currDate);
              event.start.setHours(0);
              event.start.setMinutes(1);
              event.end = new Date(currDate);
              event.end.setHours(9);
              event.end.setMinutes(59);
              event.color = colors['red'];
              event.title = "NEDOSTUPAN";
              this.events.push(event)
              event = new Cas();
              event.start = new Date(currDate);
              event.start.setHours(18);
              event.start.setMinutes(1);
              event.end = new Date(currDate);
              event.end.setHours(23);
              event.end.setMinutes(59);
              event.color = colors['red'];
              event.title = "NEDOSTUPAN";
              this.events.push(event);
            }
            days++;
          }
          else {
            let event = new Cas();
            event.start = new Date(currDate);
            event.start.setHours(0);
            event.start.setMinutes(1);
            event.end = new Date(data1[i].start);
            event.color = colors['red'];
            event.title = "NEDOSTUPAN";
            this.events.push(event);
            event = new Cas();
            event.start = new Date(data1[i].end);
            event.end = new Date(currDate);
            event.end.setHours(23);
            event.end.setMinutes(59);
            event.color = colors['red'];
            event.title = "NEDOSTUPAN";
            this.events.push(event);
            i++;
          }
          currDate = addDays(currDate, 1);
        }

        this.refresh.next();
      }
    )
  }

  setView(view: CalendarView) {
    this.view = view;
  }

}
