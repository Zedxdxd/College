import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Predmet } from '../models/predmet';
import { Message } from '../models/message';

@Injectable({
  providedIn: 'root'
})
export class PredmetService {

  url: string = "http://localhost:4000/predmeti"

  constructor(private http: HttpClient) { }

  dohvatiSvePredmete(){
    return this.http.get<Predmet[]>(this.url + "/dohvatiSvePredmete");
  }

  dohvatiZahtevanePredmete(){
    return this.http.get<Predmet[]>(this.url + "/dohvatiZahtevanePredmete");
  }

  odobriPredmet(naziv: string){
    return this.http.post<Message>(this.url + '/odobriPredmet', {naziv: naziv});
  }

  dodavanjeNovPredmet(naziv: string){
    return this.http.post<Message>(this.url + '/dodavanjeNovPredmet', {naziv: naziv});
  }
}
