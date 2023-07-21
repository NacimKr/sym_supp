import React, { useContext, useRef, useState } from 'react'
import ButtonSubmit from "../UI/ButtonSubmit";
import Form from 'react-bootstrap/Form';
import ErrorModalOverlay from './ErrorModalOverlay';
import Loader from '../UI/Loader';
import { ContextAUTH } from '../../context/ContextAuth';
import Test from "../Test"
import { useNavigate } from 'react-router-dom';

const FormAuth = () => {
  const [show, setShow] = useState(false);
  const [error, setError] = useState("");
  const [isLoading, setIsLoading] = useState(false)
  const [isLogin, setIsLogin] = useState(true);
  const navigate = useNavigate()

  const emailInput = useRef(null);
  const passwordInput = useRef(null);

  const loginContext = useContext(ContextAUTH);
  console.log(loginContext.data)

  const toggleAuthHandler = () => {
    setIsLogin(!isLogin);
    //console.log(value.innerHTML)
  }
  console.log('isLogin= ', isLogin)
  
  const handleSubmit = (e) => {
    e.preventDefault();

    console.log(e.target.childNodes[2].innerHTML);
    const emailValue = emailInput.current.value;
    const passwordValue = passwordInput.current.value;

    if(isLogin){
      if (emailValue.length === 0) {
        setError('L\'email est pas remplie')
        setShow(true)
      } else if (passwordValue.length === 0) {
        setError('Le password est pas remplie')
        setShow(true)
      } else {
        const fetchAPIToAuthentification = async(login, password) => {
          const urlAuth = await fetch('http://localhost:3000/login', {
            method:'POST',
            body: JSON.stringify({login, password}),
            headers: { "Content-Type" : "application/json" }
          });
          const responseAuth = await urlAuth.json()
          
          const {token, id, loginConnect} = responseAuth;
          console.log(responseAuth)
          
          if(Object.values(responseAuth).includes('Mot de passe incorrect')){
            setError(responseAuth.message)
            setShow(true)
          }
          

          loginContext.loginHandler(token, id, loginConnect);
          if(token && id && loginConnect){
            navigate(`/login`, {replace:true})
          }
        }
        fetchAPIToAuthentification(emailValue, passwordValue);
      }
    }else{
      if (emailValue.length === 0) {
        setError('L\'email est pas remplie')
        setShow(true)
      } else if (passwordValue.length === 0) {
        setError('Le password est pas remplie')
        setShow(true)
      } else {
        const fetchAPIToAuthentification = async(login, password) => {
          const urlAuth = await fetch('http://localhost:3000/signUp', {
            method:'POST',
            body: JSON.stringify({login, password}),
            headers: { "Content-Type" : "application/json" }
          });
          const responseAuth = await urlAuth.json()
          setIsLogin(true);
          //const {token, id, loginConnect} = responseAuth;
          
          // if(Object.values(responseAuth).includes('Mot de passe incorrect')){
          //   setError(responseAuth.message)
          //   setShow(true)
          // }
          
          //loginContext.loginHandler(token, id, loginConnect);
        }
        fetchAPIToAuthentification(emailValue, passwordValue);
      }
    }
  }

  return (
    <>
      {
        show && <ErrorModalOverlay value={error} close={() => setShow(false)} />
      }
      {
        !loginContext.data.token && !loginContext.data.id ?
          <div className="d-flex flex-column align-items-center justify-content-center border w-50 m-auto p-3 mt-5 rounded" style={{ boxShadow: "5px 5px 10px black", background: "firebrick" }}>
            {
              isLogin ?
                <h1 className="text-light">Se connecter</h1>
                :
                <h1 className="text-light">Créer un compte</h1>
            }

            <Form className="w-75" onSubmit={(e) => handleSubmit(e)}>
              <Form.Group className="mb-3" controlId="formBasicEmail">
                <Form.Label className="text-light">Email address</Form.Label>
                <Form.Control ref={emailInput} type="email" placeholder="Enter email" />
              </Form.Group>

              <Form.Group className="mb-3" controlId="formBasicPassword">
                <Form.Label className="text-light">Password</Form.Label>
                <Form.Control ref={passwordInput} type="password" placeholder="Password" />
              </Form.Group>

              {
                isLoading ?
                  <Loader />
                  :
                  isLogin ?
                    <ButtonSubmit color={"success"}>
                      Se connecter
                    </ButtonSubmit>
                    :
                    <ButtonSubmit color={"primary"}>
                      Créer mon compte
                    </ButtonSubmit>
              }
              {
                !isLogin
                  ?
                  <h4
                    onClick={()=>toggleAuthHandler()}
                    style={{ textAlign: "center", margin: "30px 0px 0px", cursor: "pointer" }}
                  >Se connecter</h4>
                  :
                  <h4
                    onClick={(e)=>toggleAuthHandler(e.target)}
                    style={{ textAlign: "center", margin: "30px 0px 0px", cursor: "pointer" }}
                  >Créer un compte</h4>
              }
            </Form>
          </div>
          :
          <div style={{color:"#f1f1f1", textAlign:"center", marginTop:"20px"}}>
            <h3>Vos feeds</h3>
          </div>
      }
    </>
  );
}

export default FormAuth