![logo](Eco-Web/public/Images/logo-ecoit-removebg.png)
# ECF2022 - Waldganger 
 ***

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

## Installation

```
# Déplacement dans le dossier
$ cd ECF

```
# Télécharger et installer composer
$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
$ php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

```
# Installer composer
$ php composer-setup.php
$ php -r "unlink('composer-setup.php');"

```
# Installation des dépendances
$ composer install

# Création de la base de données
$ php bin/console doctrine:database:create

# Création des tables (migrations)
$ php bin/console doctrine:migrations:migrate
OU
$ php bin/console doctrine:schema:update --force

# Insertions des jeux de données (fixtures)
$ php bin/console doctrine:fixtures:load 
```
 ***

## Login en fonction des rôles

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

## Utilisation 

Deux options pour lancer le serveur de développement PHP :

* Si _Symfony_ est installé
```
symfony server:start
```

* Sinon, il faut utiliser _Composer_, et installer le Web Server Bundle :
```
$ composer require webapp
$ php bin/console server:start
```








