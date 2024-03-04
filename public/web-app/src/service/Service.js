import React, { useState, useEffect } from 'react';
import axios from 'axios';
// https://levelup.gitconnected.com/how-to-make-an-api-call-with-all-crud-operations-in-react-ed6e6b94c363


const Service = () => {
  const [posts, setPosts] = useState([]);



  //get list users
  useEffect(() => {
      axios.get('http://localhost/api/users')
          .then(response => {
              setPosts(response.data);
              console.log(response.data)
          })
          .catch(error => {
              console.error('Error fetching data:', error);
          });
  }, []);

  return (
      <div>
          <h1>Posts</h1>
          {posts['hydra:member']?.map(user => (
            <li key={user.id}>
              <h2>{user.id} ------- {user.username}</h2>
            </li>
          ))}
        
      </div>
  );
}

export default Service;