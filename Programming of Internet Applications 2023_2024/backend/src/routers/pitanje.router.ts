import express from 'express';
import { PitanjeController } from '../controllers/pitanje.controller';

const pitanjeRouter = express.Router();

pitanjeRouter.route('/dohvatiSvaPitanja').get(
    (req, res) => new PitanjeController().dohvatiSvaPitanja(req, res)
)

export default pitanjeRouter;