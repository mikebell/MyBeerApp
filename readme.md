# Requirements

* Docker
* Docker Compose
* Composer
* Yarn
* Yarn guff

# Install

    composer install
    docker-composer up
    cp .evn.example .env
    php artisan key:generate
    php artisan config:cache
    
# Database

    php artisan migrate:install
    php artisan migrate
    
# Logging

PHP error logs:

    docker logs -f mybeerapp_php_1 >/dev/null
