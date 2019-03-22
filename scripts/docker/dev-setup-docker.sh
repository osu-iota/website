#!/usr/bin/env bash
set -e

if [ -z "$1" ]; then
    echo
    echo "usage: sh dev-setup-docker.sh <local_private_basepath>"
    echo
    echo "<local_private_basepath> is the local directory of the private files (database.ini,"
    echo "data directory, logs, etc.) used for website configuration and data storage"
    echo
    exit 1
fi

DB_PASSWORD=""

while [ -z "${DB_PASSWORD}" ]; do
    echo -n 'Enter DB Password: '
    read -s DB_PASSWORD
    echo
done

echo -n 'Enter name for default database [iota]: '
read DB_NAME

if [ -z "${DB_NAME}" ]; then
    DB_NAME='iota'
fi

PRIVATE_CONTENT="$1"
DB_INI="${PRIVATE_CONTENT}/database.yaml"

# Get the defined variable names
. "./dev-vars-docker.sh"

echo
echo "Creating bridge network for development containers..."
docker network create --driver bridge ${NETWORK_NAME}

echo
echo "Starting MySQL server container..."
docker run -d --name ${MYSQL_CONTAINER_NAME} \
    --network ${NETWORK_NAME} \
    -e MYSQL_ROOT_PASSWORD="${DB_PASSWORD}" \
    -e MYSQL_DATABASE="${DB_NAME}" \
    -p 3306:3306 \
    ${MYSQL_IMAGE}

echo
echo "Starting phpMyAdmin container..."
docker run -d --name ${PHP_MY_ADMIN_CONTAINER_NAME} \
    --network ${NETWORK_NAME} \
    --link ${MYSQL_CONTAINER_NAME}:db \
    -p 5000:80 \
    ${PHP_MY_ADMIN_IMAGE}

echo
echo "Building and starting custom Apache PHP server for IOTA website..."
docker build . -t ${APACHE_PHP_IMAGE}

docker run -d --name ${APACHE_PHP_CONTAINER_NAME} \
    --network ${NETWORK_NAME} \
    -p ${APACHE_PHP_LOCAL_PORT}:80 \
    -v ${PUBLIC_CONTENT}:/var/www/html \
    -v ${PRIVATE_CONTENT}:/var/www \
    ${APACHE_PHP_IMAGE}

echo
echo "Generating database.ini..."
sed -e "s/DB_NAME/${DB_NAME}/; s/DB_HOST/${MYSQL_CONTAINER_NAME}/; s/DB_PASSWORD/${DB_PASSWORD}/" \
    ${DB_INI_TEMPLATE} \
    > ${DB_INI}

echo
echo "IoT Alliance website development environment setup complete."
echo "3 docker containers were started:"
echo "    - ${MYSQL_CONTAINER_NAME}"
echo "    - ${PHP_MY_ADMIN_CONTAINER_NAME}"
echo "    - ${APACHE_PHP_CONTAINER_NAME}"
echo
echo "All containers are part of the ${NETWORK_NAME} docker bridge network."
echo
echo "The script has generated the database configuration file in the website's private"
echo "directory at "
echo
echo "phpMyAdmin is available at http://localhost:${PHP_MY_ADMIN_LOCAL_PORT}"
echo
echo "The Apache PHP server serving content for the IOTA website is listening at"
echo "http://localhost:${APACHE_PHP_LOCAL_PORT}"
echo
