import mongoose from "mongoose";

let PitanjeSchema = new mongoose.Schema(
    {
        tekst: String
    }
);

export default mongoose.model("PitanjeModel", PitanjeSchema, "pitanja");