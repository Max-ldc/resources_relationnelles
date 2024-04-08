import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import App from './App';
import reportWebVitals from './reportWebVitals';
import Navbar from './Navbar'
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom'
import Service from './service/Service';
import Resources from './ressources/Resources';
import User from './user/User';
import Connecter from './account/Connecter';
import CreateUser from './account/CreateUser';
import MyFooter from './MyFooter'
import Uploader from './uploader/Uploader';
import PageResource from './ressources/Page-resource';


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <React.StrictMode>
    <Router>
      <Navbar />
          <Routes>
            <Route path="/" element={<App />} />
            <Route path="/connecter" element={<Connecter />} />
            <Route path="/creer-compte" element={<CreateUser />} />

            <Route path="/service" element={<Service />} />
            <Route path="/resources" element={<Resources/>} />
            <Route path="/resource/:id" element={<PageResource/>} />

            {/* <Route path="/resource/:ressourceId" element={<Ressource/>} /> */}
            <Route path="/user/:id" element={<User/>} />
            <Route path="/uploader" element={<Uploader/>} />

          </Routes>
      <MyFooter/>
    </Router>
  </React.StrictMode>,
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();

