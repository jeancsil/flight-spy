#!/bin/bash
#Author Jean Silva <me@jeancsil.com>

php bin/phpunit --coverage-html ../reports/coverage
php bin/phpcs
php bin/phpmd src html cleancode,codesize,controversial,design,naming,unusedcode --reportfile ../reports/mess_detector.html