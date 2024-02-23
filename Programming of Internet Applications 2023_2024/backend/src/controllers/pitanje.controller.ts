import express from 'express';
import PitanjeModel from '../models/pitanje'

export class PitanjeController {
    dohvatiSvaPitanja = (req: express.Request, res: express.Response) => {
        PitanjeModel.find({}).then(
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
}