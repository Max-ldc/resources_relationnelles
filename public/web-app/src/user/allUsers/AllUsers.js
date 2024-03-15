// import { Link } from "react-router-dom";

const AllUsers = ({id, username, accountEnabled, role })  => {
  //  const[allLogement, setAllBd]=useState([]); //заводим переменные
    return (
      <div className="block-utilisateur">
        <div className="utilisateur">
          <div>


            {/* <Link to={`/user/${id}`}> */}
               <p>ID: {id}; Username: {username}; role:{role}</p>
            {/* </Link> */}
          </div>
        </div>
      </div>
    );
  };
  
  
export default AllUsers;