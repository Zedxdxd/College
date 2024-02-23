import { Component, OnInit } from '@angular/core';
import { Korisnik } from '../models/korisnik';
import { Router } from '@angular/router';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit{

  ulogovan: Korisnik = new Korisnik();
  currentUrl: string = "";

  constructor(private router: Router) {}

  ngOnInit(): void {
    let x = localStorage.getItem("ulogovan");
    if (x != null){
      this.ulogovan = JSON.parse(x);
    }
    this.currentUrl = this.router.url;
  }

  logout(){
    localStorage.removeItem("ulogovan");
    this.router.navigate(['']);
  }
}
