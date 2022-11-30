#Скрипт для разворачивания приложения локально
composer install --optimize-autoloader

php artisan storage:link

php artisan migrate --force
php artisan db:seed --class=\\Modules\\Users\\Database\\Seeders\\UsersDatabaseSeeder
php artisan l5-swagger:generate

php artisan route:cache
php artisan config:cache
php artisan view:cache
php artisan clear-compiled
php artisan optimize

#тестовые данные для анкеты
php artisan db:seed --class=\\Modules\\Surveys\\Database\\Seeders\\SurveysDatabaseSeeder
