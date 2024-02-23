import { Link } from 'react-router-dom';
import './navbar.css';
import User from './user/User'

function Navbar() {
   return (
      <div className='header'>
            <nav>
               <ul>
                  <li>
                     <Link to="/">Home</Link>
                  </li>
                  <li>
                     <Link to="/service">Service</Link>
                  </li>
                  <li>
                     <Link to="/createUser">Creat User</Link>
                  </li>
               </ul>
               
            </nav>
         

         <div className='header-utilisateur'>
            <User />
         </div>




      </div>
   );
}

export default Navbar;