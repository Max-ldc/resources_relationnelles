import React, { useState } from 'react';
import axios from 'axios';
import {Button, FieldError, Form, Input, Label, TextField} from 'react-aria-components';
import './account-css.css';
import { Link } from "react-router-dom";

const CreateUser = () => {
    const [username, setUsername] = useState('');
    const [email, setUserEmail] = useState('');
    const [errorEmail, setErrorEmail] = useState(false);
    // const [password, setPassword] = useState('');
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
                setMessageReussir('Vous avez creet votre user');
            })
            .catch(error => {
                console.log('-----'+ error.response.data.detail)
                setErrorEmail(error.response.data.detail);
            })
    };




        //validation form
        let usernameErrors = [];
        let emailErrors = [];
        // let passwordErrors = [];

        if (username.length < 3) {
          usernameErrors.push('Username moins de 3 caractères');
        }
        if (username.length > 16) {
          usernameErrors.push('Username plus de 3 caractères');
        }
        if ((username.match(/^[a-zA-Z0-9_]*$/) ?? []).length < 1) {
          usernameErrors.push('Username que lettres, chiffres et underscores');
        }

// 422 -  "message": "Le nom d'utilisateur ne peut pas contenir moins de 3 caractères.",
// 400 - Cet email est déjà utilisé

        if (email.length < 2) {
            emailErrors.push('Email min 3 characters');
        }
        if ((email.match(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i) ?? []).length < 1) {
            emailErrors.push('Le "@" et "." requise');
        }
      




    return (
        <div className='connecter-container'>
            <div className='connecter'>
                <div><img src="logoRSR2.png" className="logo-for-account"alt="logoRSR" /></div>

                <h2>Créer un compte</h2>
                <div className='text-accedez'>Get started with an account</div>
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

{/* 
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
                </TextField> */}
                
                <Button type="submit"  >Valider</Button>

        </Form>





            <Link to="/connecter" className='link-creer'>Se connecter</Link>

        </div>
    </div>


    );
};

export default CreateUser;