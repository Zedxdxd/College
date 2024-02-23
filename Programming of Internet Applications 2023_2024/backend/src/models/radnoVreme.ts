import mongoose from "mongoose";

const RadnoVremeSchema = new mongoose.Schema(
    {
        start: Date,
        end: Date,
        nastavnik: Object
    }
);

export default mongoose.model("RadnoVremeModel", RadnoVremeSchema, "radnoVreme");