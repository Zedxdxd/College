import express from 'express'
import PredmetModel from '../models/predmet'
import KorisnikModel from '../models/korisnik'

export class PredmetController {
    dohvatiSvePredmete = (req: express.Request, res: express.Response) => {
        PredmetModel.find({odobren: "da"}).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                res.json(null);
                console.log(err);
            }
        )
    }

    dohvatiZahtevanePredmete = (req: express.Request, res: express.Response) => {
        PredmetModel.find({odobren: "ne"}).then(
            (data) => {
                res.json(data);
            }
        ).catch(
            (err) => {
                res.json(null);
                console.log(err);
            }
        )
    }

    odobriPredmet = (req: express.Request, res: express.Response) => {
        PredmetModel.updateOne({naziv: req.body.naziv}, {odobren: 'da'}).then(
            async (ok) => {
                let data = await PredmetModel.findOne({naziv: req.body.naziv});
                if (data != null) {
                    for (let i = 0; i < data.nastavnici.length; i++){
                        await KorisnikModel.updateOne({korime: data.nastavnici[i].korime}, {$push : {predajePredmete: req.body.naziv}});
                    }
                }
                res.json({message: "ok"});
            }
        ).catch(
            (err) => {
                res.json({message: 'not ok'});
                console.log(err);
            }
        )
    }

    dodavanjeNovPredmet = (req: express.Request, res: express.Response) => {
        let novPredmet = {
            naziv: req.body.naziv,
            odobren: "da",
            nastavnici: []
        };
        new PredmetModel(novPredmet).save().then(
            (ok) => {
                res.json({message: 'ok'});
            }
        ).catch(
            (err) => {
                console.log(err);
                res.json({message: 'not ok'});
            }
        )
    }
}