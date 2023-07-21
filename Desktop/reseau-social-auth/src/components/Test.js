import React, { useCallback, useEffect, useState } from 'react'
import { useContext } from 'react'
import { ContextAUTH } from '../context/ContextAuth'
import ButtonSubmit from './UI/ButtonSubmit'
import FicheTest from './FicheTest'
import ErrorModalOverlay from './Auth/ErrorModalOverlay'

const Test = (props) => {
  const dataContext = useContext(ContextAUTH);
  const [dataFicheUser, setDataFicheUser] = useState([]);
  const [show, setShow] = useState(true);

  console.log("le context ici ->")
  console.log(dataContext);

  const fetchHandlerUser = () => {
    fetch(`http://localhost:3000/crud/readOne/${dataContext.data.id}`, {
        headers: {
            "Authorization" : `Bearer ${dataContext.data.token}`
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log(data)
        const transformData = () => {
            return {
                id: data?.message[0]?.id_fiche_user,
                name: data?.message[0]?.name_fiche_user,
                image: data?.message[0]?.image_fiche_user,
                created_at: data?.message[0]?.createdAt_fiche_user,
                ficheUserId: data.message[0]?.userID_fiche_user,
                likes: data?.message[0]?.likes_fiche_user,
                dislikes: data?.message[0]?.dislikes_fiche_user,
                job: data?.message[0]?.job_fiche_user,
                bio: data?.message[0]?.bio_fiche_user,
            }
            }
        setDataFicheUser((curr) => [...curr, transformData()])
    })
    .catch( error => console.error(error))
  };


  useEffect(()=>{
    // if(dataFicheUser.length > 0){
        fetchHandlerUser();
    // }
  },[]);

  console.log(dataFicheUser)

  const onRefresh = () => {
    console.log("--> refresh du composant")
  }

  return (
    <div style={{color:"#f1f1f1", textAlign:"center", marginTop:"40px"}}>
        {
            dataContext.data.isLogged ?
            <>
                <h3>id: {dataContext.data.id}</h3>
                <h1>Bienvenue, {dataContext?.data?.loginConnect?.split('@')[0]}</h1>
                <hr />
                <div>
                    <h1>Fiche user</h1>
                    {
                        dataFicheUser?.map((user, index) => {
                            return (
                                <FicheTest
                                    key={index} 
                                    data={user} 
                                    refresh={onRefresh}
                                />
                            )
                        })
                    }
                </div>
                <hr />
            </> 
            :
            <>
            <h2>Vous êtes pas connecté</h2>
            {show && dataContext.data.deleted && <ErrorModalOverlay value={"Compte a bien été supprimé"} close={() => setShow(false)} />}
            </>
        }
    </div>
  )
}

export default Test