<?php

require_once('../init.php');
require_once('admin_functions.php');

// si on n’est pas authentifié, on dégage.
if(!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: ../connexion.php');
    die();
}
