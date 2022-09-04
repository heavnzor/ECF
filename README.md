# ECF 2022 - ECO IT / STUDI / WALDGANGER.NET

<hr />

<img src="https://waldganger.net/build/images/logo.png" alt="logo eco it" width="250px" height="auto" />

<hr />
 
 
 ## Table des matières
- [Notion](#notion)
- [Description](#description)
- [Vérification des conditions requises](#exigences)
- [Récupération du projet](#recuperation-du-projet)
- [Installation](#installation)
- [Installation des dépendances](#installation-des-dependances)
- [Création de la base de donnée](#creation-de-la-base-de-donnees)
- [Création des tables](#creation-des-tables)
- [Insertions des jeux de données](#insertion-des-jeux-de-donnees)
- [Installation du serveur](#utilisation)
- [Login](#tester-le-projet)

# Notion
Bien le bonjour mon brave. Le lien vers les quelques (grossières) étapes & réfléxions qui m'ont traversées l'esprit durant ce projet sont ici :
[Notion](https://gleaming-hellebore-10a.notion.site/1e29d5ecb67d4723b45294029b7c31c8?v=e0d2c04c24184feda16bb47c14bb54c8) 

# Description

Les documents annexes sont disponibles dans le dossier ANNEXES :

* Charte graphique
* Manuel d'utilisation
* Documentation technique
* Wireframes

 ***
# Exigences
* Téléchargez [Symfony CLI](https://symfony.com/download)

* Pour vérifier si vous réunissez toutes les conditions requises avant d'installer ce projet :

```
$ symfony check:requirements
```

* Mais surtout téléchargez [Composer](https://getcomposer.org/)

```
$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
$ php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
$ php composer-setup.php
$ php -r "unlink('composer-setup.php');"
```

# Recuperation du projet

```
$ git clone https://github.com/heavnzor/ECF
```

 ***

# Installation

* Déplacement dans le dossier

```
$ cd ECF
```

## Installation des dependances
```
$ composer install
```

## Creation de la base de donnees
```
$ symfony console doctrine:database:create
```

## Creation des tables
```
$ symfony console doctrine:migrations:migrate
OU
$ symfony console doctrine:schema:update --force
```

## Insertion des jeux de donnees
```
$ symfony console doctrine:fixtures:load 
```
***

# Utilisation 

Pour lancer le serveur :

```
symfony server:start
```

 ***
***

# Tester le projet 

Pour obtenir des liens de connexions :

```
contactez-moi par email : heavnzor@protonmail.com
```

 ***

 








