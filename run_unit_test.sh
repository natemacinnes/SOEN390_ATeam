#!/bin/sh
php phpunit.phar -c tests/phpunit.xml --testdox-html tests/report.html --coverage-html tests/report
