#!/usr/bin/env bash
MYSQL_VERSION=5.5
MYSQL_IMAGE="mysql:${MYSQL_VERSION}"
PHP_MY_ADMIN_IMAGE="phpmyadmin/phpmyadmin"
APACHE_PHP_IMAGE="apache-php-iota"

MYSQL_CONTAINER_NAME='mysql-db'
PHP_MY_ADMIN_CONTAINER_NAME='mysql-admin'
APACHE_PHP_CONTAINER_NAME='iota-web-server'

NETWORK_NAME='iota-dev-net'

PUBLIC_CONTENT="${PWD}/../.."

DB_INI_TEMPLATE="database.template.yaml"

APACHE_PHP_LOCAL_PORT=7000
PHP_MY_ADMIN_LOCAL_PORT=5000