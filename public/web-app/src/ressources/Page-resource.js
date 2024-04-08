import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';



const PageResource = () => {

  const [resourceId, setResourceId] = useState({});
  const {id} = useParams()
  const [isLoading, setIsLoading] = useState(false);


  
  useEffect(() => {
     axios.get(`http://localhost/api/resources/${id}`)
      .then(response => {
            setResourceId(response.data);
            console.log(response.data);
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
          <h2>Resource ID: {id}</h2>
            {/* affiche des donnes de service-allusers*/}
            <h3>id-user: {resourceId.id}</h3>
            <h3>username: { resourceId.userData.user.username}</h3>
            <h3>title: { resourceId.resourceMetadata.title}</h3>
            <h3>author: { resourceId.resourceMetadata.author}</h3>
            <h3>role: { resourceId.resourceRelationTypes.map((res)=>res.type).join(', ')}</h3>
            <img
                  className="article-img"
                  src="/imgs/pdfLogo.jpeg"
                  alt="dicaprio1"
                />
          </div>
        </div>
      </div>
     )
    }

};

export default PageResource;
