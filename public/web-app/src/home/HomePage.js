import { Link } from "react-router-dom";
import "./homepage.css";
import Search from "../search/Search";
import { Button } from "react-aria-components";
import Resources from "../ressources/Resources";

function HomePage() {
  return (
    <div>
      <main>
        {/* Search */}
        <section className="section-search">
          <Search />
        </section>

        {/* buttons */}
        <section className="buttons-block">
          <Button className="button-orange">
            <Link to="/connecter">Se connecter</Link>
          </Button>
          <Button className="button-orange">
            <Link to="/creer-compte">Créer un compte</Link>
          </Button>
        </section>

        <div className="ligne-block">
          <span className="text-linge">Ressources publiques</span>
        </div>

        {/* resource */}
        <Resources></Resources>

      </main>
    </div>
  );
}

export default HomePage;
