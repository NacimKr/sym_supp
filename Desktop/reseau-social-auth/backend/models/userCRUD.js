const mongooseConnect = require('../db/db')
const mongoose = require('mongoose')

const userSchema = new mongoose.Schema({
  userID: {type: String, requred: true},
  name: {type: String, requred: true},
  image: {type: String, requred: true},
  likes: {type: Number, default : 0},
  dislikes: {type: Number, default : 0},
  usersIDLiked: {type: [String]},
  usersIDdisliked: {type: [String]},
  createdAt: {type: Date, default: Date.now()}
});

module.exports = mongoose.model('User', userSchema);