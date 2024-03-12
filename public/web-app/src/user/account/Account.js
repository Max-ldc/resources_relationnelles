import Connecter from "./Connecter";
import CreateUser from "./CreateUser";
import './account-css.css'

const Account = () => {
    return (
      <div className="account-block">
        <section><Connecter/></section>
        <section><CreateUser/></section>
      </div>
    );
  };
  
  
export default Account;