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

# Installation des dépendances
composer install

# Création de la base de données
php bin/console doctrine:database:create

# Création des tables (migrations)
php bin/console doctrine:migrations:migrate
OU
php bin/console doctrine:schema:update --force

# Insertions des jeux de données (fixtures)
php bin/console doctrine:fixtures:load 
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

* Si vous avez installé _Symfony_
```
symfony server:start
```

* Si vous utilisez _Composer_, il faut installer le Web Server Bundle :
```
composer require symfony/web-server-bundle --dev
php bin/console server:start
```








