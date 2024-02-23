import express from 'express';
import { CasController } from '../controllers/cas.controller';

const casRouter = express.Router();

casRouter.route('/dodavanjeCas').post(
    (req, res) => new CasController().dodavanjeCas(req, res)
);

casRouter.route('/dohvatiCasoveNastavnika/:korime').get(
    (req, res) => new CasController().dohvatiCasoveNastavnika(req, res)
);

casRouter.route('/dohvatiOdrzaneCasoveNastavnika/:korime').get(
    (req, res) => new CasController().dohvatiOdrzaneCasoveNastavnika(req, res)
);

casRouter.route('/dohvatiCasoveUcenika/:korime').get(
    (req, res) => new CasController().dohvatiCasoveUcenika(req, res)
);

casRouter.route('/dohvatiRadnaVremena/:korime/:datum').get(
    (req, res) => new CasController().dohvatiRadnaVremena(req, res)
);

casRouter.route('/dohvatiCasoveNedelja/:korime/:datum').get(
    (req, res) => new CasController().dohvatiCasoveNedelja(req, res)
);

casRouter.route('/oceniNastavnika').post(
    (req, res) => new CasController().oceniNastavnika(req, res)
);

casRouter.route('/oceniUcenika').post(
    (req, res) => new CasController().oceniUcenika(req, res)
);

casRouter.route('/dohvatiObavestenjaUcenika/:korime').get(
    (req, res) => new CasController().dohvatiObavestenjaUcenika(req, res)
);

casRouter.route('/oznaciProcitano').post(
    (req, res) => new CasController().oznaciProcitano(req, res)
);

casRouter.route('/promeniStatusCasa').post(
    (req, res) => new CasController().promeniStatusCasa(req, res)
);

casRouter.route('/dodajRadnoVreme').post(
    (req, res) => new CasController().dodajRadnoVreme(req, res)
);

casRouter.route('/prosecanBrojCasovaPoDanu').get(
    (req, res) => new CasController().prosecanBrojCasovaPoDanu(req, res)
);

casRouter.route('/topDesetAngazovanihNastavnika').get(
    (req, res) => new CasController().topDesetAngazovanihNastavnika(req, res)
);

casRouter.route('/odrzaniCasoviSedamDana').get(
    (req, res) => new CasController().odrzaniCasoviSedamDana(req, res)
);

casRouter.route('/odrzaniCasoviMesecDana').get(
    (req, res) => new CasController().odrzaniCasoviMesecDana(req, res)
);

casRouter.route('/brojOdrzanihCasovaPoPredmetu').get(
    (req, res) => new CasController().brojOdrzanihCasovaPoPredmetu(req, res)
);
export default casRouter;