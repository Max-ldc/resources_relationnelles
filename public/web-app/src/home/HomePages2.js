import { Link } from "react-router-dom";
import "./homepage.css";
import Search from "../search/Search";
import { Button } from "react-aria-components";

function HomePage2() {
  return (
    <div>
      <main>
        {/* Search */}
        <section className="section-search">
          <Search />
        </section>

        {/* buttons */}
        <section className="buttons-block">
          <Button>
            <Link to="/account">Se connecter</Link>
          </Button>
          <Button>
            <Link to="/account">Créer un compte</Link>
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
              <div>Tom Jarrie</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./like-svgrepo-com.svg"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">17</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Relation(accepté):</div>
              <div className="relation">
                <div className="like-number">amis,famille</div>
              </div>
            </div>

            <div className="resource-categoty-block">
              <div>Resource category:</div>
              <div className="resource-categoty">
                <div className="like-number">loisirs</div>
              </div>
            </div>

            <div className="resource-type-block">
              <div>Resource type:</div>
              <div className="resource-type">
                <div className="like-number">video</div>
              </div>
            </div>
          </article>

          <article className="video">
            <Link to={`/ressource/2`} className="article-link">
              <div className="title-article">Killers of the Flower Moon</div>
              <div>
                <img
                  className="article-img"
                  src="/imgs/dicaprio2.jpeg"
                  alt="dicaprio1"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Tom Jarrie</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./like-svgrepo-com.svg"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">2900</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Relation(accepté):</div>
              <div className="relation">
                <div className="like-number">amis,famille</div>
              </div>
            </div>

            <div className="resource-categoty-block">
              <div>Resource category:</div>
              <div className="resource-categoty">
                <div className="like-number">loisirs</div>
              </div>
            </div>

            <div className="resource-type-block">
              <div>Resource type:</div>
              <div className="resource-type">
                <div className="like-number">video</div>
              </div>
            </div>
          </article>

          <article className="video">
            <Link to={`/ressource/3`} className="article-link">
              <div className="title-article">
                The Men Who Built America: Frontiersmen
              </div>
              <div>
                <img
                  className="article-img"
                  src="/imgs/dicaprio3.jpeg"
                  alt="dicaprio1"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Auteur: Tom</div>
              <div className="like">
                <div className="like-number">17</div>
                <div>like icon</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Relation(accepté):</div>
              <div className="relation">
                <div className="like-number">amis,famille</div>
              </div>
            </div>

            <div className="resource-categoty-block">
              <div>Resource category:</div>
              <div className="resource-categoty">
                <div className="like-number">loisirs</div>
              </div>
            </div>

            <div className="resource-type-block">
              <div>Resource type:</div>
              <div className="resource-type">
                <div className="like-number">video</div>
              </div>
            </div>
          </article>

          <article className="video">
            <Link to={`/ressource/4`} className="article-link">
              <div className="title-article">Whose Vote Counts, Explained </div>
              <div>
                <img
                  className="article-img"
                  src="/imgs/dicaprio4.jpeg"
                  alt="dicaprio1"
                />
              </div>
            </Link>

            <div className="like-block">
              <div>Tom Jarrie</div>
              <div className="like">
                <div>
                  <img
                    className="likeDislike-img"
                    src="./like-svgrepo-com.svg"
                    alt="like"
                  ></img>
                </div>
                <div className="like-number">790000</div>
              </div>
            </div>

            <div className="relation-block">
              <div>Relation(accepté):</div>
              <div className="relation">
                <div className="like-number">amis,famille</div>
              </div>
            </div>

            <div className="resource-categoty-block">
              <div>Resource category:</div>
              <div className="resource-categoty">
                <div className="like-number">loisirs</div>
              </div>
            </div>

            <div className="resource-type-block">
              <div>Resource type:</div>
              <div className="resource-type">
                <div className="like-number">video</div>
              </div>
            </div>
          </article>
        </section>
      </main>
    </div>
  );
}

export default HomePage2;
