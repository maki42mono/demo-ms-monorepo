#!/bin/sh
cd $1
git checkout staging
git fetch origin
git reset --hard origin/staging
composer install