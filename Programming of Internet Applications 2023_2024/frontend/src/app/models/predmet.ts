import { Korisnik } from "./korisnik";

export class Predmet{
  naziv: string = "";
  nastavnici: Korisnik[] = [];
  odobren: string = "";

  selektovan: boolean = false;
}
