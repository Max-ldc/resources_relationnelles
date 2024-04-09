import { Link } from "react-router-dom";
import "./homepage.css";
import Search from "../search/Search";
import { Button } from "react-aria-components";

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
        <section className="main-contanair">
          {/* <article className='video'>
                                  <Link to={`/articles/${articleId}`}>Видео id={articleId}</Link>
                              </article> */}
          {/* https://www.svgrepo.com/svg/474891/like    icons bibliotheque*/}

          <article className="video">
            <Link to={`/ressource/1`} className="article-link">
            <div className="title-article">Discours sur la servitude volontaire</div>
              <div>
                <img
                  className="article-img"
                  src="/imgs/lesson.png"
                  alt="article-a-lire"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Nombre de vues :</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./eye.png"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">17</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Type de relation :</div>
              <div className="relation">Soi, Collègues</div>
            </div>

            <div className="resource-category-block">
              <div>Catégorie :</div>
              <div className="resource-category">
                <div>Recherche de sens</div>
              </div>
            </div>

            <Link to={`/ressource/1`} className="button-right">Voir</Link>
          </article>

          <article className="video">
            <Link to={`/ressource/2`} className="article-link">
            <div className="title-article">Manuel d'Epictète</div>
              <div>
                <img
                    className="article-img"
                    src="/imgs/book.png"
                    alt="article-a-lire"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Nombre de vues :</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./eye.png"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">29</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Type de relation :</div>
              <div className="relation">Soi</div>
            </div>

            <div className="resource-category-block">
              <div>Catégorie :</div>
              <div className="resource-category">
                <div>Développement personnel</div>
              </div>
            </div>

            <Link to={`/ressource/2`} className="button-right">Voir</Link>
          </article>

          <article className="video">
            <Link to={`/ressource/3`} className="article-link">
            <div className="title-article">Le Loup Des Steppes</div>
              <div>
                <img
                    className="article-img"
                    src="/imgs/book.png"
                    alt="article-a-lire"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Nombre de vues :</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./eye.png"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">45</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Type de relation :</div>
              <div className="relation">Soi, Amis et Communautés</div>
            </div>

            <div className="resource-category-block">
              <div>Catégorie :</div>
              <div className="resource-category">
                <div>Développement personnel</div>
              </div>
            </div>

            <Link to={`/ressource/3`} className="button-right">Voir</Link>
          </article>

        </section>
      </main>
    </div>
  );
}

export default HomePage;
