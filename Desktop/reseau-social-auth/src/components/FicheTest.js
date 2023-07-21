import React, { useContext, useEffect, useRef, useState } from 'react'
import { ContextAUTH } from '../context/ContextAuth';
import ButtonSubmit from './UI/ButtonSubmit';
import ErrorModalOverlay from './Auth/ErrorModalOverlay';
import ContainerModal from './Auth/ContainerModal';
import { Alert } from 'react-bootstrap';

const FicheTest = ({data, refresh}) => {
  /*
    On créer ici d'autres state afin de pouvoir mettre à jour les données
    si on souhaite les modifier
  */
  const [dataUpdate, setDataUpdate] = useState(data);
  const [valueInput, setValueInput] = useState(data)
  const [isUpdate, setIsUpdate] = useState(false);
  const [sureToDelete, setSureToDelete] = useState(false)

  const dataContext = useContext(ContextAUTH);

  const nameInput = useRef();
  const imageInput = useRef();
  const jobInput = useRef();
  const bioInput = useRef();

  const callAPIForDeleteUserAndFicheUser = (url) => {
    try{
      fetch(url, {
        method:"DELETE",
        headers:{
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          "Authorization" : `Bearer ${dataContext.data.token}`
        },
        body:JSON.stringify(dataUpdate)
      })
      .then(res => res.json())
      .then(data => {
        //ce setUpdate permet de gerer l'affichage lors de la suppression de la fiche user
        setDataUpdate(data)
        console.log(data)
      })
      .catch((err) => {
        throw new Error('La suppression est impossible')
      })
    }catch(err){
      console.error(err.message)
    }
  }
  

  const modifiyData = async() => {
    setIsUpdate(curr => !curr);
    const url = `http://localhost:3000/crud/update/${dataContext.data.id}?userID_fiche_user=${dataContext.data.id}`;
    const appel = await fetch(url, {
      method:'PUT',
      headers:{
        'Content-Type': 'application/json; charset=UTF-8',
        "Authorization" : `Bearer ${dataContext.data.token}`
      },
      body:JSON.stringify(dataUpdate)
    });

    const response = await appel.json()
    console.log('id form context',dataContext.data.id)
    console.log('id form dataUpdate',dataUpdate.id)
    console.log(dataUpdate)
  }

  console.log(dataContext)
  
  // //ON RAFRAICHIT LE COMPOSANT

  const deleteData = () => {
    callAPIForDeleteUserAndFicheUser(`http://localhost:3000/crud/delete/${dataContext.data.id}?userID_fiche_user=${dataContext.data.id}`);
    callAPIForDeleteUserAndFicheUser(`http://localhost:3000/deleteUser/${dataContext.data.id}`);
    dataContext.logoutHandler()
    dataContext.data.deleted=true
  }

  
  const handleInput = async() => {
    const newName = nameInput?.current?.value;
    const newImage = imageInput?.current?.files[0]?.name;
    const newJob = jobInput?.current?.value;
    const newBio = bioInput?.current?.value;
    setValueInput(newName)
    
    //Creation d'un objet afin de voirles mis a jour directement
    //car avec le useState y'a un temps de latence de 1 lettres
    const objetRefName = {
      name:newName,
      image:"http://localhost:3000/images/"+newImage
    };

    setDataUpdate(
      {...dataUpdate, 
        name:newName,
        image: newImage === undefined ? data.image : "http://localhost:3000/images/"+newImage,
        job: newJob,
        bio: newBio
      }
    );      
  }
  
  return (
    <section>
      {
        sureToDelete && 
        <ContainerModal>
          <div style={{position:"absolute", top:"50%", left:"50%", transform:"translate(-50%, -50%)"}}>
            <Alert variant="danger" onClose={()=>setSureToDelete(false)} dismissible>
              <Alert.Heading>Etes vous sur de vouloir le supprimer</Alert.Heading>
              <ButtonSubmit click={()=>{deleteData()}} color="btn btn-success">Oui</ButtonSubmit>
              <ButtonSubmit click={()=>{setSureToDelete(false)}} color="btn btn-danger">Non</ButtonSubmit>
            </Alert>
          </div>
        </ContainerModal>
      }
      <>
        <hr />
{
  Object.values(dataUpdate)[0] !== undefined ?
  <>

      <div style={{
          margin:"0px 20px",
          display:"flex", 
          justifyContent:"center", 
          gap:"40px"
        }}>
          {
            isUpdate ?
            <div style={{display:"flex", flexDirection:"column"}}>
              <img 
                src={dataUpdate.image} 
                alt="" 
                style={{width:"200px", borderRadius:"20px"}} 
              />
              <input 
                type="file"
                ref={imageInput} 
                onChange={handleInput} 
                accept='.jpeg,.jpg,.png' 
                name="image"
              />
            </div>
            :
            <img 
              src={dataUpdate.image} 
              alt="" 
              style={{width:"200px", borderRadius:"20px", height:"200px"}} 
            />
          }


          <div style={{
            display:"flex", 
            flexDirection:"column", 
            alignItems:"center"
          }}>
            {
              isUpdate ? 
              <>
                <label htmlFor="nom">Nom</label>
                <input 
                  ref={nameInput} 
                  onChange={handleInput} 
                  type="text" 
                  value={dataUpdate.name} 
                  style={{color:"black"}} 
                  name="name"
                />
              </>
              :
              <>
                <h4>id : {dataUpdate.id}</h4>
                <h4>Nom : {dataUpdate.name}</h4>
              </>
            }
            <h5>Compte créé le : {dataUpdate.created_at}</h5>
            <br />
            <h5>Profession :</h5>
            {
              !isUpdate ?
              <p>{dataUpdate.job}</p> :
              <textarea 
                ref={jobInput} 
                cols="50" rows="3" 
                style={{resize:"none", padding:"5px", color:"black"}}
                onChange={handleInput}
                value={dataUpdate.job}
                name="job"
              >
              </textarea>
            }

            <h5>A propos de moi :</h5>
            {
              !isUpdate ?
              <p>{dataUpdate.bio}</p> :
              <textarea 
                ref={bioInput} 
                cols="50" rows="3" 
                style={{resize:"none", padding:"5px", color:"black"}}
                onChange={handleInput}
                value={dataUpdate.bio}
                name="bio"
              >
              </textarea>
            }
          </div>
        </div>
      <div style={{display:"flex", gap:"20px", justifyContent:"center", marginTop:"20px"}}>
        <ButtonSubmit click={()=>modifiyData()} color={"warning"}>
          {!isUpdate ? "Modifier la fiche" : "Enregister les modifications"}
        </ButtonSubmit>
        <ButtonSubmit click={()=>setSureToDelete(true)} color={"danger"}>Supprimer la fiche</ButtonSubmit>
      </div>
      </>:
    <h2>Pas de User</h2>
}
      </>
    </section>
  )
}

export default FicheTest