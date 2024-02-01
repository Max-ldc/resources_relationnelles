# Création des Fixtures avec Faker

## Fonctionnement des fixtures

Dans la méthode load() du fichier AppFixtures.php, on va instancier toutes les entités qu'on souhaite enregistrer en base de données. Par exemple :

```php
<?php
//...
public function load(ObjectManager $manager)
{
  $article = new Article();
  $article->setTitle('Mon premier article !')
    ->setDateCreated(new DateTime())
    ->setContent("Le contenu de l'article");

  $manager->persist($article);
  $manager->flush();
}
//...
```

| /!\  Bien penser à "persist" chaque entité à ajouter, puis à flush une seule fois en fin de méthode load()   |
|-----------------------------------------|

On peut ensuite envoyer ces données de tests en base grâce à : 

```
php bin/console doctrine:fixtures:load
# ou plus court
php bin/console d:f:l
```

| + d'infos :  [Documentation Fixtures Bundle](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html)   |
|-----------------------------------------|
    
## Création de données aléatoires avec Faker

Après l'ajout de la librairie faker aux dépendances, on utilise une instance de la classe Faker\Generator pour générer des noms, des pseudos, des nombres, des dates, etc... aléatoires.

Par exemple, pour générer 50 articles aléatoires :

```php
$faker = \Faker\Factory::create();

for ($i = 0; $i < 50; $i++) {
  $article = new Article();
  $article->setTitle($faker->realText(50))
    ->setDateCreated($faker->dateTimeBetween('-2 years'))
    ->setContent($faker->realTextBetween(250, 500));
  $manager->persist($article);
}

$manager->flush();
```

| + d'infos :  [Documentation complète sur Faker](https://fakerphp.github.io/)   |
|-----------------------------------------|