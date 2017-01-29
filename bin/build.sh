#!/bin/bash
#Author Jean Silva <me@jeancsil.com>

set -e
set -o pipefail

php bin/phpunit --coverage-html reports/coverage
php bin/phpcs -p --colors --standard=PSR2 src
php bin/phpmd src html cleancode,codesize,controversial,design,naming,unusedcode --reportfile reports/mess_detector.html
