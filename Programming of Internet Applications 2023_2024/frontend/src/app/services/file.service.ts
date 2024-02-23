import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class FileService {

  url: string = "http://localhost:4000/fajlovi";

  constructor(private http: HttpClient) { }

  dohvatiUrlSlika(filename: string){
    return this.http.get<{imageUrl: string}>(this.url + `/dohvatiSliku/${filename}`);
  }

  dohvatiUrlCV(filename: string){
    return this.http.get<{cvUrl: string}>(this.url + `/dohvatiCV/${filename}`);
  }
}
