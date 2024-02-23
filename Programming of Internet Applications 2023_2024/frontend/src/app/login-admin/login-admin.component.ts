import { Component, OnInit } from '@angular/core';
import { KorisnikService } from '../services/korisnik.service';
import { Router } from '@angular/router';
import { Korisnik } from '../models/korisnik';

@Component({
  selector: 'app-login-admin',
  templateUrl: './login-admin.component.html',
  styleUrls: ['./login-admin.component.css']
})
export class LoginAdminComponent implements OnInit {

  korime: string = "";
  lozinka: string = "";
  greskaLogin: string = "";

  constructor(private router: Router,
    private korisnikServis: KorisnikService) { }

  ngOnInit(): void {
    let x = localStorage.getItem("ulogovan");
    if (x != null) {
      let ulogovan: Korisnik = JSON.parse(x);
      if (ulogovan.tip == "nastavnik"){
        this.router.navigate(['nastavnikProfil']);
      }
      else if (ulogovan.tip == 'ucenik'){
        this.router.navigate(['ucenikProfil']);
      }
      else if (ulogovan.tip == "admin"){
        this.router.navigate(['adminPocetna']);
      }
    }
  }

  login() {
    if (this.korime == "" && this.lozinka == "") {
      this.greskaLogin = "Korisnicko ime i lozinka su obavezna polja.";
      return;
    }
    else if (this.korime == "") {
      this.greskaLogin = "Korisnicko ime je obavezno polje.";
      return;
    }
    else if (this.lozinka == "") {
      this.greskaLogin = "Lozinka je obavezno polje.";
      return;
    }

    this.korisnikServis.loginAdmin(this.korime, this.lozinka).subscribe(
      (data) => {
        if (data == null) {
          this.greskaLogin = "Korisnik sa tim kredencijalima ne postoji.";
          return;
        }
        localStorage.setItem("ulogovan", JSON.stringify(data));
        this.router.navigate(['adminPocetna']);
      }
    )
  }
}
