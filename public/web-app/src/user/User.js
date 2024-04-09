import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';
import './User.css';

const User = () => {
    const [user, setUser] = useState(null);
    const { id } = useParams();
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        setIsLoading(true);
        axios.get(`http://localhost/api/users/${id}`)
            .then(response => {
                setUser(response.data);
                setIsLoading(false);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                setIsLoading(false);
            });
    }, [id]);

    if (isLoading) {
        return <div className="loading">Chargement...</div>;
    }

    if (!user) {
        return <div className="not-found">Utilisateur non trouvé</div>;
    }

    // Conversion de accountEnabled pour affichage
    const accountStatus = user.accountEnabled ? 'Activé' : 'Désactivé';

    return (
        <div className="user-details">
            <h2>Détails de l'utilisateur</h2>
            <h3><strong>ID:</strong> {user.id}</h3>
            <h3><strong>Nom d'utilisateur:</strong> {user.username}</h3>
            <h3><strong>Rôle:</strong> {user.role}</h3>
            <h3><strong>État du compte:</strong> {user.accountEnabled ? 'Activé' : 'Désactivé'}</h3>
        </div>
    );
};

export default User;
