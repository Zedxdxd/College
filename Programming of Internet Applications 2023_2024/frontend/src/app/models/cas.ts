import { CalendarEvent } from "angular-calendar";
import { EventColor, EventAction } from "calendar-utils";
import { Korisnik } from "./korisnik";

export class Cas implements CalendarEvent{
  id?: string | number | undefined;
  start: Date = new Date();
  end?: Date | undefined;
  title: string = "";
  color?: EventColor | undefined;
  actions?: EventAction[] | undefined;
  allDay?: boolean | undefined;
  cssClass?: string | undefined;
  resizable?: { beforeStart?: boolean | undefined; afterEnd?: boolean | undefined; } | undefined;
  draggable?: boolean | undefined;
  meta?: any;

  opis: string = "";
  ucenik: Korisnik = new Korisnik();
  nastavnik: Korisnik = new Korisnik();
  predmet: string = "";
  komentarZaNastavnika: string = "";
  ocenaZaNastavnika: number = 0;
  komentarZaUcenika: string = "";
  ocenaZaUcenika: number = 0;
  status: string = "";
  procitan: boolean = false;
  datumFormiranja: Date = new Date();
  obrazlozenje: string = "";
}
