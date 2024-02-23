export class Korisnik{
  korime: string = "";
  lozinka: string = "";
  pitanje: string = "";
  odgovor: string = "";
  ime: string = "";
  prezime: string = "";
  pol: string = "";
  adresa: string = "";
  telefon: string = "";
  email: string = "";
  profilna: string = "";
  tip: string = "";

  tipSkole: string = "";
  razred: number = 0;
  uzrast: string = ""; // pomagalo za prikaz nastavnika

  cv: string = "";
  predajePredmete: string[] = [];
  predajeUzrastu: string[] = [];
  gdeSteCuli: string = "";
  odobrenaRegistracija: string = "";
  cvurl: string = "";
  prosecnaOcena: number = 0;


  // olaksavajuca stvar kod azuriranja profilne slike.. dont ask pls :)
  // ako bas moras da pitas, to je da bih u isto vreme vratio korisnika sa novom proflinom
  // i poruku ako slucajno slika nije dobrih dimenzija itd, psoto sve to proveravam na back-u
  message: string = "";
}
