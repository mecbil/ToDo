ToDoList
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

## Badge

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/b2b2b34954834ea3b6f971d14ef80047)](https://www.codacy.com/gh/mecbil/ToDo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=mecbil/ToDo&amp;utm_campaign=Badge_Grade)

<a href="https://codeclimate.com/github/mecbil/ToDo/maintainability"><img src="https://api.codeclimate.com/v1/badges/37788c4d330facf99b36/maintainability" /></a>

## Installation
1. Clonez ou téléchargez le repository GitHub dans le dossier voulu :
```
    git clone https://github.com/mecbil/ToDo.git
```
2. Configurez vos variables d'environnement tel que la connexion à la base de données dans le fichier `.env.local` qui devra être crée à la racine du projet en réalisant une copie du fichier `.env` ainsi que la connexion à la base de données de test dans le fichier `env.test`.

3. Téléchargez et installez les dépendances du projet avec [Composer](https://getcomposer.org/download/) :
```
    composer install
```
4. Créez la base de données si elle n'existe pas déjà, taper la commande ci-dessous en vous plaçant dans le répertoire du projet :
```
    php bin/console doctrine:database:create
```
5. Créez les différentes tables de la base de données en appliquant les migrations :
```
    php bin/console doctrine:migrations:migrate
```
6. Installez les fixtures pour avoir une démo de données fictives en développement :
```
    php app/console doctrine:fixtures:load --env=dev --group=dev
```
7. Félicitations le projet est installé correctement, vous pouvez désormais commencer à l'utiliser à votre guise !

