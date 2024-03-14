import { Link } from "react-router-dom";
import "./navbar.css";

function Navbar() {
  return (
    <header className="header">
      
      <div className="logo-block">
      <Link to="/">
          <div><img src="logo-3.png" className="site-logo" alt="logo" /></div>
          <div className="title-site"><span>Sources Relationnelles</span></div>
          <div className="logo-little-text">La plateforme pour améliorer vos relations</div>
      </Link> 
      </div>



      <nav>
        <ul>
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
            <Link to="/home2">H2</Link>
          </li>
          <li>
            <Link to="/service">Service</Link>
          </li>

          
        </ul>
      </nav>






    </header>
  );
}

export default Navbar;
