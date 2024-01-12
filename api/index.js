// récupere les fonctions dans le fichier functions.js
import * as functions from './functions.js';
import express from 'express';
const app = express()
const port = 3000

app.use(function (req, res, next) {
    res.setHeader('Access-Control-Allow-Origin', '*');
    if (req.method === 'OPTIONS') {
        res.setHeader('Access-Control-Allow-Methods', 'GET');
        res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
        return res.status(200).json({});
    }
    next();
});

app.get('/', (req, res) => {
    res.send('API Dev&+ Google Chrome Carousel Page')
})

app.get('/help', (req, res) => {
    res.redirect('http://elidev.fr');
})

app.get('/displayList/:displayId', (req, res) => {
    functions.getDisplayList(req.params.displayId)
        .then((list) => {
            res.send(list);
        })
        .catch((err) => {
            console.log(err);
        })
})

app.get('/displayList/:displayId/exist', (req, res) => {
    functions.getDisplayListExist(req.params.displayId)
        .then((exist) => {
            res.send(exist);
        })
        .catch((err) => {
            console.log(err);
        })
})

app.listen(port, () => {
    console.log(`The app are started on port ${port}`)
})