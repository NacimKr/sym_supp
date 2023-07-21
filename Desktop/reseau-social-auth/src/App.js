import FormAuth from "./components/Auth/FormAuth";
import NavBar from "./components/NavBar";
import Test from "./components/Test";
import Error from "./pages/Error/Error";
import FicheUser from "./pages/FicheUser";
import Home from "./pages/Home";
import { useContext } from 'react'
import { ContextAUTH } from "./context/ContextAuth";

import {
  BrowserRouter,
  Link,
  Route,
  Routes,
} from "react-router-dom";


function App() {

  const loginContext = useContext(ContextAUTH);
  console.log(loginContext.data)

  return (
    <div style={{background:"#223"}}>
      <BrowserRouter>
        <NavBar />
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<FicheUser />} />
          <Route path="/*" element={<Error />} />
        </Routes>
      </BrowserRouter>
      {console.log(BrowserRouter)}
    </div>
  );
}

export default App;
