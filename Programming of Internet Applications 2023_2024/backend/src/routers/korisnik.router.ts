import express from 'express';
import { KorisnikController } from '../controllers/korisnik.controller';
import multer from 'multer';

const korisnikRouter = express.Router();

korisnikRouter.route('/login').post(
    (req, res) => new KorisnikController().login(req, res)
)

korisnikRouter.route('/loginAdmin').post(
    (req, res) => new KorisnikController().loginAdmin(req, res)
)

const upload = multer({dest: 'uploads/'});

korisnikRouter.route('/registracijaUcenika').post(upload.single('slika'), 
    (req, res) => new KorisnikController().registracijaUcenika(req, res)
)

korisnikRouter.route('/registracijaNastavnika').post(upload.fields([{ name: 'cv' }, { name: 'slika' }]), 
    (req, res) => new KorisnikController().registracijaNastavnika(req, res)
)

korisnikRouter.route('/dohvatiKorisnika/:korime').get(
    (req, res) => new KorisnikController().dohvatiKorisnika(req, res)
);

korisnikRouter.route('/promenaLozinke').post(
    (req, res) => new KorisnikController().promenaLozinke(req, res)
)

korisnikRouter.route('/proveraLozinke').post(
    (req, res) => new KorisnikController().proveraLozinke(req, res)
);

korisnikRouter.route('/azuriranjeUcenika').post(
    (req, res) => new KorisnikController().azuriranjeUcenika(req, res)
);

korisnikRouter.route('/azuriranjeNastavnika').post(
    (req, res) => new KorisnikController().azuriranjeNastavnika(req, res)
);

korisnikRouter.route('/azuriranjeProfilne').post(upload.single('slika'), 
    (req, res) => new KorisnikController().azuriranjeProfilne(req, res)
);

korisnikRouter.route('/dohvatiSveUcenike').get(
    (req, res) => new KorisnikController().dohvatiSveUcenike(req, res)
)

korisnikRouter.route('/dohvatiSveOdobreneNastavnike').get(
    (req, res) => new KorisnikController().dohvatiSveOdobreneNastavnike(req, res)
);

korisnikRouter.route('/dohvatiSveZahteveNastavnike').get(
    (req, res) => new KorisnikController().dohvatiSveZahteveNastavnike(req, res)
);

korisnikRouter.route('/azuriranjeRegistracijeNastavnika').post(
    (req, res) => new KorisnikController().azuriranjeRegistracijeNastavnika(req, res)
);

korisnikRouter.route('/izracunajProsecneOceneNastavnika').get(
    (req, res) => new KorisnikController().izracunajProsecneOceneNastavnika(req, res)
);

korisnikRouter.route('/izracunajProsecneOceneUcenika').get(
    (req, res) => new KorisnikController().izracunajProsecneOceneUcenika(req, res)
);

korisnikRouter.route("/procenatPolovaNastavnika").get(
    (req, res) => new KorisnikController().procenatPolovaNastavnika(req, res)
)

korisnikRouter.route("/procenatPolovaUcenika").get(
    (req, res) => new KorisnikController().procenatPolovaUcenika(req, res)
)

korisnikRouter.route("/procenatUzrastaUcenika").get(
    (req, res) => new KorisnikController().procenatUzrastaUcenika(req, res)
)

export default korisnikRouter;