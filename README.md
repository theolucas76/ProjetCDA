# Heimdall Construction API
## Description
API pour le développement d'applications HeimdallConstruction


## Required
    composer: PHP >= 7.4
    localhost: heimdallapiv2
    BDD: CREATE DATABASE heimdall; MySQL
    Créer un fichier .env en reprennant le .env.example et compléter les variables d'env
    composer install
    Migrate: php artisan migrate --seed || php artisan migrate & php artisan migrate:refresh --seed
    JWT: php artisan jwt:secret
    Swagger: php artisan swagger-lume:generate

## Swagger
    URL: http://heimdallapiv2/api/documentation
