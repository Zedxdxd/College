import express from 'express'

export class FajloviController {
    dohvatiSliku = (req: express.Request, res: express.Response) => {
        const imageName = req.params.filename;
        const imageUrl = `http://localhost:4000/profilneSlike/${imageName}`;
        res.json({ imageUrl });
    }

    dohvatiCV = (req: express.Request, res: express.Response) => {
        const cvName = req.params.filename;
        const cvUrl = `http://localhost:4000/cv/${cvName}`;
        res.json({ cvUrl });
    }
}