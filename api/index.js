const express = require('express')
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
    res.send('API Dev&+')
})

app.get('/help', (req, res) => {
    // redirige vers google
    res.redirect('http://elidev.fr');
})

app.get('/googleExtCarrouselComm/:displayCaracters', (req, res) => {
    //
    res.json({ displayType: "public", displayCaracters: req.params.displayCaracters });
})

app.get('/googleExtCarrouselComm/:displayCaracters/type', (req, res) => {
    res.json({ displayType: "public" });
})

app.listen(port, () => {
    console.log(`Example app listening on port ${port}`)
})