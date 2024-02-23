import express from 'express';
import fs from 'fs';
import path from 'path';
import KorisnikModel from '../models/korisnik'
import sizeOf from 'image-size';
import PredmetModel from '../models/predmet'
import CasModel from '../models/cas'
import bcrypt from 'bcrypt';

const saltRounds = 9;
const uporediLozinke = async (lozinka: string | Buffer, hashedLozinka: string) => {
    try {
        const match = await bcrypt.compare(lozinka, hashedLozinka);
        return match;
    } catch (error) {
        throw error;
    }
};
const hashLozinka = async (password: string | Buffer) => {
    try {
        const hashedLozinka = await bcrypt.hash(password, saltRounds);
        return hashedLozinka;
    } catch (error) {
        throw error;
    }
};

export class KorisnikController {
    login = (req: express.Request, res: express.Response) => {
        let korime = req.body.korime;
        let lozinka = req.body.lozinka;

        KorisnikModel.findOne({ korime: korime, tip: { $ne: "admin" } }).then(
            async (data) => {
                if (data != null) {
                    if (data.lozinka === undefined || data.lozinka == null) return;
                    if (await uporediLozinke(lozinka, data.lozinka)) {
                        data.lozinka = '';
                    }
                    else {
                        res.json(null);
                        return;
                    }
                }
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json(null);
            }
        )
    }

    loginAdmin = (req: express.Request, res: express.Response) => {
        let korime = req.body.korime;
        let lozinka = req.body.lozinka;

        KorisnikModel.findOne({ korime: korime, tip: 'admin' }).then(
            async (data) => {
                if (data != null) {
                    if (data.lozinka === undefined || data.lozinka == null) return;
                    if (await uporediLozinke(lozinka, data.lozinka)) {
                        data.lozinka = '';
                    }
                    else {
                        res.json(null);
                        return;
                    }
                }
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json(null);
            }
        )
    }

    prebaciFajlUProfilne = (file: Express.Multer.File, korime: string) => {
        const sourceFilePath = file.path;
        const destinationFolderPath = "public/profilneSlike";
        const newFileName = korime + "_" + file.originalname;
        if (!fs.existsSync(destinationFolderPath)) {
            fs.mkdirSync(destinationFolderPath);
        }
        const sourceFileContent = fs.readFileSync(sourceFilePath);
        const desnationFilePath = path.join(destinationFolderPath, newFileName);
        fs.writeFileSync(desnationFilePath, sourceFileContent);

        fs.unlinkSync(sourceFilePath);
    }

    prebaciFajlUCV = (file: Express.Multer.File, korime: string) => {
        const sourceFilePath = file.path;
        const destinationFolderPath = "public/cv";
        const newFileName = korime + "_" + file.originalname;
        if (!fs.existsSync(destinationFolderPath)) {
            fs.mkdirSync(destinationFolderPath);
        }
        const sourceFileContent = fs.readFileSync(sourceFilePath);
        const desnationFilePath = path.join(destinationFolderPath, newFileName);
        fs.writeFileSync(desnationFilePath, sourceFileContent);

        fs.unlinkSync(sourceFilePath);
    }

    registracijaUcenika = (req: express.Request, res: express.Response) => {
        const slika = req.file;
        const ucenik = JSON.parse(req.body.korisnik);

        if (!slika) {
            res.json({ message: "Nije postavljena slika" });
            return;
        }

        const dozvoljeniTipovi = ['image/jpeg', 'image/png', 'application/octet-stream'];
        if (!dozvoljeniTipovi.includes(slika.mimetype)) {
            fs.unlinkSync(slika.path);
            res.json({ message: "Slika mora biti .png ili .jpg" });
            return;
        }
        const dimensions = sizeOf(slika.path);
        const width = dimensions.width;
        const height = dimensions.height;
        if (width === undefined || height === undefined) {
            fs.unlinkSync(slika.path);
            res.json({ message: "Postoji neki problem sa slikom pa nije moguce odrediti dimenzije." });
            return;
        }

        if (width < 100 || height < 100 || width > 300 || height > 300) {
            fs.unlinkSync(slika.path);
            res.json({ message: "Slika mora biti minimalnih dimenzija 100x100px, a maksimalnih dimenzija 300x300px" });
            return;
        }


        KorisnikModel.findOne({ $or: [{ korime: ucenik.korime }, { email: ucenik.email }] }).then(
            async (data) => {
                if (data) {
                    res.json({ message: "Vec postoji korisnik sa unetim korisnickim imenom ili emailom!" });
                }
                else {
                    this.prebaciFajlUProfilne(slika, ucenik.korime);
                    ucenik.profilna = ucenik.korime + "_" + slika.originalname;
                    ucenik.lozinka = await hashLozinka(ucenik.lozinka);

                    new KorisnikModel(ucenik).save().then(
                        (ok) => {
                            res.json({ message: "ok" });
                        }
                    )
                }
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json({ message: "not ok" });
            }
        )


    }

    dohvatiKorisnika = (req: express.Request, res: express.Response) => {
        KorisnikModel.findOne({ korime: req.params.korime }).then(
            (data) => {
                if (data != null) {
                    data.lozinka = '';
                }
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
            }
        )
    }

    registracijaNastavnika = (req: express.Request, res: express.Response) => {
        const cv = ((req.files as any)['cv'] as Express.Multer.File[])[0];
        const slika = ((req.files as any)['slika'] as Express.Multer.File[])[0];
        const nastavnik = JSON.parse(req.body.korisnik);

        if (!slika) {
            res.json({ message: "Nije postavljena slika" });
            return;
        }
        if (!cv) {
            res.json({ message: "Nije postavljena slika" });
            return;
        }

        const dozvoljeniTipovi = ['image/jpeg', 'image/png', 'application/octet-stream'];
        if (!dozvoljeniTipovi.includes(slika.mimetype)) {
            fs.unlinkSync(slika.path);
            fs.unlinkSync(cv.path);
            res.json({ message: "Slika mora biti .png ili .jpg" });
            return;
        }
        const dimensions = sizeOf(slika.path);
        const width = dimensions.width;
        const height = dimensions.height;
        if (width === undefined || height === undefined) {
            fs.unlinkSync(slika.path);
            fs.unlinkSync(cv.path);
            res.json({ message: "Postoji neki problem sa slikom pa nije moguce odrediti dimenzije." });
            return;
        }

        if (width < 100 || height < 100 || width > 300 || height > 300) {
            fs.unlinkSync(slika.path);
            fs.unlinkSync(cv.path);
            res.json({ message: "Slika mora biti minimalnih dimenzija 100x100px, a maksimalnih dimenzija 300x300px" });
            return;
        }


        if (!cv.mimetype.startsWith('application/pdf')) {
            fs.unlinkSync(slika.path);
            fs.unlinkSync(cv.path);
            res.json({ message: "CV mora biti PDF dokument." });
            return;
        }
        if (cv.size > 3 * 1024 * 1024) {
            fs.unlinkSync(slika.path);
            fs.unlinkSync(cv.path);
            res.json({ message: "Maksimalna velicina za CV je 3MB." });
            return;
        }

        KorisnikModel.findOne({ $or: [{ korime: nastavnik.korime }, { email: nastavnik.email }] }).then(
            async (data) => {
                if (data) {
                    res.json({ message: "Vec postoji korisnik sa unetim korisnickim imenom ili emailom!" });
                }
                else {
                    this.prebaciFajlUProfilne(slika, nastavnik.korime);
                    nastavnik.profilna = nastavnik.korime + "_" + slika.originalname;

                    this.prebaciFajlUCV(cv, nastavnik.korime);
                    nastavnik.cv = nastavnik.korime + "_" + cv.originalname;
                    nastavnik.lozinka = await hashLozinka(nastavnik.lozinka);

                    new KorisnikModel(nastavnik).save().then(
                        async (ok) => {
                            for (let i = 0; i < nastavnik.predajePredmete.length; i++) {
                                let nazivPredmeta = nastavnik.predajePredmete[i];
                                await PredmetModel.updateOne({ naziv: nazivPredmeta }, { $push: { nastavnici: nastavnik } });
                            }
                            if (req.body.imeCustomPredmet != '') {
                                let predmet = await PredmetModel.findOne({ naziv: req.body.imeCustomPredmet });
                                if (predmet == null) {
                                    let novPredmet = {
                                        naziv: req.body.imeCustomPredmet,
                                        odobren: "ne",
                                        nastavnici: []
                                    }
                                    await new PredmetModel(novPredmet).save();
                                }
                                await PredmetModel.updateOne({ naziv: req.body.imeCustomPredmet }, { $push: { nastavnici: nastavnik } });
                            }
                            res.json({ message: "ok" });
                        }
                    )
                }
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json({ message: "not ok" });
            }
        )
    }

    promenaLozinke = async (req: express.Request, res: express.Response) => {
        let lozinka = await hashLozinka(req.body.lozinka);
        KorisnikModel.updateOne({ korime: req.body.korime }, { lozinka: lozinka }).then(
            (data) => {
                res.json({ message: 'ok' });
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json({ message: "not ok" });
            }
        )
    }

    proveraLozinke = (req: express.Request, res: express.Response) => {
        let korime = req.body.korime;
        let lozinka = req.body.lozinka;

        KorisnikModel.findOne({ korime: korime }).then(
            async (data) => {
                if (data != null) {
                    if (data.lozinka === undefined || data.lozinka == null) return;
                    if (await uporediLozinke(lozinka, data.lozinka)) {
                        res.json({ message: "ok" });
                    }
                    else {
                        res.json({ message: "Stara lozinka nije ispravna." })
                        return;
                    }
                }
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json({ message: "not ok" });
            }
        )
    }

    azuriranjeUcenika = (req: express.Request, res: express.Response) => {
        KorisnikModel.findOne({ email: req.body.email }).then(
            (data) => {
                if (data != null) {
                    res.json({ message: "Korisnik sa tim emailom vec postoji." });
                    return;
                }
                KorisnikModel.updateOne({ korime: req.body.korime }, {
                    ime: req.body.ime, prezime: req.body.prezime,
                    adresa: req.body.adresa, email: req.body.email, telefon: req.body.telefon, tipSkole: req.body.tipSkole,
                    razred: req.body.razred
                }).then(
                    (ok) => {
                        res.json({ message: 'ok' });
                    }
                )
            }
        ).catch(
            (err) => {
                res.json({ message: 'not ok' });
                console.log(err);
            }
        )

    }

    azuriranjeNastavnika = (req: express.Request, res: express.Response) => {
        KorisnikModel.updateOne({ korime: req.body.korime }, {
            ime: req.body.ime, prezime: req.body.prezime,
            adresa: req.body.adresa, email: req.body.email, telefon: req.body.telefon, predajePredmete: req.body.predajePredmete,
            predajeUzrastu: req.body.predajeUzrastu
        }).then(
            async (ok) => {
                await PredmetModel.updateMany({}, { $pull: { nastavnici: { korime: req.body.korime } } });
                for (let i = 0; i < req.body.predajePredmete.length; i++) {
                    let nazivPredmeta = req.body.predajePredmete[i];
                    await PredmetModel.updateOne({ naziv: nazivPredmeta }, { $push: { nastavnici: req.body } });
                }
                res.json({ message: 'ok' });
            }
        ).catch(
            (err) => {
                res.json({ message: 'not ok' });
                console.log(err);
            }
        )
    }

    azuriranjeProfilne = (req: express.Request, res: express.Response) => {
        const slika = req.file;
        const korisnik = JSON.parse(req.body.korisnik);

        if (!slika) {
            korisnik.message = "Nije postavljena slika";
            res.json(korisnik);
            return;
        }

        const dozvoljeniTipovi = ['image/jpeg', 'image/png', 'application/octet-stream'];
        if (!dozvoljeniTipovi.includes(slika.mimetype)) {
            fs.unlinkSync(slika.path);
            korisnik.message = "Slika mora biti .png ili .jpg. Ostatak profila je uspesno izmenjen.";
            res.json(korisnik);
            return;
        }
        const dimensions = sizeOf(slika.path);
        const width = dimensions.width;
        const height = dimensions.height;
        if (width === undefined || height === undefined) {
            fs.unlinkSync(slika.path);
            korisnik.message = "Postoji neki problem sa slikom pa nije moguce odrediti dimenzije. Ostatak profila je uspesno izmenjen.";
            res.json(korisnik);
            return;
        }

        if (width < 100 || height < 100 || width > 300 || height > 300) {
            fs.unlinkSync(slika.path);
            korisnik.message = "Slika mora biti minimalnih dimenzija 100x100px, a maksimalnih dimenzija 300x300px. Ostatak profila je uspesno izmenjen.";
            res.json(korisnik);
            return;
        }

        const destinationFolderPath = "public/profilneSlike";
        const oldPhotoPath = path.join(destinationFolderPath, korisnik.profilna);
        if (fs.existsSync(oldPhotoPath)) {
            fs.unlinkSync(oldPhotoPath);
        }

        this.prebaciFajlUProfilne(slika, korisnik.korime);
        korisnik.profilna = korisnik.korime + "_" + slika.originalname;

        KorisnikModel.updateOne({ korime: korisnik.korime }, { profilna: korisnik.profilna }).then(
            (ok) => {
                korisnik.message = 'ok';
                res.json(korisnik);
            }
        ).catch(
            (err) => {
                console.log(err);
                korisnik.message = 'not ok';
                res.json(korisnik);
            }
        )
    }

    dohvatiSveUcenike = (req: express.Request, res: express.Response) => {
        KorisnikModel.find({ tip: 'ucenik' }).then(
            (data) => {
                if (data != null) {
                    for (let i = 0; i < data.length; i++) {
                        data[i].lozinka = "";
                    }
                }
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json(err);
            }
        )
    }

    dohvatiSveOdobreneNastavnike = (req: express.Request, res: express.Response) => {
        KorisnikModel.find({ tip: 'nastavnik', odobrenaRegistracija: "da" }).then(
            (data) => {
                if (data != null) {
                    for (let i = 0; i < data.length; i++) {
                        data[i].lozinka = "";
                    }
                }
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json(err);
            }
        )
    }

    dohvatiSveZahteveNastavnike = (req: express.Request, res: express.Response) => {
        KorisnikModel.find({ tip: 'nastavnik', odobrenaRegistracija: "ne" }).then(
            (data) => {
                if (data != null) {
                    for (let i = 0; i < data.length; i++) {
                        data[i].lozinka = "";
                    }
                }
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json(err);
            }
        )
    }

    azuriranjeRegistracijeNastavnika = (req: express.Request, res: express.Response) => {
        KorisnikModel.updateOne({ korime: req.body.korime }, { odobrenaRegistracija: req.body.odobrenaRegistracija }).then(
            async (ok) => {
                let data1 = await PredmetModel.updateMany({}, { $set: { 'nastavnici.$[n].odobrenaRegistracija': req.body.odobrenaRegistracija } },
                    { arrayFilters: [{ "n.korime": req.body.korime }] });

                if (req.body.odobrenaRegistracija == "deaktiviran") {
                    let data2 = await CasModel.updateMany({
                        "nastavnik.korime": req.body.korime,
                        start: { $gte: new Date() },
                        status: "potvrdjen"
                    },
                        {
                            status: "otkazan",
                            procitan: false,
                            datumFormiranja: new Date(),
                            obrazlozenje: "Administrator je deaktivirao ovog nastavnika, pa su mu svi casovi otkazani."
                        })
                }
                res.json({ message: 'ok' });
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json({ message: 'not ok' });
            }
        )
    }

    izracunajProsecneOceneNastavnika = (req: express.Request, res: express.Response) => {
        CasModel.aggregate([
            {
                $match: {
                    ocenaZaNastavnika: { $ne: 0 },
                    status: "potvrdjen"
                }
            },
            {
                $group:
                {
                    _id: "$nastavnik.korime",
                    prosecnaOcena: { $avg: "$ocenaZaNastavnika" }
                }
            },
            {
                $project:
                {
                    _id: 0,
                    korime: "$_id",
                    prosecnaOcena: "$prosecnaOcena"
                }
            }
        ]).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json([]);
            }
        )
    }

    izracunajProsecneOceneUcenika = (req: express.Request, res: express.Response) => {
        CasModel.aggregate([
            {
                $match: {
                    ocenaZaUcenika: { $ne: 0 },
                    status: "potvrdjen"
                }
            },
            {
                $group:
                {
                    _id: "$ucenik.korime",
                    count: { $sum: 1 },
                    total: { $sum: "$ocenaZaUcenika" }
                }
            },
            {
                $match: { count: { $gte: 3 } }
            },
            {
                $project:
                {
                    _id: 0,
                    korime: "$_id",
                    prosecnaOcena: { $divide: ["$total", "$count"] }
                }
            }
        ]).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json([]);
            }
        )
    }

    procenatPolovaNastavnika = (req: express.Request, res: express.Response) => {
        KorisnikModel.aggregate([
            {
                $match: {
                    odobrenaRegistracija: "da",
                    tip: "nastavnik"
                }
            },
            {
                $group:
                {
                    _id: "$pol",
                    count: { $sum: 1 }
                }
            },
            {
                $project:
                {
                    _id: 0,
                    pol: "$_id",
                    prosecnaOcena: "$count"
                }
            }
        ]).sort({ pol: 1 }).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json([]);
            }
        )
    }

    procenatPolovaUcenika = (req: express.Request, res: express.Response) => {
        KorisnikModel.aggregate([
            {
                $match: {
                    tip: "ucenik"
                }
            },
            {
                $group:
                {
                    _id: "$pol",
                    count: { $sum: 1 }
                }
            },
            {
                $project:
                {
                    _id: 0,
                    pol: "$_id",
                    prosecnaOcena: "$count"
                }
            }
        ]).sort({ pol: 1 }).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json([]);
            }
        )
    }

    procenatUzrastaUcenika = (req: express.Request, res: express.Response) => {
        KorisnikModel.find({ tip: "ucenik", tipSkole: "osn", razred: { $gte: 1, $lte: 4 } }).then(
            async (data) => {
                let result: {uzrast: string, broj: number}[] = [];
                result[0] = {
                    uzrast: "Osnovna skola(1-4. razred)",
                    broj: data.length
                }
                let data1 = await KorisnikModel.find({ tip: "ucenik", tipSkole: "osn", razred: { $gte: 5, $lte: 8 } })
                result[1] = {
                    uzrast: "Osnovna skola(5-8. razred)",
                    broj: data1.length
                }
                data1 = await KorisnikModel.find({ tip: "ucenik", tipSkole: { $in: ["srGimn", "srUmet", "srStruc"] } });
                result[2] = {
                    uzrast: "Srednja skola",
                    broj: data1.length
                }
                res.json(result);
            }
        ).catch(
            (err)=>{
                console.log(err);
            }
        )
    }
}