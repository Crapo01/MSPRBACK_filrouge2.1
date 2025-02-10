# App1: DATABASE ACCESS LIBRARY

## set up

in xampp htdocs create folder App1

see how to make virtualhost in welcome page

http://localhost/dashboard/docs/configure-vhosts.html

## init git repository

add .gitignore

    vendor
    .env

## composer

    composer init

add/update autoload script in composer.json

    "autoload": {
    "psr-4": {
      "Lucpa\\Model\\": "src/Model/",
      "Lucpa\\Service\\": "src/Service/",
      "Lucpa\\Repository\\": "src/Repository/"
    }

this is to match this structure:

    scr/  
        model/
        repository/
        service/
    .env
    index.php

## mongoDb

    composer require mongodb/mongodb

## dotEnv

    composer require vlucas/phpdotenv

remember to load env variables:

    require_once 'vendor/autoload.php';
    Dotenv\Dotenv::createImmutable(__DIR__)->load();






