# Blog Actuli PHP

![forthebadge](https://forthebadge.com/images/badges/built-with-love.svg)
![forthebadge](https://forthebadge.com/images/badges/uses-html.svg)
![forthebadge](https://forthebadge.com/images/badges/uses-css.svg)
![forthebadge](https://forthebadge.com/images/badges/uses-js.svg)
![forthebadge](https://forthebadge.com/images/badges/uses-php.svg)


## Une petite description du projet

**Blog Actuli PHP** est une plateforme de blogging moderne, dédiée à partager des connaissances et des actualités sur les nouvelles technologies et les langages de programmation. Ce projet est construit en PHP avec Docker pour la gestion des données, offrant une expérience utilisateur fluide et interactive.

## Pour commencer

Pour utiliser ce projet, clonez le dépôt et suivez les instructions d'installation ci-dessous.

### Pré-requis

- PHP installé sur votre machine
- Composer installé sur votre machine
- Docker pour gérer les conteneurs de base de données
- Git pour cloner le dépôt

### Installation

Suivez ces étapes pour installer le projet :

1. Clonez le dépôt :
```bash
git clone https://github.com/LudwigELATRE/Actuli.git
```
2. Installez les dépendances du projet avec Composer. Dans le dossier racine du projet, exécutez :
```bash
   composer install
```
3. Dans le dossier racine, exécutez Docker :
   Cette commande lance le conteneur Docker pour la base de données.
```bash
   docker-compose up
```
4. Déplacez-vous dans le dossier `public` et lancez le serveur PHP :
```bash
   php -S localhost:8000 -d display_errors=1
``` 

### Démarrage

Pour démarrer le blog, ouvrez votre navigateur et accédez à `http://localhost:8000`. Suivez les instructions sur la page pour configurer votre compte administrateur.

## Fabriqué avec

- **PHP** - Langage de programmation principal
- **Docker** - Utilisé pour la gestion des conteneurs de base de données
- **Bootstrap** - Framework CSS pour le design frontend

## Contributing

Si vous souhaitez contribuer, veuillez consulter le fichier `CONTRIBUTING.md` pour connaître les procédures à suivre.

## Versions

- Dernière version stable : 1.0
- Dernière version : 1.1
- [Liste des versions](<url_de_votre_projet/tags>)

## Auteurs

- **Ludwig Elatre** - *Initial work* - [@ludwigelatre](<url_de_votre_profil>)

Voir aussi la liste des [contributeurs](<url_de_votre_projet/contributors>) qui ont participé à ce projet.

## Licence

Ce projet est sous licence libre - voir le fichier `LICENSE.md` pour plus de détails.
