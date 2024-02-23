import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Pitanje } from '../models/pitanje';

@Injectable({
  providedIn: 'root'
})
export class PitanjeService {

  url: string = "http://localhost:4000/pitanja"

  constructor(private http:HttpClient) { }

  dohvatiSvaPitanja(){
    return this.http.get<Pitanje[]>(this.url + "/dohvatiSvaPitanja");
  }

}
