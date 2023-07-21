import React, { useContext } from 'react'
import { NavLink, useNavigate } from "react-router-dom";
import { ContextAUTH } from '../context/ContextAuth';

const NavBar = () => {
  const getLogout = useContext(ContextAUTH);
  const navigate = useNavigate()
  console.log(getLogout);
  return (
    <nav>
        <ul>
            <li><NavLink to={"/"} style={{"color":
                ({ isActive }) => isActive ? "active" : ""
            }}>Home</NavLink></li>
            <li><NavLink to={"/login"}>Profile</NavLink></li>
            {
                getLogout.data.isLogged === null ||  !getLogout.data.isLogged? 
                <li onClick={()=>{
                    navigate('/')
                }}>Connexion</li>
                :
                <li onClick={()=>{
                    getLogout.logoutHandler()
                    navigate('/')
                }}>Déconnexion</li>
            }
        </ul>
    </nav>
  )
}

export default NavBar