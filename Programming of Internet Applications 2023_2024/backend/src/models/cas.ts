import mongoose from "mongoose";

const CasSchema = new mongoose.Schema(
    {
        start: Date, 
        end: Date,
        opis: String,
        ucenik: Object,
        nastavnik: Object,
        predmet: String,
        komentarZaNastavnika: String,
        ocenaZaNastavnika: Number,
        komentarZaUcenika: String,
        ocenaZaUcenika: Number,
        status: String,
        procitan: Boolean,
        datumFormiranja: Date,
        obrazlozenje: String
    }
)

export default mongoose.model("CasModel", CasSchema, "casovi");