import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';
import AllUsers from '../user/allUsers/AllUsers';

// https://levelup.gitconnected.com/how-to-make-an-api-call-with-all-crud-operations-in-react-ed6e6b94c363


const Service = () => {
    const [users, setAllUsers] = useState([]);
    // const [userId, setUserId] = useState({});
    const [loading, setLoading] = useState(true);


  //get list users
  useEffect(() => {
      axios.get('http://localhost/api/users')
          .then(response => {
              setAllUsers(response.data);
              console.log(response.data)
              setLoading(false);
          })
          .catch(error => {
              console.error('Error fetching data:', error);
          });
    }, []);
  


  //get 1 user
  // const handleSearchById = (id) => {
  //   axios.get(`http://localhost/api/users/${id}`)
  //      .then(response => {
  //       setUserId(response.data);
  //       //setUserId(response.filter(user => user.id !== useId));
  //       console.log("111",id);
  //     })
  //     .catch(error => {
  //       console.error('Ошибка при получении пользователя:', error);
  //     });
  // };
  


  return (
    <div>
        <h1>List Users</h1>
        { loading ? ( <p>Loading...</p>) : (
          <div>
          {users['hydra:member']?.map(user => (
            <Link key={user.id} to={`/user/${user.id}`}>
              <AllUsers key={user.id}
                id={user.id}
                username={user.username}
                accountEnabled={user.accountEnabled}
              />
            </Link>
          ))}
         </div>
          )}
    </div>
  );

}

export default Service;      
