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
            <div className="title-article">The Great Gatsby</div>
              <div>
                <img
                  className="article-img"
                  src="/imgs/dicaprio1.jpeg"
                  alt="dicaprio1"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Nombre de vues</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./eye-plus.svg"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">17</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Type relation:</div>
              <div className="relation">famille, professionnel</div>
            </div>

            <div className="resource-category-block">
              <div>Catégorie:</div>
              <div className="resource-category">
                <div>loisirs</div>
              </div>
            </div>

            <Button className="button-right">Voir</Button>
          </article>

          <article className="video">
            <Link to={`/ressource/1`} className="article-link">
            <div className="title-article">The Great Gatsby</div>
              <div>
                <img
                  className="article-img"
                  src="/imgs/dicaprio1.jpeg"
                  alt="dicaprio1"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Nombre de vues</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./eye-plus.svg"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">17</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Type relation:</div>
              <div className="relation">famille</div>
            </div>

            <div className="resource-category-block">
              <div>Catégorie:</div>
              <div className="resource-category">
                <div>loisirs</div>
              </div>
            </div>

            <Button className="button-right">Voir</Button>
          </article>

          <article className="video">
            <Link to={`/ressource/1`} className="article-link">
            <div className="title-article">The Great Gatsby</div>
              <div>
                <img
                  className="article-img"
                  src="/imgs/dicaprio1.jpeg"
                  alt="dicaprio1"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Nombre de vues</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./eye-plus.svg"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">17</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Type relation:</div>
              <div className="relation">famille, professionnel</div>
            </div>

            <div className="resource-category-block">
              <div>Catégorie:</div>
              <div className="resource-category">
                <div>loisirs</div>
              </div>
            </div>

            <Button className="button-right">Voir</Button>
          </article>

          <article className="video">
            <Link to={`/ressource/1`} className="article-link">
            <div className="title-article">The Great Gatsby</div>
              <div>
                <img
                  className="article-img"
                  src="/imgs/dicaprio1.jpeg"
                  alt="dicaprio1"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Nombre de vues</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./eye-plus.svg"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">17</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Type relation:</div>
              <div className="relation">famille, professionnel</div>
            </div>

            <div className="resource-category-block">
              <div>Catégorie:</div>
              <div className="resource-category">
                <div>loisirs</div>
              </div>
            </div>

            <Button className="button-right">Voir</Button>
          </article>

        </section>
      </main>
    </div>
  );
}

export default HomePage;
