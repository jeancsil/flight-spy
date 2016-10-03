#!/bin/bash

COMPOSER=`which composer`
MUST_INSTALL=$?

if [ "$MUST_INSTALL" != 0 ]; then
    EXPECTED_SIGNATURE=$(wget https://composer.github.io/installer.sig -O - -q)
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")

    if [ "$EXPECTED_SIGNATURE" = "$ACTUAL_SIGNATURE" ]
    then
        php composer-setup.php --install-dir=.
        rm composer-setup.php
        COMPOSER="/composer.phar";
    else
        >&2 echo 'ERROR: Invalid installer signature'
        rm composer-setup.php
        exit 1
    fi
fi

su -c "cd /flight-spy/ && $COMPOSER install --no-plugins --no-scripts --optimize-autoloader --no-suggest --no-interaction --no-dev --prefer-dist"
