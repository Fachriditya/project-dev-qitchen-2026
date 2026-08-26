#!/bin/bash
# Wrapper to run phpunit with sqlite support on systems without php-sqlite installed
DIR="$(cd "$(dirname "$0")" && pwd)"
php -d extension="$DIR/tests/support/pdo_sqlite.so" -d extension="$DIR/tests/support/sqlite3.so" vendor/bin/phpunit "$@"
