import './App.css';
import HomePage from './home/HomePage'
// import logo from 'logo.svg';
import gouvfr from './gouvfr.png'


function App() {
  return (
    <div>



     <div><HomePage/></div>

    {/* <div><img src={logo} className="App-logo" alt="logo" /></div> */}


      <footer>
          <div className='footer-up'>
              <img src={gouvfr} className="footer-img" alt="logo" />
              <div className='footer-up-text'>
                  <span className='text-footer-up-text'>ressources.gouv.fr</span>
                  <span>(RE)Sources Relationnelles</span>
              </div>
          </div>

          <div className='footer-down'>
              <div>Accuile</div>
              <div>Ressources</div>
              <div>About...</div>
          </div>
     </footer>



    </div>
  );
}

export default App;
