import mongoose from "mongoose";

let PredmetSchema = new mongoose.Schema(
    {
        naziv: String,
        nastavnici: Array,
        odobren: String
    }
);

export default mongoose.model("PredmetModel", PredmetSchema, "predmeti");