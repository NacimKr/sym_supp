const validator = require('validator');
const passwordValidator = require('password-validator');


const isEmail = (req, res, next) => {
  const {login} = req.body;

  try{
    if(validator.isEmail(login)){
      next();
    }else{
      throw Error("L'email est invalide");
    }
  }catch(error){
    res.status(400).json({
      message:error.message
    })
  }
}


const isGoodPassword = (req, res, next) => {
  const schemaPassword = new passwordValidator();
  const badPassword = 'azerty';

  schemaPassword
    .is().min(5, "Mot de passe trop court")
    .is().max(20, "Mot de passe trop long")
    .is().not().oneOf([
      badPassword.toLocaleLowerCase(), 
      badPassword.toUpperCase()
    ]);

  try{
    if(schemaPassword.validate(req.body.password, {details: true}).length === 0){
      next();
    }else{
      const errorPassword = schemaPassword.validate(req.body.password, {details: true})
      errorPassword.forEach(err =>{
        throw Error(err.message);
      });
    }
  }catch(error){
    res.status(400).json({
      message:error.message
    });
  }
}

module.exports = {isEmail, isGoodPassword}