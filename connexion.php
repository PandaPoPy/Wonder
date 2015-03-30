<?php
require_once('init.php');

if(isset($_GET['logout'])) {
    // déconnexion (suppression de l’authentification
    $_SESSION = [];
    // redirection vers la page d’accueil
    header('Location: index.php');
    die();
}

if(isset($_POST['password'])) {
    // le mot de passe est bon : on valide l’authentification, puis on redirige
    if(password_verify($_POST['password'], $config['admin_password'])) {
        $_SESSION['admin'] = true;
    } else { // le mot de passe est invalide : on en informe l’utilisateur
        $form_error = 'Le mot de passe est invalide';
    }
}

if(isset($_SESSION['admin']) && $_SESSION['admin']) {
    header('Location: admin/');
    die();
}

$page_title = 'Connexion';
require_once('header.php');

?>
<form method="post">
    <?php
    if(isset($form_error)) {
        echo '<p class="error">'.$form_error.'</p>'.PHP_EOL;
    }
    ?>
    <div>
        <label for="id_password">Mot de passe :</label> <input type="password" name="password" id="id_password" />
    </div>
    <div>
        <input type="submit" value="Connexion" />
    </div>
</form>
<?php

require_once('footer.php');
