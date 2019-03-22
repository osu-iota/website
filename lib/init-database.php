<?php

// Read database credentials and open a PDO connection
$dbCredentials = yaml_parse(file_get_contents($config['database']['configFile']));
$dbHost = $dbCredentials['host'];
$dbName = $dbCredentials['name'];
$dbUser = $dbCredentials['user'];
$dbPassword = $dbCredentials['password'];
$url = 'mysql:host=' . $dbHost . ';dbname=' . $dbName;
$db = new PDO($url, $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$daoUsers = new DAO\UserDao($db);