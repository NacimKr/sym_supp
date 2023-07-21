const jwt = require('jsonwebtoken');

const authentification = (req, res, next) => {
  const getToken = req.headers.authorization.split(' ')[1];
  console.log(getToken);

  const getIDFromToken = jwt.verify(getToken, "shhhhh");
  console.log(getIDFromToken);

  if(getIDFromToken.id == req.params.idUser){
    next()
  }else{
    console.log('Echec authentification')
  }

  console.log(req.params)
    
  }
// }

module.exports = authentification