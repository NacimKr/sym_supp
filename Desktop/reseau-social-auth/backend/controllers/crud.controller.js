const userCRUDModel = require('../models/userCRUD');
const jwt = require('jsonwebtoken');
const userModels = require('../models/userModels');
const fs = require('fs');

//partie SQL
const mysqlConnection = require('../db/db.mysql');
const FicheUser = require('../models/ficheUserClass');



const create = (req, res) => {
  const {userID_fiche_user, name_fiche_user} = JSON.parse(req.body.user)
  
  const fiche_User = new FicheUser(
    userID_fiche_user, 
    name_fiche_user, 
    "http://localhost:3000/images/"+req.file.originalname,
    null,
    null
  );

  console.log('on est dans le create')
  console.log(fiche_User)
  console.log('on est dans le create')

  mysqlConnection.query(
    'INSERT INTO ficheuser SET ?',fiche_User,
    async(err, results) => {
      console.log(fiche_User)

      if(err){
        res.status(400).json({
          message:err
        })
      }else if(results){
        res.status(201).json({
          message:"Utilisateur créer avec succès",
          user:results
        })
      }
    }
  )
}



const read = async(_, res) => {
    mysqlConnection.query(
      'SELECT * FROM ficheuser',
      async function(err, results){
        if(err){
          res.status(400).json({
            message: err
          })
        }else if(results){
          res.status(200).json({
            message: results
          })
        }
      }
    )
}



const readOneUser = async(req, res) => {
  mysqlConnection.query(
    'SELECT * FROM ficheuser WHERE userID_fiche_user = ?',[req.params.idUser],
    async function(err, results){
      if(err){
        res.status(400).json({
          message: err
        })
      }else if(results){
        res.status(200).json({
          message: results
        })
      }
    }
  )
}




const readOneFicheUser = async(req, res) => {
  console.log("---->contenu de url")
  console.log(req.originalUrl)

  mysqlConnection.query(
    "SELECT * FROM ficheuser WHERE id_fiche_user = ?",[req.params.idFicheUser],
    async(err, results) => {
      if(err){
        res.status(400).json({
          message: err
        })
      }else if(results){
        res.status(200).json({
          message: results
        })
      }
    }
  )
}




const update = async(req, res) => {
  try{
      const {id, name, image, job, bio} = req.body
      console.log("---> dans le update")
      console.log(req.body)
      console.log("---> dans le update")

      mysqlConnection.query(
        `UPDATE ficheuser SET 
        name_fiche_user = ?, 
        image_fiche_user = ?,
        job_fiche_user = ?, 
        bio_fiche_user = ? 
        WHERE id_fiche_user = ? `,[name, image, job, bio, id],
        async function(err, results){
          if(err){
            res.status(400).json({
              message: err
            })
          }else if(results){
            res.status(200).json({
              message: "La modification du user a bien été effectuée",
              result: results,
              users: req.body
            })
        }
      }
    )
  }catch(err){
    console.log(err);
  }
}





const deleted = async(req, res) => {
  console.log(req.body.id)
  console.log(req.body.ficheUserId)
  console.log(Number(req.params.idUser))
  console.log(req.body)
  mysqlConnection.query(
    `DELETE f, u FROM ficheuser f INNER JOIN users u WHERE u.id = ${req.params.idUser} AND f.userID_fiche_user = ${req.params.idUser};`,
    async function(err, results){
      if(err){
        res.status(400).json({
          message: err
        })
      }else if(results){
        res.status(200).json({
          message: "La suppression du user a bien été effectuée",
          user: results
        })
      }
    }
  )
}

module.exports = {create, read, readOneUser, readOneFicheUser, update, deleted}