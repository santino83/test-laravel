#!/usr/bin/env bash

mkdir -p storage/app/public
mkdir -p storage/app/public/uploads
mkdir -p storage/logs

cd /var/www/html || exit

if [ -z "$APP_KEY" ]
then
  KEY=$(cat .env | grep "APP_KEY=" | head -1)
  if [ "$KEY" == "APP_KEY=" ]
  then
    php artisan key:generate
  fi
fi

# setup db
php artisan migrate --force -q

# seed the db if it's empty
if [ ! -e storage/app/public/seeds.lock ]
then
  php artisan db:seed --force -q > storage/logs/setup.log
  touch storage/app/public/seeds.lock
fi

chown www-data:www-data database/*.sqlite

chmod -R 0777 storage/app/public
chown -R www-data:www-data storage

apache2-foreground
