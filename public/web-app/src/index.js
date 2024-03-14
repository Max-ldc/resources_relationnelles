import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import App from './App';
import reportWebVitals from './reportWebVitals';
import Navbar from './Navbar'
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom'
import Service from './service/Service';
import Ressource from './ressources/Ressource';
import User from './user/User';
import HomePage2 from './home/HomePages2';
import Account from './account/Account';


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <React.StrictMode>
    <Router>
    <Navbar />
      <Routes>
        <Route path="/" element={<App />} />
        <Route path="/home2" element={<HomePage2 />} />

        <Route path="/service" element={<Service />} />
        {/* <Route path="/createUser" element={<CreateUser />} /> */}
        <Route path="/ressource/:ressourceId" element={<Ressource/>} />
        <Route path="/account/" element={<Account/>} />

        <Route path="/user/:id" element={<User/>} />

      </Routes>
      {/* <App />
      <Service/> */}
    </Router>
  </React.StrictMode>,
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();

