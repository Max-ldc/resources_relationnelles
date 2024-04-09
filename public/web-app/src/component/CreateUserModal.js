import React, { useState } from "react";
import "./CreateUserModal.css";
import axios from 'axios';

function CreateUserModal({ isOpen, onClose, onSubmit, roles }) {
    const [username, setUsername] = useState('');
    const [email, setEmail] = useState('');
    const [role, setRole] = useState('');
    const [accountEnabled, setAccountEnabled] = useState(false);

    let usernameErrors = [];
    let emailErrors = [];

    if (username && username.length < 6) usernameErrors.push('Le nom d’utilisateur doit faire au moins 6 caractères');
    if (username && username.length > 16) usernameErrors.push('Le nom d’utilisateur dépasse la limite de 16 caractères');
    if ((username.match(/^[a-zA-Z0-9_]*$/) ?? []).length < 1) {
        usernameErrors.push('Le nom d utilisateur ne peut contenir que des chiffres, lettres et underscores');
    }

    if (email.length > 0 && email.length < 2) {
        emailErrors.push('Email moins de 3 caractères');
    }
    if (email.length > 0 && !email.match(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i)) {
        emailErrors.push('Veuillez saisir une adresse mail valide');
    }

    const resetForm = () => {
        setUsername('');
        setEmail('');
        setRole('');
        setAccountEnabled(false);
    };

    if (!isOpen) return null;

    const handleSubmit = (e) => {
        e.preventDefault();

        if (usernameErrors.length === 0 && emailErrors.length === 0) {
            const userData = {
                username: username,
                email: email,
                role: role,
                accountEnabled: accountEnabled
            };

            axios.post('http://localhost/api/users_privileged', userData, {
                headers: {
                    'Content-Type': 'application/ld+json'
                }
            })
                .then(response => {
                    console.log(response);
                    resetForm();
                    onClose();
                    onSubmit();
                })
                .catch(error => {
                    console.error("Il y a eu une erreur lors de la création de l'utilisateur", error);
                });
        }
    };

    const handleCancel = () => {
        resetForm();
        onClose();
    };

    return (
        <div className="modal-backdrop">
            <div className="modal">
                <form onSubmit={handleSubmit}>
                    <label>Nom d’utilisateur*</label>
                    <input type="text" value={username} onChange={(e) => setUsername(e.target.value)} className="input"/>
                    {usernameErrors.map((error, index) => <div key={index} className="field-error">{error}</div>)}

                    <label>Adresse email*</label>
                    <input type="email" value={email} onChange={(e) => setEmail(e.target.value)}  className="input"/>
                    {emailErrors.map((error, index) => <div key={index} className="field-error">{error}</div>)}

                    <label>Rôle</label>
                    <select value={role} onChange={(e) => setRole(e.target.value)} className="select">
                        {Object.entries(roles).map(([key, value]) => (
                            <option key={key} value={value}>{value}</option>
                        ))}
                    </select>

                    <label>
                        Compte activé?
                        <input type="checkbox" checked={accountEnabled} onChange={(e) => setAccountEnabled(e.target.checked)} />
                    </label>

                    <button className="btn" type="submit">Créer</button>
                    <button className="btn" type="button" onClick={handleCancel}>Annuler</button>
                </form>
            </div>
        </div>
    );
}

export default CreateUserModal;