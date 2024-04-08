import React, { useState } from 'react';
import axios from 'axios';
import './uploader.css';
import { Button } from 'react-aria-components';

const Uploader = () => {

  const [file, setFile] = useState()
  const [uploadedFileURL, setUploadedFileURL] = useState(null)

  function handleChange(event) {
    setFile(event.target.files[0])
  }

  function handleSubmit(event) {
    event.preventDefault()
    const url = 'http://localhost:3000/uploadFile';
    const formData = new FormData();
    formData.append('file', file);
    formData.append('fileName', file.name);
    const config = {
      headers: {
        'content-type': 'multipart/form-data',
      },
    };
    axios.post(url, formData, config).then((response) => {
      setUploadedFileURL(response.data.fileUrl);
    });
  }

  return (
    <div className="uploader-container">
        <form onSubmit={handleSubmit}>
          <h1>Charger une ressource</h1>
            <input type="file" onChange={handleChange}/>
            <Button type="submit">Valider l'envoi</Button>
        </form>
        {uploadedFileURL && <img src={uploadedFileURL} alt="Uploaded content"/>}
    </div>
  );
}

export default Uploader;