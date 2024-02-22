import { Link } from 'react-router-dom';
import User from './user/User'
import './navbar.css';

function Navbar() {
   return (
      <div className='header'>
            <nav>
               <ul>
                  <li>
                     <Link to="/">Home</Link>
                  </li>
                  <li>
                     <Link to="/about">About</Link>
                  </li>
                  <li>
                     <Link to="/products">Products</Link>
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