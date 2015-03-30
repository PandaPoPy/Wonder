<?php

require_once('config.php'); // déclare un tableau $config
require_once('functions.php'); // déclare les différentes fonctions utiles

session_start();

try {
    $dsn = 'mysql:dbname='.$config['dbname'].';host='.$config['dbhost'].';charset=utf8';
    $db = new PDO($dsn, $config['user'], $config['password']);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erreur de connexion : '.$e->getMessage());
}
