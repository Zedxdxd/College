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
  selector: 'app-nastavnik-kalendar',
  changeDetection: ChangeDetectionStrategy.OnPush,
  templateUrl: './nastavnik-kalendar.component.html',
  styleUrls: ['./nastavnik-kalendar.component.css'],
  providers: [
    {
      provide: CalendarDateFormatter,
      useClass: MilitaryDateFormatter
    }
  ]
})
export class NastavnikKalendarComponent implements OnInit {

  // MOJ KOD START
  locale: string = 'gb';
  weekStartsOn: number = DAYS_OF_WEEK.MONDAY;
  view: CalendarView = CalendarView.Week;
  CalendarView = CalendarView;
  viewDate: Date = new Date();
  refresh = new Subject<void>();
  events: Cas[] = [];
  showingDate: Date = new Date(); // ovo je za previous i next da se dohvataju nedostupnosti

  ulogovan: Korisnik = new Korisnik();

  formatDate(date: Date): string {
    // Format date as 'yyyy-MM-ddThh:mm'
    return date.toISOString().slice(0, 16);
  }

  constructor(private casServis: CasService) { }

  ngOnInit() {
    let x = localStorage.getItem("ulogovan");
    if (x != null) {
      this.ulogovan = JSON.parse(x);
    }

    this.casServis.dohvatiCasoveNastavnika(this.ulogovan.korime).subscribe(
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
    this.casServis.dohvatiRadnaVremena(this.ulogovan.korime, datum).subscribe(
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
