import Connecter from "./Connecter";
import CreerCompte from "./CreerCompte";


const Account = () => {
    return (
      <div className="account-block">
        <section><Connecter/></section>
        <section><CreerCompte/></section>
      </div>
    );
  };
  
  
export default Account;