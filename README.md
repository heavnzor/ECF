# ECF 2022 - ECO IT / STUDI DIGITAL CAMPUS / WALDGANGER.NET

<hr />

<img src="https://waldganger.net/build/images/logo.png" alt="logo eco it" width="250px" height="auto" />

<hr />
 
 
 ## Table des matières
- [Notion](#notion)
- [Description](#description)
- [Récupération du projet](#récuperation-du-projet)
- [Installation](#installation)
- [Télécharger composer](#télécharger-composer)
- [Installer composer](#installer-composer)
- [Installation des dépendances](#installation-des-dépendances)
- [Création de la base de donnée](#création-de-la-base-de-données)
- [Création des tables](#création-des-tables)
- [Insertions des jeux de données](#insertion-des-jeux-de-données)
- [Login](#login)
- [Installation du serveur](#utilisation)

## Notion
Bonjour à toi jeune padawan, le lien vers les quelques réfléxions qui m'ont traversées l'esprit durant ce projet sont ici :[Notion](https://gleaming-hellebore-10a.notion.site/1e29d5ecb67d4723b45294029b7c31c8?v=e0d2c04c24184feda16bb47c14bb54c8) 

## Description

Les documents annexes sont disponibles dans le dossier ANNEXES :

* Charte graphique
* Manuel d'utilisation
* Documentation technique
* Wireframes

 ***

## Récupération du projet

```
$ git clone https://github.com/heavnzor/ECF
```

 ***

# Installation

* Déplacement dans le dossier

```
$ cd ECF
```


## Télécharger composer
```
$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
$ php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
```

## Installer composer
```
$ php composer-setup.php
$ php -r "unlink('composer-setup.php');"
```


## Installation des dépendances
```
$ composer install
```

## Création de la base de données
```
$ php bin/console doctrine:database:create
```

## Création des tables
```
$ php bin/console doctrine:migrations:migrate
OU
$ php bin/console doctrine:schema:update --force
```

## Insertions des jeux de données
```
$ php bin/console doctrine:fixtures:load 
```

 ***

# Login

Après le chargement des fixtures, les identifiants pour tester le projet sont : 

* Administrateur 
```
Email =  webmaster@waldganger.net
Mdp = ecfjuin2022!
```
* Instructeur non validé
```
Email =  instructeur0@waldganger.net
Mdp = ecfjuin2022
```
* Instructeur validé
```
Email =  instructeur@waldganger.net
Mdp = ecfjuin2022
```
* Apprenant
```
Email =  user@waldganger.net
Mdp = ecfjuin2022
```

 ***

# Utilisation 

Deux options pour lancer le serveur de développement PHP :

* Si _Symfony_ est installé
```
symfony server:start
```

* Sinon, il faut utiliser _Composer_, et installer le bundle webapp :
```
$ composer require webapp
$ php bin/console server:start
```








