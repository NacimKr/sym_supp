import { createContext, useState } from "react";
import { useNavigate } from "react-router-dom";

export const ContextAUTH = createContext()

const ContextAUTHProvider = ({children}) => {
    const [token, setToken] = useState(localStorage.getItem('monToken'))
    const [loginConnect, setLoginConnect] = useState(localStorage.getItem('monLogin'))
    const [id, setID] = useState(localStorage.getItem('monID'))
    const [isLogged, setIsLogged] = useState(localStorage.getItem('imLogged'))
    const [deleted, setDeleted] = useState(false)

    const [data, setData] = useState({
        id,
        isLogged,
        token,
        loginConnect,
        deleted:false
    });

    const loginHandler = (token, id, loginConnect) => {
        setData({
            ...data,
            id,
            isLogged: true,
            token,
            loginConnect,
            deleted:false
        });

        localStorage.setItem('monToken', token)
        localStorage.setItem('monID', id)
        localStorage.setItem('monLogin', loginConnect)
        localStorage.setItem('imLogged', true)
        
        console.log(data)
    }

    const logoutHandler = () => {
        setData({
            ...data,
            id:null,
            isLogged: false,
            token:null,
            loginConnect:null,
            deleted:true
        });

        // setLocalStorageValue('')
        localStorage.removeItem('monToken')
        localStorage.removeItem('monID')
        localStorage.removeItem('monLogin')
        localStorage.removeItem('imLogged')
    }

    return (
        <ContextAUTH.Provider value={{
            data, 
            loginHandler, 
            logoutHandler, 
        }}>
            {children}
        </ContextAUTH.Provider>
    )
}

export default ContextAUTHProvider;