import { Link } from "react-router-dom";
import "./navbar.css";

function Navbar() {
  return (
    <div className="header">
      <nav>
        <ul>
          <li>
            <Link to="/">Home</Link>
          </li>
          <li>
            <Link to="/service">Service</Link>
          </li>
        </ul>
      </nav>

      <div className="header-utilisateur">
        {/* <Link to="/account">
          <UserNavbar />
        </Link> */}
      </div>






    </div>
  );
}

export default Navbar;
