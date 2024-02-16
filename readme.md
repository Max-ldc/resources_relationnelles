# Installer le projet

### Prérequis
Windows : WSL + Docker Desktop \
Installer Make sur WSL et configurer le chemin du bin pour PHPStorm \
Installer Make sur WSL et configurer l'extension pour VSCode 

Mac et Linux : Docker Destop 

### Cloner le projet
Créer une clef SSH Git pour les postes Windows et la configurer (cf doc Git)

    SSH
    git clone git@github.com:Max-ldc/resources_relationnelles.git

    HTTPS
    git clone https://github.com/Max-ldc/resources_relationnelles.git

### Lancer le projet

Se placer à la racine du projet

    cd ~/Chemin/Vers/Racine/Projet/

Executer l'installation

    make install

# Mettre à jour son environnement

Il est généralement nécessaire de mettre à jour son environnement Docker pour prendre en compte les modifications sur :
- la structure de la base de données
- l'ajout ou la mise à jour de packages (dans le composer.json)

### Méthode bourrine quand on récupère une branche ou qu'on met à jour son main

Lancer "reset" depuis le Makefile (ou "make reset" via un terminal à partir de la racine du projet)

    ## Reset environment  
    reset: down install

La première partie de la commande "down", une fois décortiquée (cf Makefile) force l'arrêt de l'environnement Docker et supprime les conteneurs, volumes, etc ... (partie "down") :

    docker compose kill
    docker compose down --remove-orphans
    docker compose down -v

Ensuite la partie "install" va dans un premier temps construire (build) puis démarrer (up) les images Docker définies dans le fichier docker-compose.yaml. \
Ensuite, les dépendances du projet définies dans composer.json sont installées sur l'image php. \
Enfin, la base de données est initiée (db-reload) \

    docker compose build
    docker compose up -d
    docker compose exec -u www-data php composer install
    docker compose exec -u www-data php bin/console doctrine:database:drop --force --if-exists
    docker compose exec -u www-data php bin/console doctrine:database:create --if-not-exists
    docker compose exec -u www-data php sh -c 'PGPASSWORD=[password] psql ressources -h database -U [user] < dump/ressources.sql'


### MAJ de la structure de la BDD après avoir modifié les entités Doctrine
Après avoir modifié une entité (ajout d'un champ, ajout d'une table, modification d'une relation, etc), 
les changements doivent être "versionnés" et "migrés" avant d'être poussés sur Git avec le reste de la branche. 
Pour cela, il faut générer une fichier de "version" qui établira les différences entre la base actuelle et les changements proposés. 
Il s'agit de la commande "db-diff" dans le Makefile :

    ## Generate Doctrine Migration Diff  
    db-diff

Cette commande réalise l'action suivante :

    docker compose exec -u www-data php bin/console doctrine:migration:diff --no-interaction

Une fois le fichier de version généré, il est conseillé de le relire pour vérifier les changements attendus. Ces fichiers se trouve dans le dossier "migrations" (à la racine du projet). \
Afin d'appliquer les changements, il faut alors migrer la base en appliquant les différentes versions via la commande "db-migrate" du Makefile :


    ## Execute Doctrine Migration  
    db-migrate
Cette commande réalise l'action suivante :

    docker compose exec -u www-data php bin/console doctrine:migration:migrate --no-interaction

### Regénération des dumps après migration Doctrine
Il est obligatoire de régénérer un dump de la base de données et de l'ajouter à votre développement après une migration Doctrine. 
Principalement pour s'assurer que nous partageons tous la même base à jour (et autres avantages que je ne citerai pas ici). 
Il faut toujours le faire en environnement dev et de test. \
Les tests sont joués sur une base à part générée à chaque fois que les tests sont lancés afin d'assurer l'intégrité des données testées. \
Les deux commandes Makefile sont :

    ## Regenerate dump database  
    db-regenerate-dump
    
    ## Regenerate dump test database  
    db-regenerate-dump-test

On y retrouve les actions suivantes que ce soit en dev ou en test : drop de la base, création, migration, import des fixtures et génération du dump

    docker compose exec -u www-data php bin/console doctrine:database:drop --force --if-exists
    docker compose exec -u www-data php bin/console doctrine:database:create --if-not-exists
    docker compose exec -u www-data php bin/console doctrine:migration:diff --no-interaction
    docker compose exec -u www-data php bin/console doctrine:fixtures:load --no-interaction
    docker compose exec -u www-data php sh -c 'PGPASSWORD=[password] pg_dump ressources -h database -U [user]> dump/ressources.sql'


# Tests unitaires et tests d'intégration

Les tests unitaires sont conçus pour tester des parties isolées du code, généralement des fonctions ou des méthodes, pour s'assurer qu'elles fonctionnent correctement de manière isolée. Ils sont rapides à exécuter et aident à identifier les bugs au niveau le plus élémentaire du code.

Les tests d'intégration vérifient la façon dont différentes parties du système travaillent ensemble. Contrairement aux tests unitaires, les tests d'intégration se concentrent sur les points de connexion et les interfaces entre les composants pour s'assurer que l'ensemble du système fonctionne comme prévu.

Ces tests sont exécutés dans l'environnement de test de Symfony. On yutilise PHPUnit. La configuration se trouve dans le fichier `phpunit.xml.dist`. Ce fichier définit les paramètres de test, y compris les suites de tests à exécuter et les configurations d'environnement.

### Lancer les tests unitaires
Les tests unitaires se lancent via la commande "unit-test" du Makefile. Les tests joués se trouve dans le folder tests/unit

    ## Run phpunit tests  
    unit-test
Cette commande correspond à :

    docker compose exec -u www-data php bin/phpunit --testsuite Unit

### Lancer les tests d'intégration
Les tests d'intégration se lancent via la commande "api-test" du Makefile. Les tests joués se trouve dans le folder tests/integration

    ## Run API tests  
        api-test

Cette commande switche sur l'environnement de test, créé une base de données avec chargement des fixtures, exécute les tests puis switche à nouveau sur l'environnement de dev.

    ## Switch sur l'env de test     
        @echo "Switch to ${YELLOW}test${RESET}"  
        @-docker compose exec -u www-data php bash -c 'grep APP_ENV= .env.local 1>/dev/null 2>&1 || echo -e "\nAPP_ENV=test" >> .env.local'  
        @-docker compose exec -u www-data php sed -i 's/APP_ENV=.*/APP_ENV=test/g' .env.local
            
    ## Préparation de la base de test
        docker compose exec -u www-data php bin/console doctrine:database:drop --force --if-exists
        docker compose exec -u www-data php bin/console doctrine:database:create --if-not-exists
        docker compose exec -u www-data php sh -c 'PGPASSWORD=[password] psql ressources_test -h database -U [user] < dump/ressources_test.sql'
        
    ## Execution des tests
        docker compose exec -u www-data php php -dmemory_limit=512M bin/phpunit --testsuite Integration
        
    ## Retour sur l'env dev
        @echo "Switch to ${YELLOW}dev${RESET}"  
        @-docker compose exec -u www-data php bash -c 'grep APP_ENV= .env.local 1>/dev/null 2>&1 || echo -e "\nAPP_ENV=dev" >> .env.local'  
        @-docker compose exec -u www-data php sed -i 's/APP_ENV=.*/APP_ENV=dev/g' .env.local

# Accès à l'API
Pour l'instant l'URL est http://localhost/api