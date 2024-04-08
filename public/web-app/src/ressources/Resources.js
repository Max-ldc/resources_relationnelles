import { Link } from "react-router-dom";
import { Button } from "react-aria-components";
import "./resources.css";
import React, { useState, useEffect } from "react";
import axios from "axios";



const Resources = () => {

  const [resources, setAllResources] = useState([]);
  const [loading, setLoading] = useState(true);

 //get list users
 useEffect(() => {
  axios
    .get("http://localhost/api/resources")
    .then((response) => {
      setAllResources(response.data);
      setLoading(false);
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
}, []);



  return (
    <div className="container_resources">
      <main>
      {loading ? (
         <p className="loadingLogo">Loading...</p>
        ) : (
      <section className="main-contanair">
        { resources["hydra:member"]?.map((resources) => (


          <article className="video" key={resources.id}>
            <Link to={`/resource/${resources.id}`} className="article-link">
            <div className="title-article">{resources.resourceMetadata.title}</div>
              <div>
                <img
                  className="article-img"
                  src="/imgs/pdfLogo.jpeg"
                  alt="dicaprio1"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Nombre de vues</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./eye-plus.svg"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">17</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Type relation:</div>
              <div className="relation">
                { resources.resourceRelationTypes.map((res)=>res.type).join(', ')}
                </div>
            </div>

            <div className="resource-category-block">
              <div>Catégorie:</div>
              <div className="resource-category">
                 {resources.category}
              </div>
            </div>

            <Link to={`/resource/${resources.id}`} className="article-link">
                   <Button className="button-right">Voir</Button>
            </Link>
          </article>
                ))}
        </section>
      )}

      </main>
    </div>
  );
};

export default Resources;


 



  // return (
  //   <div className="container_listuser">
  //     <h1>List Users</h1>
  //     {loading ? (
  //       <p>Loading...</p>
  //     ) : (
  //       <Table aria-label="Files" selectionMode="multiple">
  //         <TableHeader>
  //           <Column>
  //             <MyCheckbox slot="selection"  />
  //           </Column>
  //           <Column isRowHeader>ID</Column>
  //           <Column>Username</Column>
  //           <Column>Role</Column>
  //           <Column>Account</Column>
  //           <Column className='btn_cell'></Column>
  //           <Column className='btn_cell'></Column>
  //           <Column className='btn_cell'></Column>
  //         </TableHeader>

  //         <TableBody>
  //           {users["hydra:member"]?.map((user) => (
  //             <Row key={user.id}>
  //               <Cell>
  //                 <MyCheckbox slot="selection" />
  //               </Cell>
  //               <Cell>{user.id}</Cell>
  //               <Cell>{user.username}</Cell>
  //               <Cell>{user.role}</Cell>
  //               <Cell>{user.accountEnabled}</Cell>

              
  //                   <Cell className='btn_cell'>
  //                     <Link key={user.id} to={`/user/${user.id}`}>
  //                       <Button className="btn_modif">moddifier</Button>
  //                     </Link>
  //                   </Cell>
  //                   <Cell className='btn_cell'>
  //                     <Link key={user.id} to={`/user/${user.id}`}>
  //                       <Button className="btn_supprimer">supprimer</Button>
  //                     </Link>
  //                   </Cell>
  //                   <Cell className='btn_cell'>
  //                     <Link key={user.id} to={`/user/${user.id}`}>
  //                       <Button>regarder</Button>
  //                     </Link>
  //                   </Cell>
                    

  //             </Row>
  //           ))}
  //         </TableBody>
  //       </Table>
  //     )}
  //   </div>
  // );

