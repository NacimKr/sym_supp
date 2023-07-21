const express = require('express');
const router = express.Router();
const {create, read, readOneUser, readOneFicheUser, update, deleted} = require('../controllers/crud.controller');
const authentification = require('../middlewares/authentification');
const multerImage = require('../middlewares/multerImage')

router.post('/create', authentification, multerImage, create);

router.get('/read', authentification, read);
router.get('/readOne/:idUser', authentification, readOneUser);
router.get('/readOneFicheUser/:idFicheUser', authentification, multerImage, readOneFicheUser);

router.put('/update/:idUser', authentification, multerImage, update);
router.delete('/delete/:idUser', authentification, multerImage, deleted);



module.exports = router;