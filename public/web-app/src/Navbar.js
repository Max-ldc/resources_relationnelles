import { Link } from "react-router-dom";
import "./navbar.css";
import { Button } from "react-aria-components";
import { useState } from "react";



function Navbar() {
   const [isOpen,setOpenMenu] = useState();

  return (
    <header className="header">
      
      <div className="logo-block">
      <Link to="/">
          <div><img src="logo-3.png" className="site-logo" alt="logo" /></div>
          <div className="title-site"><span>Sources Relationnelles</span></div>
          <div className="logo-little-text">La plateforme pour améliorer vos relations</div>
      </Link> 
      </div>



      <nav className={`nav-header ${isOpen ? "open" : ""}`}>
        <ul className="nav-header__ul">
          <li>
            <Link to="/">Accueil</Link>
          </li>
          <li>
            <Link to="/ressources">Ressources</Link>
          </li>
          <li>
            <Link to="/uploader">Uploader</Link>
          </li>
          <li>
            <Link to="/account">Mon compte</Link>
          </li>
          <li>
            <Link to="/service">Admin</Link>
          </li>

          <li>
            <Link to="/home2">H2</Link>
          </li>
        </ul>
      </nav>
      <Button onClick={()=> setOpenMenu(!isOpen)} className="icon-menu-open">
      <img
                    className="likeDislike-img"
                    src="./menu-close.svg"
                    alt="like"
                  ></img>
      </Button>






    </header>
  );
}

export default Navbar;
