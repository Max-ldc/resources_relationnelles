import React, { useState, useEffect } from "react";
import axios from "axios";
import { Link } from "react-router-dom";
// import AllUsers from '../user/allUsers/AllUsers';
import {
  Button,
  Cell,
  Column,
  Row,
  Table,
  TableBody,
  TableHeader,
} from "react-aria-components";
import "./service.css";
import MyCheckbox from "./MyCheckbox";
// https://levelup.gitconnected.com/how-to-make-an-api-call-with-all-crud-operations-in-react-ed6e6b94c363

const Service = () => {
  const [users, setAllUsers] = useState([]);
  // const [userId, setUserId] = useState({});
  const [loading, setLoading] = useState(true);

  //get list users
  useEffect(() => {
    axios
      .get("http://localhost/api/users")
      .then((response) => {
        setAllUsers(response.data);
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  }, []);


  // return (
  //   <div>
  //       <h1>List Users</h1>
  //       { loading ? ( <p>Loading...</p>) : (
  //         <div>
  //         {users['hydra:member']?.map(user => (
  //           <Link key={user.id} to={`/user/${user.id}`}>
  //             <AllUsers key={user.id}
  //               id={user.id}
  //               username={user.username}
  //               accountEnabled={user.accountEnabled}
  //               role={user.role}
  //             />
  //           </Link>
  //         ))}
  //        </div>
  //         )}
  //   </div>
  // );

  //Test
  return (
    <div className="container_listuser">
      <h1>List Users</h1>
      {loading ? (
        <p>Loading...</p>
      ) : (
        <Table aria-label="Files" selectionMode="multiple">
          <TableHeader>
            <Column>
              <MyCheckbox slot="selection"  />
            </Column>
            <Column isRowHeader>ID</Column>
            <Column>Username</Column>
            <Column>Role</Column>
            <Column>Account</Column>
            <Column className='btn_cell'></Column>
            <Column className='btn_cell'></Column>
            <Column className='btn_cell'></Column>
          </TableHeader>

          <TableBody>
            {users["hydra:member"]?.map((user) => (
              <Row key={user.id}>
                <Cell>
                  <MyCheckbox slot="selection" />
                </Cell>
                <Cell>{user.id}</Cell>
                <Cell>{user.username}</Cell>
                <Cell>{user.role}</Cell>
                <Cell>{user.accountEnabled}</Cell>

              
                    <Cell className='btn_cell'>
                      <Link key={user.id} to={`/user/${user.id}`}>
                        <Button className="btn_modif">moddifier</Button>
                      </Link>
                    </Cell>
                    <Cell className='btn_cell'>
                      <Link key={user.id} to={`/user/${user.id}`}>
                        <Button className="btn_supprimer">supprimer</Button>
                      </Link>
                    </Cell>
                    <Cell className='btn_cell'>
                      <Link key={user.id} to={`/user/${user.id}`}>
                        <Button>regarder</Button>
                      </Link>
                    </Cell>
                    

              </Row>
            ))}
          </TableBody>
        </Table>
      )}
    </div>
  );
};

export default Service;
