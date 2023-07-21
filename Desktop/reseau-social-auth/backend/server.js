const express = require('express');
const app = express();
const port = 3000;
const cors = require('cors');
// const bodyParser = require('body-parser'); 
const routerAuth = require('./routes/auth');
const routerCRUD = require('./routes/crud');
const routerLike = require('./routes/like');

// parse application/x-www-form-urlencoded
// app.use(bodyParser.urlencoded({ extended: true }));

// parse application/json
// app.use(bodyParser.json());
app.use(cors())

//Express nous fournit une méthode afin d'accéder au corpd de la requetes
//c'est le express.json()
app.use(express.json())

//Routes de l'API pour authentification
app.use('/', routerAuth);
app.use('/crud/', routerCRUD);
app.use('/', routerLike)


app.use('/images', express.static(__dirname + '/images'));


app.listen(port, () => console.log(`Serveur sur écoute sur le port ${port}`))