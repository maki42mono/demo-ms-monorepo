#!/bin/sh
cd $1
git checkout master
git fetch origin
git reset --hard origin/master
rm -r var/cache
composer install --no-dev --no-interaction