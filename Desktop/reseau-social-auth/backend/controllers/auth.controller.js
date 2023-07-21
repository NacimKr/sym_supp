const jwt = require('jsonwebtoken');
const mysqlConnection = require('../db/db.mysql');
const userAuth = require('../models/userClass');
var CryptoJS = require("crypto-js");
const {create} = require('./crud.controller');
const FicheUser = require('../models/ficheUserClass');




const signUp = async(req, res) => {
  console.log("on est dans la route signUp")
  const {login, password} = req.body;

  var loginOnce = CryptoJS.HmacSHA1(req.body.login, '123').toString();
  const userSignUp = new userAuth(login, password);
  
  // simple query
  mysqlConnection.query(
    "INSERT INTO users SET ?", userSignUp
  ,
  (err, results) => {
    if(err){
      res.status(400).json({
        message: "Erreur lors de la création de votre compte",
      });
    }
    
    if(results){
      setTimeout(() => {
        // create un user juste aprres la create du compte
        console.log(login);
        console.log(password);
        console.log(req);

        mysqlConnection.query(
          "SELECT * FROM `users` WHERE `login` = ?",
          [login], 
          async(err, results) => {
            if(err){
              console.log("erreur de login")
            }else if(results){
              console.log("compte créer après la création du compte")
              console.log(results)

              results.forEach(item => {
                const neAccountFicheUUser = new FicheUser(
                  item.id, item.login.split('@')[0], 'http://localhost:3000/images/user.png',
                  "",""
                );

                mysqlConnection.query(
                  'INSERT INTO ficheuser SET ?', neAccountFicheUUser,
                  async(err, results) => {
                    if(err){
                      console.log("Creation du user impossible")
                      console.log(err)
                    }else if(results){
                      console.log("Creation du user avec succès")
                      res.status(201).json({
                        message: "Votre compte a bien été créer avec succés",
                        users :userSignUp,
                        ficheUser: neAccountFicheUUser
                      });
                    }
                  }
                );

              });
            }
          }
        )
        // create un user juste aprres la create du compte
      },600);
    
    }
  }
  );
}

const login = async(req, res) => {
  //-----> Avec SQL
  const {login, password} = req.body
  const userLogin = new userAuth(login, password)

  mysqlConnection.query(
    "SELECT * FROM `users` WHERE `login` = ?",
    [userLogin.login], 
    async(err, results) => {

      if(err){
        res.status(400).json({
          message:"erreur de login",
        })
      }else{
        //On checke le login
        if(Object.keys(userLogin).length === 0){
          res.status(400).json({
            message:"pas d'utilisateur disponible",
          })
        }else{
          const isValidPassword = userLogin.password === results[0]?.password;
          const token = jwt.sign({id: results[0]?.id}, "shhhhh");
          if(isValidPassword){
            res.status(200).json({
              message:"Connexion réussi",
              id: results[0]?.id,
              loginConnect: results[0]?.login,
              token,
            })
          }else{
            res.status(400).json({
              message:"Mot de passe incorrect",
            })
          }
        }
      }
    }
  )
}


const deletedLoginUser = async(req, res) => {
  mysqlConnection.query(
    "DELETE FROM `users` WHERE `id` = ?",
    [req.params.idUserConnect], 
    async(err, results) => {
      if(err){
        res.status(400).json({
          message : err
        })
      }

      if(results){
        res.status(200).json({
          message:results
        })
      }
    }
  )
}


  //-----> MongoDB
  // const getUserToLogin = await userModels.find({ login : req.body.login})
  
  // if(!getUserToLogin){
  //   res.status(404).json({
  //     message:"Utilisateur introuvable"
  //   });
  // }

  // const isValidPassword = req.body.password === getUserToLogin[0].password
  
  // if(isValidPassword){
  //   const token = jwt.sign({ id: getUserToLogin[0]._id }, 'shhhhh');
  //   res.status(200).json({
  //     message:"Connexion réussi",
  //     token,
  //     id: getUserToLogin[0]._id
  //   })
  // }else{
  //   res.status(400).json({
  //     message:"le mot de passe est incorrect"
  //   })
  // }

module.exports = {signUp, login, deletedLoginUser}