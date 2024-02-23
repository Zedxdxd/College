import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Cas } from '../models/cas';
import { Message } from '../models/message';
import { RadnoVreme } from '../models/radnoVreme';
import { Korisnik } from '../models/korisnik';
import { angazovaniNastavnici } from '../models/angazovaniNastavici';

@Injectable({
  providedIn: 'root'
})
export class CasService {

  url: string = "http://localhost:4000/casovi";

  constructor(private http: HttpClient) { }

  dodavanjeCas(cas: Cas) {
    return this.http.post<Message>(this.url + '/dodavanjeCas', cas);
  }

  dohvatiCasoveNastavnika(korime: string) {
    return this.http.get<Cas[]>(this.url + `/dohvatiCasoveNastavnika/${korime}`);
  }

  dohvatiOdrzaneCasoveNastavnika(korime: string) {
    return this.http.get<Cas[]>(this.url + `/dohvatiOdrzaneCasoveNastavnika/${korime}`);
  }

  dohvatiCasoveUcenika(korime: string) {
    return this.http.get<Cas[]>(this.url + `/dohvatiCasoveUcenika/${korime}`);
  }

  dohvatiRadnaVremena(korime: string, datum: Date) {
    return this.http.get<RadnoVreme[]>(this.url + `/dohvatiRadnaVremena/${korime}/${datum.toISOString()}`);
  }

  dohvatiCasoveNedelja(korime: string, datum: Date) {
    return this.http.get<Cas[]>(this.url + `/dohvatiCasoveNedelja/${korime}/${datum.toISOString()}`);
  }

  oceniNastavnika(cas: Cas){
    return this.http.post<Message>(this.url + "/oceniNastavnika", cas);
  }

  oceniUcenika(cas: Cas){
    return this.http.post<Message>(this.url + "/oceniUcenika", cas);
  }

  dohvatiObavestenjaUcenika(korime: string) {
    return this.http.get<Cas[]>(this.url + `/dohvatiObavestenjaUcenika/${korime}`);
  }

  oznaciProcitano(korime: string) {
    return this.http.post<Message>(this.url + `/oznaciProcitano`, {korime: korime});
  }

  promeniStatusCasa(cas: Cas) {
    return this.http.post<Message>(this.url + '/promeniStatusCasa', cas);
  }

  dodajRadnoVreme(datumOd: Date, datumDo: Date, nastavnik: Korisnik){
    let data = {
      datumOd: datumOd,
      datumDo: datumDo,
      nastavnik: nastavnik
    }
    return this.http.post<Message>(this.url + "/dodajRadnoVreme", data);
  }

  prosecanBrojCasovaPoDanu(){
    return this.http.get<Korisnik[]>(this.url + '/prosecanBrojCasovaPoDanu');
  }

  topDesetAngazovanihNastavnika(){
    return this.http.get<angazovaniNastavnici[]>(this.url + "/topDesetAngazovanihNastavnika");
  }

  odrzaniCasoviSedamDana(){
    return this.http.get<{broj: number}>(this.url + "/odrzaniCasoviSedamDana");
  }

  odrzaniCasoviMesecDana(){
    return this.http.get<{broj: number}>(this.url + "/odrzaniCasoviMesecDana");
  }

  brojOdrzanihCasovaPoPredmetu(){
    return this.http.get<{predmet: string, broj: number}[]>(this.url + "/brojOdrzanihCasovaPoPredmetu");
  }
}
