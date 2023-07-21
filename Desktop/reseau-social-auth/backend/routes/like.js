const express = require('express');
const authentification = require('../middlewares/authentification');
const {likes, dislikes} = require('../controllers/like.controller')
const router = express.Router();

router.post('/:userPost/like', authentification, likes)
router.post('/:userPost/dislike', authentification, dislikes)

module.exports = router