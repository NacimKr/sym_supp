const userCRUD = require('../models/userCRUD');

const likes = async(req, res) => {
  const userPostID = req.params.userPost;
  const getUserByID = await userCRUD.find({userID: userPostID});
  console.log(getUserByID)

  if(!getUserByID[0].usersIDLiked.includes(userPostID)){
    const getUserForIncrementLike = await userCRUD.updateOne(
      {userID: userPostID},
      { $inc : { likes: + 1 }, $push : { usersIDLiked: userPostID }}
    );  
    res.status(202).json({
      message:"Vous avez aimé ce post",
      users: getUserByID
    });
  }else{
    res.status(202).json({
      message:"Vous avez déjà aimé ce post"
    });
  }

}


const dislikes = async(req, res) => {
  const userPostID = req.params.userPost;
  const getUserById = await userCRUD.find({userID:userPostID});

  if(!getUserById[0].usersIDdisliked.includes(userPostID)){
    const getUserForDislike = await userCRUD.updateOne(
      {userID: userPostID},
      {
        $inc : { likes: -1 }, 
        $pull : { usersIDLiked: userPostID }, 
        $inc : { dislikes: +1 }, 
        $push : { usersIDdisliked: userPostID }
      }
    )
  
    res.status(202).json({
      message:"Vous n'aimez plus ce post",
      users: getUserForDislike
    })
  }else{
    res.status(202).json({
      message:"Vous avez déjà pas aimé ce post",
    })
  }
  
}

module.exports = {likes, dislikes};