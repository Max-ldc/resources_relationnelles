import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import App from './App';
import reportWebVitals from './reportWebVitals';
import Navbar from './Navbar'
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom'
import Service from './service/Service';
import CreateUser from './service/CreateUser';
import Resource from './resource/Resource'



const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <React.StrictMode>
    <Router>
    <Navbar />
      <Routes>
        <Route path="/" element={<App />} />
        <Route path="/service" element={<Service />} />
        <Route path="/createUser" element={<CreateUser />} />
        <Route path="/resource/:resourceId" element={<Resource/>} />

       
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

