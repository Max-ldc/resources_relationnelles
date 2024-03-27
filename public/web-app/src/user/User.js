import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';



const User = () => {

  const [userId, setUserId] = useState({});
  const {id} = useParams()
  const [isLoading, setIsLoading] = useState(false);


  
  useEffect(() => {
     axios.get(`http://localhost/api/users/${id}`)
      .then(response => {
            setUserId(response.data);
            console.log(response.data.username);
            setIsLoading(true) // wait result
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
  }, [id]);


  if (isLoading) {
    return (
      <div className="block-utilisateur">
        <div className="utilisateur">
          <div>
          <h2>User ID: {id}</h2>
            <p>id-user: {userId.id}, name: {userId.username}</p>
          </div>
        </div>
      </div>
     )
    }

};

export default User;
