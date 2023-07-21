const mongoose = require('mongoose');

//Envoie d'erreur en cas de crache du serveur
const handleError = err => {
  return err
}

const main = async() => {
  try{
    await mongoose.connect('mongodb://127.0.0.1:27017/node-test')
      .catch(() => handleError("Erreur de connexion au serveur MongoDB"));
  }catch(error){
    handleError(error)
  }
}

module.exports = main();