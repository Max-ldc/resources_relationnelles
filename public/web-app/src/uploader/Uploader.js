import React, { useState } from 'react';
import axios from 'axios';
import styles from './Uploader.module.css';
import { Button } from 'react-aria-components';

const Uploader = () => {
  const [file, setFile] = useState(null);
  const [sharedStatus, setSharedStatus] = useState('');
  const [category, setCategory] = useState('');
  const [type, setType] = useState('');
  const [title, setTitle] = useState('');
  const [author, setAuthor] = useState('');
  const [uploadedFileURL, setUploadedFileURL] = useState(null);
  const [relationTypes, setRelationTypes] = useState([]);

  const sharedStatusOptions = ['public', 'shared', 'private'];
  const categoryOptions = [
    'communication', 'culture', 'developpement_personnel', 'intelligence_emotionnelle',
    'loisirs', 'monde_professionnel', 'parentalite', 'qualite_de_vie',
    'recherche_de_sens', 'sante_physique', 'sante_psychique', 'spiritualite',
    'vie_affective'
  ];
  const typeOptions = [
    'article', 'carte_defi', 'cours_pdf', 'excercice',
    'fiche_lecture', 'video', 'audio', 'game'
  ];

  const relationTypeOptions = [
    {id: 1, text: 'Soi'},
    {id: 2, text: 'Conjoints'},
    {id: 3, text: 'Famille'},
    {id: 4, text: 'Professionnel'},
    {id: 5, text: 'Amis et communautés'},
    {id: 6, text: 'Inconnus'},
    {id: 7, text: 'Enfants'},
    {id: 8, text: 'Parents'},
    {id: 9, text: 'Fratrie'},
    {id: 10, text: 'Collègues'},
    {id: 11, text: 'Collaborateurs'},
    {id: 12, text: 'Managers'},
  ];

  function handleChange(event) {
    setFile(event.target.files[0]);
  }

  function handleRelationTypeChange(event) {
    const value = event.target.value;
    setRelationTypes(prev => {
      if (prev.includes(value)) {
        return prev.filter(item => item !== value);
      } else {
        return [...prev, value];
      }
    });
  }

  function handleSubmit(event) {
    event.preventDefault();
    const url = 'http://localhost/api/resources';
    const formData = new FormData();
    formData.append('importFile', file);
    formData.append('sharedStatus', sharedStatus);
    formData.append('category', category);
    formData.append('type', type);
    formData.append('title', title);
    formData.append('author', author);
    formData.append('relationTypes', JSON.stringify(relationTypes));

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
      <div className={styles.uploaderContainer}>
        <form onSubmit={handleSubmit}>
          <h1 className={styles.formTitle}>Charger une ressource</h1>
          <input
              type="file"
              onChange={handleChange}
              className={styles.fileInput}
          />
          <select
              value={sharedStatus}
              onChange={(e) => setSharedStatus(e.target.value)}
              className={styles.selectInput}
          >
            <option value="">Statut de partage</option>
            {sharedStatusOptions.map((option) => (
                <option key={option} value={option}>{option}</option>
            ))}
          </select>
          <select
              value={category}
              onChange={(e) => setCategory(e.target.value)}
              className={styles.selectInput}
          >
            <option value="">Catégorie</option>
            {categoryOptions.map((option) => (
                <option key={option} value={option}>{option}</option>
            ))}
          </select>
          <select
              value={type}
              onChange={(e) => setType(e.target.value)}
              className={styles.selectInput}
          >
            <option value="">Type</option>
            {typeOptions.map((option) => (
                <option key={option} value={option}>{option}</option>
            ))}
          </select>
          <input
              placeholder="Titre"
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              className={styles.textInput}
          />
          <input
              placeholder="Auteur"
              value={author}
              onChange={(e) => setAuthor(e.target.value)}
              className={styles.textInput}
          />

          <h2 className={styles.formSubtitle}>Types de relation</h2>
          <div className={styles.relationTypeSelection}>
            {relationTypeOptions.map((option) => (
                <label key={option.id} className={styles.checkboxContainer}>
                  {option.text}
                  <input
                      type="checkbox"
                      value={`/api/relation_types/${option.id}`}
                      onChange={handleRelationTypeChange}
                      checked={relationTypes.includes(`/api/relation_types/${option.id}`)}
                  />
                  <span className={styles.checkmark}></span>
                </label>
            ))}
          </div>

          <Button type="submit" className={styles.submitButton}>Valider l'envoi</Button>
        </form>

        {uploadedFileURL && (
            <div className={styles.uploadedFileContainer}>
              <img src={uploadedFileURL} alt="Uploaded content" className={styles.uploadedImage}/>
            </div>
        )}
      </div>
  );
}

  export default Uploader;
