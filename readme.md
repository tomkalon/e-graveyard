# E-GRAVEYARD

## Table of contents
* [General info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)

## General info
A simple application for cataloging and searching for graves in cemeteries made for parishes.

## Technologies
Project is created with:
* PHP 8.1
* Symfony 6.3
* MySQL / MariaDB
* Bootstrap 5.x
* SASS

## Setup
To run this project, follow the commands below:

1) Create and complete the .env file in root folder

Complete:
* APP_SECRET
* APP_ENV
* APP_TIMEZONE=Europe/Warsaw 
* DATABASE_URL
* MAILER_DSN

```
# In all environments, the following files are loaded if they exist,
# the latter taking precedence over the former:
#
#  * .env                contains default values for the environment variables needed by the app
#  * .env.local          uncommitted file with local overrides
#  * .env.$APP_ENV       committed environment-specific defaults
#  * .env.$APP_ENV.local uncommitted environment-specific overrides
#
# Real environment variables win over .env files.
#
# DO NOT DEFINE PRODUCTION SECRETS IN THIS FILE NOR IN ANY OTHER COMMITTED FILES.
# https://symfony.com/doc/current/configuration/secrets.html
#
# Run "composer dump-env prod" to compile .env files for production use (requires symfony/flex >=1.2).
# https://symfony.com/doc/current/best_practices.html#use-environment-variables-for-infrastructure-configuration

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=***
APP_TIMEZONE=Europe/Warsaw
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
# Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
#
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=15&charset=utf8"
DATABASE_URL=***
###< doctrine/doctrine-bundle ###

###> symfony/mailer ###
 MAILER_DSN=***
###< symfony/mailer ###

```
2) Run following commands:
```
$ composer update
$ npm update
```


3) To complete Database tables run:
```
php bin/console doctrine:migrations:migrate
```

4) To create new account run:
```
php bin/console newuser
```
...and add admin privileges by:
```
php bin/console admin
```

5) Adding new users and changing password are available only by ADMIN in ADMIN_PANEL (create new user) and:
```
php bin/console changepassword
```