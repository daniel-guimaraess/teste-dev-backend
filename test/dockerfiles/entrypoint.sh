#!/bin/bash

#Instalando dependências do Laravel
composer install --no-dev --optimize-autoloader

#Corrigindo permissões
chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

#Esperando banco de dados
apt-get update && apt-get install -y curl
curl -o /usr/local/bin/wait-for-it https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh
chmod +x /usr/local/bin/wait-for-it
wait-for-it database-neoestech:3306 --timeout=10 --strict -- echo "Banco de dados pronto!"

#Executando comandos Laravel
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force
php artisan import-data-csv

exec php-fpm &

exec supervisord -n
supervisorctl reread
supervisorctl update
supervisorctl start supervisor-queue