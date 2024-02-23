import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { RegistracijaComponent } from './registracija/registracija.component';
import { UcenikProfilComponent } from './ucenik-profil/ucenik-profil.component';
import { NastavnikProfilComponent } from './nastavnik-profil/nastavnik-profil.component';
import { LoginAdminComponent } from './login-admin/login-admin.component';
import { HeaderComponent } from './header/header.component';
import { AdminPocetnaComponent } from './admin-pocetna/admin-pocetna.component';
import { ZaboravljenaLozinkaComponent } from './zaboravljena-lozinka/zaboravljena-lozinka.component';
import { PromenaLozinkeComponent } from './promena-lozinke/promena-lozinke.component';
import { UzrastPipe } from './pipes/uzrast.pipe';
import { TipSkolePipe } from './pipes/tip-skole.pipe';
import { NastavniciComponent } from './nastavnici/nastavnici.component';
import { NastavnikDetaljiComponent } from './nastavnik-detalji/nastavnik-detalji.component';
import { CalendarModule, DateAdapter } from 'angular-calendar';
import { adapterFactory } from 'angular-calendar/date-adapters/date-fns';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { UcenikKalendarComponent } from './ucenik-kalendar/ucenik-kalendar.component';
import { UcenikCasoviComponent } from './ucenik-casovi/ucenik-casovi.component';
import { UcenikObavestenjaComponent } from './ucenik-obavestenja/ucenik-obavestenja.component';
import { NastavnikCasoviComponent } from './nastavnik-casovi/nastavnik-casovi.component';
import { NastavnikKalendarComponent } from './nastavnik-kalendar/nastavnik-kalendar.component';
import { NastavnikMojiUceniciComponent } from './nastavnik-moji-ucenici/nastavnik-moji-ucenici.component';
import { AdminStatistikaComponent } from './admin-statistika/admin-statistika.component';
import { NgApexchartsModule } from 'ng-apexcharts';
import { FooterComponent } from './footer/footer.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    RegistracijaComponent,
    UcenikProfilComponent,
    NastavnikProfilComponent,
    LoginAdminComponent,
    HeaderComponent,
    AdminPocetnaComponent,
    ZaboravljenaLozinkaComponent,
    PromenaLozinkeComponent,
    UzrastPipe,
    TipSkolePipe,
    NastavniciComponent,
    NastavnikDetaljiComponent,
    UcenikKalendarComponent,
    UcenikCasoviComponent,
    UcenikObavestenjaComponent,
    NastavnikCasoviComponent,
    NastavnikKalendarComponent,
    NastavnikMojiUceniciComponent,
    AdminStatistikaComponent,
    FooterComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    HttpClientModule,
    CalendarModule.forRoot({ provide: DateAdapter, useFactory: adapterFactory }),
    NgbModule,
    NgApexchartsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
