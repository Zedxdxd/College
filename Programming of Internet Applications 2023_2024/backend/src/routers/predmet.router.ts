import express from 'express'
import { PredmetController } from '../controllers/predmet.controller';

const predmetRouter = express.Router()

predmetRouter.route("/dohvatiSvePredmete").get(
    (req, res) => new PredmetController().dohvatiSvePredmete(req, res)
);

predmetRouter.route("/dohvatiZahtevanePredmete").get(
    (req, res) => new PredmetController().dohvatiZahtevanePredmete(req, res)
);

predmetRouter.route("/odobriPredmet").post(
    (req, res) => new PredmetController().odobriPredmet(req, res)
);

predmetRouter.route("/dodavanjeNovPredmet").post(
    (req, res) => new PredmetController().dodavanjeNovPredmet(req, res)
);

export default predmetRouter;