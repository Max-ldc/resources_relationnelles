import gouvfr from './gouvfr.png'
import './App.css';
import React from 'react';


function MyFooter() {
  return (
    <footer>
      <div className="footer-up">
        <img src={gouvfr} className="footer-img" alt="logo" />
        <div className="footer-up-text">
          <span className="text-footer-up-text">ressources.gouv.fr</span>
          <span>(RE)Sources Relationnelles</span>
        </div>
      </div>

      <div className="footer-down">
        <div>Accueil</div>
        <div>Aide</div>
        <div>Protection des données</div>
        <div>Conditions d'utilisation</div>
      </div>
    </footer>
  );
}
export default MyFooter;

