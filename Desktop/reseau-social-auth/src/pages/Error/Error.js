import React from 'react'
import { useNavigate } from "react-router-dom";


const Error = () => {
  const navigate = useNavigate();
  return (
    <div>
        <h1>Cette page n'existe pas !</h1>
        {/* {
            setTimeout(() => {
                navigate('/')
            },1000)
        } */}
    </div>
  )
}

export default Error