import React, { useState } from 'react';
import axios from 'axios';
import {Button, FieldError, Form, Input, Label, TextField} from 'react-aria-components';
import './account-css.css';
import { Link } from "react-router-dom";

const CreateUser = () => {
    const [username, setUsername] = useState('');
    const [email, setUserEmail] = useState('');
    const [errorEmail, setErrorEmail] = useState(false);
    const [password, setPassword] = useState('');
    const [messageReussir, setMessageReussir] = useState('');


    var response;

    const creatUser = (e) => {
        e.preventDefault();
        const newPost = { username, email};
        const customConfig = {  headers: { 'Content-Type': 'application/ld+json' }};

        response = axios.post('http://localhost/api/users', newPost, customConfig)
            .then(response => {
                console.log(response);
                setErrorEmail('');
                setMessageReussir('Votre compte a bien été créé !');
            })
            .catch(error => {
                console.log('-----'+ error.response.data.detail)
                setErrorEmail(error.response.data.detail);
            })
    };

    //validation form
    let usernameErrors = [];
    let emailErrors = [];
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

    if ((email.match(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i) ?? []).length < 1) {
        emailErrors.push('Le "@" et "." requise');
    }

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

                <h2>Créer un compte</h2>
                <div className='text-accedez'>Ouvrez la porte à de nouvelles possibilités</div>
                <div className='text-star'>* champ obligatoire</div>

                <div>
                    { errorEmail && !response ? (<h3 className='errorMessageExist'>{errorEmail}</h3>
                                  ) : ('')
                    }
                </div>
                <div>
                    { messageReussir && !errorEmail ? (<h3 className='messageReussir'>{messageReussir}</h3>
                                  ) : ('')
                    }
                </div>

        <Form className='connecter-block' onSubmit={creatUser}>

                <TextField name="username" type="text"  isInvalid={usernameErrors.length > 0}
                         value={username} onChange={setUsername} isRequired>
                  <Label>Nom d’utilisateur*</Label>
                  <Input onChange={(e) => setUsername(e.target.value)}/>
                  {username ? (
                      <FieldError>
                          <div><ul>{usernameErrors.map((error, i) => <li key={i}>{error}</li>)}</ul></div>
                      </FieldError>
                    ) : ('')
                    }                       
              </TextField>


                <TextField name="email" type="email" isInvalid={emailErrors.length > 0} 
                           value={email} onChange={setUserEmail}  isRequired>
                    <Label>Email*</Label>
                    <Input onChange={(e) => setUserEmail(e.target.value)}/>
                    {email ? (
                      <FieldError>
                          <div><ul>{emailErrors.map((error, i) => <li key={i}>{error}</li>)}</ul></div>
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


                <TextField name="password_verif" type="password" isRequired>
                    <Label>Confirmez le mot de passe*</Label>
                    <Input />
                    <FieldError />
                </TextField>
                
                { emailErrors.length===0 && usernameErrors.length===0 && passwordErrors.length===0 ? (
                    <Button type="submit">Valider</Button>
                  ):
                  ( <Button className='inValidButton'>Valider</Button>)
                }
        </Form>
            <Link to="/connecter" className='link-creer'>Se connecter</Link>
        </div>
    </div>
    );
};

export default CreateUser;
