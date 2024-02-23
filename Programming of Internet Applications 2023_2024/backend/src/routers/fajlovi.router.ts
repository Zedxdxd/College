import express from 'express';
import { FajloviController } from '../controllers/fajlovi.controller';

const fajloviRouter = express.Router();

fajloviRouter.route('/dohvatiSliku/:filename').get(
    (req, res) => new FajloviController().dohvatiSliku(req, res)
);

fajloviRouter.route('/dohvatiCV/:filename').get(
    (req, res) => new FajloviController().dohvatiCV(req, res)
);

export default fajloviRouter;