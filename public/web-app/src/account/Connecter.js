  import { useState } from 'react';
  import './account-css.css';
  import {Button, FieldError, Form, Input, Label, TextField} from 'react-aria-components';
  import { Link } from "react-router-dom";


  const Connecter = () => {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');


    //validation form
    let usernameErrors = [];
    let passwordErrors = [];
    // username
    if (username.length < 6) {
      usernameErrors.push('Le nom d utilisateur doit faire au moins 6 caractères');
    }
    if (username.length > 16) {
      usernameErrors.push('Le nom d utilisateur dépasse la limite de 16 caractères');
    }
    if ((username.match(/^[a-zA-Z0-9_]*$/) ?? []).length < 1) {
      usernameErrors.push('Le nom d utilisateur ne peut contenir que des chiffres, lettres et underscores');
    }
    // if (username.match('/^[a-zA-Z0-9_]+$/')) {
    //   errors.push('Password must include at least 2 upper case letters');
    // }
    // if ((username.match(/[A-Z]/g) ?? []).length < 2) {
    //   errors.push('Password must include at least 2 upper case letters');
    // }
    
    // password
    if (password.length <= 7) {
      passwordErrors.push('Le mot de passe doit faire au moins 8 caractères');
    }
    if (password.length > 32) {
      passwordErrors.push('Le mot de passe ne peut pas faire plus de 32 caractères');
    }




    return (
      <div className='connecter-container'>
        <div className='connecter'>
              <div><img src="logoRSR2.png" className="logo-for-account"alt="logoRSR" /></div>

            <h2>Se connecter</h2>
              <div className='text-accedez'>Accèdez à votre compte</div>
              <div className='text-star'>* champ obligatoire</div>

          <Form className='connecter-block'>

              <TextField name="username" type="text"  isInvalid={usernameErrors.length > 0}
                         value={username} onChange={setUsername} isRequired>
                  <Label>Nom d’utilisateur*</Label>
                  <Input value={username}  onChange={(e) => setUsername(e.target.value)}/>
                  {username ? (
                      <FieldError>
                          <div><ul>{usernameErrors.map((error, i) => <li key={i}>{error}</li>)}</ul></div>
                      </FieldError>
                    ) : ('')
                    }                       
              </TextField>


              <TextField name="password" type="password" isInvalid={passwordErrors.length > 0}
                         value={password} onChange={setPassword} isRequired>
                  <Label>Mot de passe*</Label>
                  <Input  onChange={(e) => setPassword(e.target.value)} />
                  {password ? (
                      <FieldError>
                          <ul>{passwordErrors.map((error, i) => <li key={i}>{error}</li>)}</ul>
                      </FieldError>
                        ) : ('')
                  }                   
              </TextField>



              <Button type="submit">Valider</Button>
          </Form>


          <Link to="/creer-compte" className='link-creer'>Créer un compte</Link>
      </div>
    </div>


      // <div className='connecter-block'>
      //   <h2>Page Connecter si Déjà client ?!</h2>
      //   <form  c>
      //     <div className='form-container'>
      //       {/* <label>Username</label> */}
      //       <input type="text" value={username} onChange={(e) => setUsername(e.target.value)} placeholder='username' />
      //     </div>
      //     {/* <div>
      //       <label>Email:</label>
      //       <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} />
      //     </div> */}
      //     <button type="submit">Se connecter</button>
      //   </form>
      // </div>
    );
};
  
  
export default Connecter;
