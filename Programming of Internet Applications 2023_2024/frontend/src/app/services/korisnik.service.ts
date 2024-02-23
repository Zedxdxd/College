import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Korisnik } from '../models/korisnik';
import { Message } from '../models/message';

@Injectable({
  providedIn: 'root'
})
export class KorisnikService {

  url: string = "http://localhost:4000/korisnici";

  constructor(private http: HttpClient) { }

  registracijaUcenika(korisnik: Korisnik, slika: File){
    let formData = new FormData();
    formData.append("slika", slika, slika.name);
    formData.append("korisnik", JSON.stringify(korisnik));
    return this.http.post<Message>(this.url + "/registracijaUcenika", formData);
  }

  registracijaNastavnika(korisnik: Korisnik, slika: File, cv: File, imeCustomPredmet: string){
    let formData = new FormData();
    formData.append("slika", slika, slika.name);
    formData.append("cv", cv, cv.name);
    formData.append("korisnik", JSON.stringify(korisnik));
    formData.append("imeCustomPredmet", imeCustomPredmet);
    return this.http.post<Message>(this.url + "/registracijaNastavnika", formData);
  }

  login(korime: string, lozinka: string){
    const data = {
      korime: korime,
      lozinka: lozinka
    }
    return this.http.post<Korisnik>(this.url + "/login", data);
  }

  loginAdmin(korime: string, lozinka: string) {
    const data = {
      korime: korime,
      lozinka: lozinka
    };
    return this.http.post<Korisnik>(this.url + '/loginAdmin', data);
  }

  dohvatiKorisnika(korime: string){
    return this.http.get<Korisnik>(this.url + `/dohvatiKorisnika/${korime}`);
  }

  promenaLozinke(korime: string, lozinka: string){
    const data = {
      korime: korime,
      lozinka: lozinka
    };
    return this.http.post<Message>(this.url + '/promenaLozinke', data);
  }

  proveraLozinke(korime: string, lozinka: string) {
    const data = {
      korime: korime,
      lozinka: lozinka
    };
    return this.http.post<Message>(this.url + '/proveraLozinke', data);
  }

  azuriranjeUcenika(korisnik: Korisnik){
    return this.http.post<Message>(this.url + '/azuriranjeUcenika', korisnik);
  }

  azuriranjeNastavnika(korisnik: Korisnik){
    return this.http.post<Message>(this.url + '/azuriranjeNastavnika', korisnik);
  }

  azuriranjeProfilne(korisnik: Korisnik, slika: File){
    let formData = new FormData();
    formData.append("slika", slika, slika.name);
    formData.append("korisnik", JSON.stringify(korisnik));
    return this.http.post<Korisnik>(this.url + "/azuriranjeProfilne", formData);
  }

  dohvatiSveUcenike(){
    return this.http.get<Korisnik[]>(this.url + '/dohvatiSveUcenike');
  }

  dohvatiSveOdobreneNastavnike(){
    return this.http.get<Korisnik[]>(this.url + '/dohvatiSveOdobreneNastavnike');
  }

  dohvatiSveZahteveNastavnike(){
    return this.http.get<Korisnik[]>(this.url + '/dohvatiSveZahteveNastavnike');
  }

  deaktivacijaNastavnika(nastavnik: Korisnik){
    nastavnik.odobrenaRegistracija = "deaktiviran";
    return this.http.post<Message>(this.url + '/azuriranjeRegistracijeNastavnika', nastavnik);
  }

  odobravanjeRegistracije(nastavnik: Korisnik) {
    nastavnik.odobrenaRegistracija = "da";
    return this.http.post<Message>(this.url + '/azuriranjeRegistracijeNastavnika', nastavnik);
  }

  odbijanjeRegistracije(nastavnik: Korisnik){
    nastavnik.odobrenaRegistracija = "odbijen";
    return this.http.post<Message>(this.url + '/azuriranjeRegistracijeNastavnika', nastavnik);
  }

  izracunajProsecneOceneNastavnika(){
    return this.http.get<Korisnik[]>(this.url + '/izracunajProsecneOceneNastavnika');
  }

  izracunajProsecneOceneUcenika(){
    return this.http.get<Korisnik[]>(this.url + '/izracunajProsecneOceneUcenika');
  }

  procenatPolovaNastavnika(){
    return this.http.get<Korisnik[]>(this.url + '/procenatPolovaNastavnika')
  }

  procenatPolovaUcenika(){
    return this.http.get<Korisnik[]>(this.url + '/procenatPolovaUcenika')
  }

  procenatUzrastaUcenika(){
    return this.http.get<{uzrast: string, broj: number}[]>(this.url + '/procenatUzrastaUcenika')
  }
}
