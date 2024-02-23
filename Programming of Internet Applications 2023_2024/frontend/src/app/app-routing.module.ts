import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { RegistracijaComponent } from './registracija/registracija.component';
import { UcenikProfilComponent } from './ucenik-profil/ucenik-profil.component';
import { NastavnikProfilComponent } from './nastavnik-profil/nastavnik-profil.component';
import { LoginAdminComponent } from './login-admin/login-admin.component';
import { AdminPocetnaComponent } from './admin-pocetna/admin-pocetna.component';
import { ZaboravljenaLozinkaComponent } from './zaboravljena-lozinka/zaboravljena-lozinka.component';
import { PromenaLozinkeComponent } from './promena-lozinke/promena-lozinke.component';
import { NastavniciComponent } from './nastavnici/nastavnici.component';
import { NastavnikDetaljiComponent } from './nastavnik-detalji/nastavnik-detalji.component';
import { UcenikCasoviComponent } from './ucenik-casovi/ucenik-casovi.component';
import { UcenikObavestenjaComponent } from './ucenik-obavestenja/ucenik-obavestenja.component';
import { NastavnikCasoviComponent } from './nastavnik-casovi/nastavnik-casovi.component';
import { NastavnikMojiUceniciComponent } from './nastavnik-moji-ucenici/nastavnik-moji-ucenici.component';
import { AdminStatistikaComponent } from './admin-statistika/admin-statistika.component';

const routes: Routes = [
  {path: "", component: LoginComponent},
  {path: "registracija", component: RegistracijaComponent},
  {path: "ucenikProfil", component: UcenikProfilComponent},
  {path: "nastavnikProfil", component: NastavnikProfilComponent},
  {path: "loginAdmin", component: LoginAdminComponent},
  {path: "adminPocetna", component: AdminPocetnaComponent},
  {path: "zaboravljenaLozinka", component: ZaboravljenaLozinkaComponent},
  {path: "promenaLozinke", component: PromenaLozinkeComponent},
  {path: "nastavnici", component: NastavniciComponent},
  {path: "nastavnikDetalji", component: NastavnikDetaljiComponent},
  {path: "ucenikCasovi", component: UcenikCasoviComponent},
  {path: "ucenikObavestenja", component: UcenikObavestenjaComponent},
  {path: "nastavnikCasovi", component: NastavnikCasoviComponent},
  {path: "nastavnikMojiUcenici", component: NastavnikMojiUceniciComponent},
  {path: "adminStatistika", component: AdminStatistikaComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
