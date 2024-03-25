import React, { useState } from 'react';
import axios from 'axios';
import {Button, FieldError, Form, Input, Label, TextField} from 'react-aria-components';
import './account-css.css';
import { Link } from "react-router-dom";

const CreateUser = () => {
    const [username, setUsername] = useState('');
    const [email, setUseremail] = useState('');

    // const [body, setBody] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();

        const newPost = { username, email };
        const customConfig = {  headers: { 'Content-Type': 'application/ld+json' }};

        axios.post('http://localhost/api/users', newPost, customConfig)
            .then(response => {
                console.log(`New Post Created with ID: ${response.data.id}`);
            })
            .catch(error => {
                console.error('Error creating a post:', error);
            })
        };

    

    return (
        <div className='connecter-container'>
            <div className='connecter'>
                <div><img src="logoRSR2.png" className="logo-for-account"alt="logoRSR" /></div>

                <h2>Créer un compte</h2>
                <div className='text-accedez'>Get started with an account</div>
                <div className='text-star'>* champ obligatoire</div>


                <Form className='connecter-block'>
                <TextField name="username" type="text" isRequired>
                    <Label>Nom d’utilisateur*</Label>
                    <Input value={username}  onChange={(e) => setUsername(e.target.value)}/>
                    <FieldError />
                </TextField>
                <TextField name="email" type="email" isRequired>
                    <Label>Email*</Label>
                    <Input onChange={(e) => setUseremail(e.target.value)} />
                    <FieldError />
                </TextField>

                <TextField name="password" type="password" isRequired>
                    <Label>Mot de passe*</Label>
                    <Input />
                    <FieldError />
                </TextField>

                <TextField name="password" type="password" isRequired>
                    <Label>Confirmez le mot de passe*</Label>
                    <Input />
                    <FieldError />
                </TextField>
                
                <Button type="submit" onSubmit={handleSubmit} >Valider</Button>
            </Form>

            <Link to="/connecter" className='link-creer'>Se connecter</Link>

        </div>
    </div>

        // <div className='createuser'>
        //     <h2>Create User</h2>
        //         <Form  onSubmit={handleSubmit} className='connecter-block'>
        //             <TextField name="username" type="text" isRequired>
        //                 <Label>username</Label>
        //                 <Input onChange={(e) => setUsername(e.target.value)}/>
        //                 <FieldError />
        //             </TextField>
        //             <TextField name="email" type="email" isRequired>
        //                 <Label>email</Label>
        //                 <Input onChange={(e) => setUseremail(e.target.value)} />
        //                 <FieldError />
        //             </TextField>
                    
        //             <Button type="submit" >Submit</Button>
        //         </Form>
        // </div>


        // <form onSubmit={handleSubmit} className='creat-user-block'>
        //     <input type="text" value={username} onChange={(e) => setUsername(e.target.value)} placeholder="user name" />
        //     <input type="text" value={email} onChange={(e) => setUseremail(e.target.value)} placeholder="email" />
        //     {/* <textarea value={body} onChange={(e) => setBody(e.target.value)} placeholder="Body"></textarea> */}
        //     <button type="submit">Create User</button>
        // </form>
    );
};

export default CreateUser;