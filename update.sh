#!/usr/bin/env bash
git pull

php ./public/index.php compile-mo

FILE=./composer.phar
COMPOSER="sudo composer"
if [ -f $FILE ];
then
    COMPOSER="php $FILE"
fi
$COMPOSER self-update
$COMPOSER update --no-dev