import express from 'express';
import cors from 'cors';
import mongoose from 'mongoose';
import korisnikRouter from './routers/korisnik.router';
import pitanjeRouter from './routers/pitanje.router';
import predmetRouter from './routers/predmet.router';
import fajloviRouter from './routers/fajlovi.router';
import casRouter from './routers/cas.router';

const app = express();
app.use(cors());
app.use(express.json());

mongoose.connect("mongodb://127.0.0.1:27017/mojNajdraziNastavnik");
mongoose.connection.once("open", () => {
    console.log("DB ok!");
})

const router = express.Router();

router.use('/korisnici', korisnikRouter);
router.use('/pitanja', pitanjeRouter);
router.use('/predmeti', predmetRouter);
router.use('/fajlovi', fajloviRouter);
router.use('/casovi', casRouter);

app.use('/', router)
app.use(express.static('public'));

app.listen(4000, () => console.log(`Express server running on port 4000`));