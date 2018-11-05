## About Project

Please, before deploy download browscap.ini and uncomment 'browscap = path_to_browscap.ini' in your php.ini file.

Deploing:

* composer install
* create .env file
* php artisan key:generate
* configure database in your .env
* php artisan migrate
* php artisan serve
