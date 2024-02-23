import express from 'express';
import CasModel from '../models/cas';
import RadnoVremeModel from '../models/radnoVreme'

export class CasController {
    dodavanjeCas = (req: express.Request, res: express.Response) => {
        let cas = req.body;
        cas.start = new Date(cas.start);
        cas.end = new Date(cas.end);
        var queryDate = new Date(cas.start); // Date to compare (just day, month, year)

        RadnoVremeModel.findOne({
            "nastavnik.korime": cas.nastavnik.korime,
            $expr: {
                $and: [
                    { $eq: [{ $dayOfMonth: "$start" }, { $dayOfMonth: queryDate }] },
                    { $eq: [{ $month: "$start" }, { $month: queryDate }] },
                    { $eq: [{ $year: "$start" }, { $year: queryDate }] },
                    { $eq: [{ $dayOfMonth: "$end" }, { $dayOfMonth: queryDate }] },
                    { $eq: [{ $month: "$end" }, { $month: queryDate }] },
                    { $eq: [{ $year: "$end" }, { $year: queryDate }] }
                ]
            }
        }).then(
            async (radnoVreme) => {
                if (radnoVreme != null) {
                    if (radnoVreme.start == null || radnoVreme.end == null) {
                        res.json({ message: "not ok" });
                        return;
                    }
                    if (cas.start < radnoVreme.start || cas.end > radnoVreme.end) {
                        res.json({ message: "Nastavnik je nedostupan u tom terminu." });
                        return;
                    }
                }
                else {
                    if (cas.start.getDay() == 0 || cas.start.getDay() == 6) {
                        res.json({ message: "Nastavnik je nedostupan u tom terminu." });
                        return;
                    }
                    let radnoVremePocetak = new Date(cas.start);
                    radnoVremePocetak.setHours(10);
                    radnoVremePocetak.setMinutes(0);
                    radnoVremePocetak.setSeconds(0);
                    radnoVremePocetak.setMilliseconds(0);
                    let radnoVremeKraj = new Date(cas.start);
                    radnoVremeKraj.setHours(18);
                    radnoVremeKraj.setMinutes(0);
                    radnoVremeKraj.setSeconds(0);
                    radnoVremeKraj.setMilliseconds(0);
                    if (cas.start < radnoVremePocetak || cas.end > radnoVremeKraj) {
                        res.json({ message: "Nastavnik je nedostupan u tom terminu." });
                        return;
                    }
                }

                let data = await CasModel.findOne({
                    "nastavnik.korime": cas.nastavnik.korime,
                    $or: [
                        {
                            $and: [
                                { "start": { $lte: cas.start } },
                                { "end": { $gt: cas.start } }
                            ]
                        },
                        {
                            $and: [
                                { "start": { $lt: cas.end } },
                                { "end": { $gte: cas.end } }
                            ]
                        }
                    ]
                });

                if (data != null) {
                    res.json({ message: "Nastavnik ima cas u tom terminu." });
                    return;
                }

                data = await CasModel.findOne({
                    "nastavnik.korime": cas.nastavnik.korime,
                    $and: [
                        { start: { $gte: cas.start } },
                        { end: { $lte: cas.end } }
                    ]
                });

                if (data != null) {
                    res.json({ message: "Nastavnik ima cas u tom terminu." });
                    return;
                }

                new CasModel(cas).save().then(
                    (ok) => {
                        res.json({ message: 'ok' });
                    }
                ).catch(
                    (err) => {
                        console.log(err);
                        res.json(err);
                    }
                )
            }
        ).catch(
            (err) => {
                res.json({ message: "not ok" });
                console.log(err);
            }
        )
    }

    dohvatiCasoveNastavnika = (req: express.Request, res: express.Response) => {
        CasModel.find({
            'nastavnik.korime': req.params.korime, $or: [
                { status: "cekanje" }, { status: "potvrdjen" }
            ]
        }).sort({ start: 1 }).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json(null);
            }
        )
    }


    dohvatiOdrzaneCasoveNastavnika = (req: express.Request, res: express.Response) => {
        CasModel.find({
            'nastavnik.korime': req.params.korime, status: "potvrdjen", start: { $lt: new Date() }
        }).sort({ start: 1 }).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json(null);
            }
        )
    }



    dohvatiCasoveUcenika = (req: express.Request, res: express.Response) => {
        CasModel.find({ 'ucenik.korime': req.params.korime, status: "potvrdjen" }).sort({ start: 1 }).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json(null);
            }
        )
    }

    dohvatiCasoveNedelja = (req: express.Request, res: express.Response) => {
        let datum = new Date(req.params.datum);
        datum.setHours(0);
        datum.setMinutes(0);
        datum.setSeconds(0);
        datum.setMilliseconds(0);
        let datumPocetka = new Date(datum.valueOf() - 24 * 60 * 60 * 1000 * ((datum.getDay() - 1) % 7));
        let datumKraja = new Date(datumPocetka.valueOf() + 6 * 24 * 60 * 60 * 1000);
        datumKraja.setHours(23);
        datumKraja.setMinutes(59);
        datumKraja.setSeconds(59);
        datumKraja.setMilliseconds(999);
        CasModel.find({ 'nastavnik.korime': req.params.korime, start: { $gte: datumPocetka }, end: { $lte: datumKraja } }).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                res.json([]);
                console.log(err);
            }
        )
    }

    dohvatiRadnaVremena = (req: express.Request, res: express.Response) => {
        let datum = new Date(req.params.datum);
        datum.setHours(0);
        datum.setMinutes(0);
        datum.setSeconds(0);
        datum.setMilliseconds(0);
        let datumPocetka = new Date(datum.valueOf() - 24 * 60 * 60 * 1000 * ((datum.getDay() - 1) % 7));
        let datumKraja = new Date(datumPocetka.valueOf() + 6 * 24 * 60 * 60 * 1000);
        datumKraja.setHours(23);
        datumKraja.setMinutes(59);
        datumKraja.setSeconds(59);
        datumKraja.setMilliseconds(999);
        RadnoVremeModel.find({ 'nastavnik.korime': req.params.korime, start: { $gte: datumPocetka }, end: { $lte: datumKraja } }).sort({ start: 1 }).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json(null);
            }
        )
    }

    oceniNastavnika = (req: express.Request, res: express.Response) => {
        CasModel.updateOne({ "nastavnik.korime": req.body.nastavnik.korime, start: req.body.start },
            { $set: { komentarZaNastavnika: req.body.komentarZaNastavnika, ocenaZaNastavnika: req.body.ocenaZaNastavnika } }
        ).then(
            (ok) => {
                res.json({ message: "ok" });
            }
        ).catch(
            (err) => {
                res.json({ message: "not ok" });
                console.log(err);
            }
        )
    }

    oceniUcenika = (req: express.Request, res: express.Response) => {
        CasModel.updateOne({ "nastavnik.korime": req.body.nastavnik.korime, start: req.body.start },
            { $set: { komentarZaUcenika: req.body.komentarZaUcenika, ocenaZaUcenika: req.body.ocenaZaUcenika } }
        ).then(
            (ok) => {
                res.json({ message: "ok" });
            }
        ).catch(
            (err) => {
                res.json({ message: "not ok" });
                console.log(err);
            }
        )
    }

    dohvatiObavestenjaUcenika = (req: express.Request, res: express.Response) => {
        CasModel.find({
            "ucenik.korime": req.params.korime, $or: [
                { status: "potvrdjen" }, { status: "odbijen" }, { status: "otkazan" }
            ]
        }).sort({ datumFormiranja: -1 }).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                res.json([]);
                console.log(err);
            }
        )
    }

    oznaciProcitano = (req: express.Request, res: express.Response) => {
        CasModel.updateMany({
            "ucenik.korime": req.body.korime, $or: [
                { status: "potvrdjen" }, { status: "odbijen" }, { status: "otkazan" }
            ]
        }, { $set: { procitan: true } }).then(
            (ok) => {
                res.json({ message: "ok" });
            }
        ).catch(
            (err) => {
                res.json({ message: "not ok" });
                console.log(err);
            }
        )
    }

    promeniStatusCasa = (req: express.Request, res: express.Response) => {
        CasModel.updateOne({
            predmet: req.body.predmet,
            "nastavnik.korime": req.body.nastavnik.korime,
            start: req.body.start
        }, {
            $set:
            {
                status: req.body.status,
                obrazlozenje: req.body.obrazlozenje,
                procitan: false,
                datumFormiranja: new Date()
            }
        }).then(
            (ok) => {
                res.json({ message: 'ok' });
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json({ message: "not ok" });
            }
        )
    }

    dodajRadnoVreme = (req: express.Request, res: express.Response) => {
        let radnoVreme = {
            start: new Date(req.body.datumOd),
            end: new Date(req.body.datumDo),
            nastavnik: req.body.nastavnik
        }
        var queryDate = new Date(radnoVreme.start);

        RadnoVremeModel.findOne({
            "nastavnik.korime": req.body.nastavnik.korime,
            $expr: {
                $and: [
                    { $eq: [{ $dayOfMonth: "$start" }, { $dayOfMonth: queryDate }] },
                    { $eq: [{ $month: "$start" }, { $month: queryDate }] },
                    { $eq: [{ $year: "$start" }, { $year: queryDate }] },
                    { $eq: [{ $dayOfMonth: "$end" }, { $dayOfMonth: queryDate }] },
                    { $eq: [{ $month: "$end" }, { $month: queryDate }] },
                    { $eq: [{ $year: "$end" }, { $year: queryDate }] }
                ]
            }
        }).then(
            async (data) => {
                if (data == null) {
                    let data1 = await new RadnoVremeModel(radnoVreme).save();
                }
                else {
                    if (data.start === undefined || data.end === undefined || data.start == null || data.end == null) {
                        return;
                    }
                    let data1 = await RadnoVremeModel.updateOne({
                        "nastavnik.korime": radnoVreme.nastavnik.korime,
                        start: new Date(data.start),
                        end: new Date(data.end)
                    }, {
                        start: radnoVreme.start,
                        end: radnoVreme.end
                    });
                }

                let data1 = await CasModel.updateMany({
                    "nastavnik.korime": req.body.nastavnik.korime,
                    $expr: {
                        $and: [
                            { $eq: [{ $dayOfMonth: "$start" }, { $dayOfMonth: queryDate }] },
                            { $eq: [{ $month: "$start" }, { $month: queryDate }] },
                            { $eq: [{ $year: "$start" }, { $year: queryDate }] },
                            { $eq: [{ $dayOfMonth: "$end" }, { $dayOfMonth: queryDate }] },
                            { $eq: [{ $month: "$end" }, { $month: queryDate }] },
                            { $eq: [{ $year: "$end" }, { $year: queryDate }] }
                        ]
                    },
                    $or:
                        [
                            { start: { $lt: radnoVreme.start } },
                            { start: { $gte: radnoVreme.end } },
                            { end: { $lte: radnoVreme.start } },
                            { end: { $gt: radnoVreme.end } }
                        ]
                },
                    {
                        status: "otkazan",
                        procitan: false,
                        datumFormiranja: new Date(),
                        obrazlozenje: "Nastavnik je za ovaj dan azurirao radno vreme tako da se njegova nedostupnost poklapa sa ovim casom."
                    })

                res.json({ message: "ok" });
            }
        ).catch(
            (err) => {
                res.json({ message: "Interna serverska greska" });
                console.log(err);
            }
        )
    }

    prosecanBrojCasovaPoDanu = (req: express.Request, res: express.Response) => {
        CasModel.aggregate([
            {
                $match: {
                    start: {
                        $gte: new Date('2023-01-01'),
                        $lt: new Date('2024-01-01')
                    },
                    status: 'potvrdjen'
                }
            },
            {
                $group:
                {
                    _id: { $dayOfWeek: "$start" },
                    avg: { $sum: 1 }
                }
            },
            {
                $project:
                {
                    _id: 0,
                    razred: "$_id",                    // razred ce biti dan u nedelji, prosecnaOcena prosek
                    prosecnaOcena: { $divide: ["$avg", 52] }
                }
            }
        ]).sort({ razred: 1 }).then(
            (data) => {
                data.forEach(element => {
                    element.razred = (element.razred - 2) % 7
                });
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json([]);
            }
        )
    }

    topDesetAngazovanihNastavnika = (req: express.Request, res: express.Response) => {
        CasModel.aggregate([
            {
                $match: {
                    start: {
                        $gte: new Date('2023-01-01'),
                        $lt: new Date('2024-01-01')
                    },
                    status: 'potvrdjen'
                }
            },
            {
                $group:
                {
                    _id: "$nastavnik.korime",
                    count: { $sum: 1 },
                }
            },
            {
                $project:
                {
                    _id: 0,
                    korime: "$_id",
                    angazovanje: "$count"
                }
            }
        ]).sort({ angazovanje: -1 }).limit(10).then(
            async (data) => {
                let result: { korime: string, angazovanja: number[] }[] = [];
                await Promise.all(data.map(async elem => {
                    let angazovanja: number[] = [];

                    for (let month = 1; month <= 12; month++) {
                        let data1 = await CasModel.aggregate([
                            {
                                $match: {
                                    start: {
                                        $gte: new Date('2023-01-01'),
                                        $lt: new Date('2024-01-01')
                                    },
                                    "nastavnik.korime": elem.korime,
                                    "status": 'potvrdjen',
                                    $expr: {
                                        $eq: [{ $month: "$start" }, month]
                                    }
                                }
                            },
                            {
                                $group: {
                                    _id: "$nastavnik.korime",
                                    count: { $sum: 1 }
                                }
                            },
                            {
                                $project: {
                                    _id: 0,
                                    korime: "$_id",
                                    ang: "$count"
                                }
                            }
                        ]);
                        if (data1.length >= 1) {
                            angazovanja.push(data1[0].ang);
                        }
                        else {
                            angazovanja.push(0);
                        }
                    }
                    result.push({
                        korime: elem.korime,
                        angazovanja: angazovanja
                    });
                }));
                res.json(result);
            }
        ).catch(
            (err) => {
                console.log(err);
            }
        )
    }

    odrzaniCasoviSedamDana = (req: express.Request, res: express.Response) => {
        let currDate = new Date();
        CasModel.aggregate([
            {
                $match: {
                    status: "potvrdjen",
                    start: { $lt: currDate }
                }
            },
            {
                $addFields: {
                    timeDifference: { $subtract: [currDate, "$start"] }
                }
            },
            {
                $match: {
                    timeDifference: { $lte: 7 * 24 * 60 * 60 * 1000 }
                }
            }
        ]).then(
            (data) => {
                res.json({ broj: data.length });
            }
        ).catch(
            (err) => {
                console.log(err);
            }
        )
    }

    odrzaniCasoviMesecDana = (req: express.Request, res: express.Response) => {
        let currDate = new Date();
        CasModel.aggregate([
            {
                $match: {
                    status: "potvrdjen",
                    start: { $lt: currDate }
                }
            },
            {
                $addFields: {
                    timeDifference: { $subtract: [currDate, "$start"] }
                }
            },
            {
                $match: {
                    timeDifference: { $lte: 30 * 24 * 60 * 60 * 1000 }
                }
            }
        ]).then(
            (data) => {
                res.json({ broj: data.length });
            }
        ).catch(
            (err) => {
                console.log(err);
            }
        )
    }

    brojOdrzanihCasovaPoPredmetu = (req: express.Request, res: express.Response) => {
        let currDate = new Date();
        CasModel.aggregate([
            {
                $match: {
                    status: "potvrdjen",
                    start: { $lt: currDate }
                }
            },
            {
                $group: {
                    _id: "$predmet",
                    count: {$sum: 1}
                }
            },
            {
                $project: {
                    _id: 0,
                    predmet: "$_id",
                    broj: "$count"
                }
            }
        ]).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                console.log(err);
            }
        )
    }
}