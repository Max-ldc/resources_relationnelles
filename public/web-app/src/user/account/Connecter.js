  import { useState } from 'react';
  import './account-css.css';
  // import axios from 'axios';


  const Connecter = () => {
    const [username, setUsername] = useState('');





  
    return (
      <div >
        <h2>Page Connecter si Déjà client ?!</h2>
        <form  className='connecter-block'>
          <div>
            <label>Username</label>
            <input type="text" value={username} onChange={(e) => setUsername(e.target.value)} />
          </div>
          {/* <div>
            <label>Email:</label>
            <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} />
          </div> */}
          <button type="submit">Se connecter</button>
        </form>
      </div>
    );
};
  
  
export default Connecter;