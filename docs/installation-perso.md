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
