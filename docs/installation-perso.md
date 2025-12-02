# Installation de la toolbar

    docker compose exec php composer require --dev symfony/profiler-pack

# Installation Vue UX

> [!NOTE]
> Bien suivre la documentation pour ne pas oublier une ligne de commande.

1. Twig bundle:

    docker compose exec php composer require symfony/twig-bundle

2. Webpack encore:

    docker compose exec php composer require symfony/webpack-encore-bundle
    > npm install

3. Ux-vue:

    docker compose exec php composer require symfony/ux-vue
    > npm install -D vue-loader --force
    > npm run watch

4. Tailwind:

    Suivre la documentation sur le site officiel Tailwind.

5. Maker bundle:

  > [!NOTE]
  > Pour faciliter la création de Controller par exemple.

    docker compose exec php composer require symfony/maker-bundle

6. Création d'un controller:

    docker compose exec php php bin/console make:controller BrandNewController

## Installation de la base de données

1. Doctrine:

    docker compose exec php composer require symfony/orm-pack

    doctrine/doctrine-bundle instructions:

      * Modify your DATABASE_URL config in .env

      > créer les variables ici pour ne rien toucher dans le fichier config.yaml

2. Creation database:

    docker compose exec php php bin/console doctrine:database:create

  > [!NOTE]
  > Il faudra sûrement Build et up le docker

2. Lier les explorer de Database (Postgres Explorer et TablePlus):

    > The hostname : correspont à DOMAIN (et pas NAME) sur les infos Orbstack de l'image database


## Création Entity et table dans la BDD

1. Entity:

    docker compose exec php php bin/console make:entity

2. Migration:

    docker compose exec php php bin/console make:migration

3. Migrate:

    docker compose exec php php bin/console doctrine:migrations:migrate

4. Suppression d'une entrée d'une table

    supprimer le champ (propriété + annotations / attributs + getters/setters ) dans l'entité
    puis migration et migrate

## Création de fixtures avec FakerPhp

1. Doctrine fixtures Bundle:

    docker compose exec php composer require --dev doctrine/doctrine-fixtures-bundle

2. Faker:

    docker compose exec php composer require fakerphp/faker --dev

3. Création des fixtures:

  > s'aider de perplexity

4. Loading Fixtures:

    docker compose exec php php bin/console doctrine:fixtures:load

## Création du système d'authentification

1. Security bundle:

    docker compose exec php composer require symfony/security-bundle

2. User

    docker compose exec php php bin/console make:user

3. Migration:

    docker compose exec php php bin/console make:migration

4. Migrate:

    docker compose exec php php bin/console doctrine:migrations:migrate

5. Register

    docker compose exec php composer require form validator

    docker compose exec php php bin/console make:registration-form

5. Login

    docker compose exec php php bin/console make:security:form-login
