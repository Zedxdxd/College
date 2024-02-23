import mongoose from "mongoose";

const KorisnikSchema = new mongoose.Schema(
    {
        korime: String,
        lozinka: String,
        pitanje: String,
        odgovor: String, 
        ime: String,
        prezime: String,
        pol: String,
        adresa: String,
        telefon: String,
        email: String,
        profilna: String,
        tip: String,

        tipSkole: String,
        razred: Number,

        cv: String,
        predajePredmete: Array,
        predajeUzrastu: Array,
        gdeSteCuli: String,
        odobrenaRegistracija: String
    }
)

export default mongoose.model("KorisnikModel", KorisnikSchema, "korisnici");