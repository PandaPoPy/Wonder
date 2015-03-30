<?php
require_once('init.php');

$page_title = 'Suppression';
require_once('header.php');

if(isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $news = get_news($id);
    if($news) {
        $affichage_formulaire = true;

        // est-ce que le formulaire est renvoyé ?
        if(isset($_POST['confirmation'])) {
            // on insère
            $resultat = delete_news($id);
            if($resultat === true) {
                // succès ! \o/
                unlink('../img/'.$news['image']);
                echo '<p class="success">La news a été supprimée avec succès.</p><p><a href="index.php">Retour</a></p>'.PHP_EOL;
                $affichage_formulaire = false;
            } else {
                echo '<p class="error">Il y a eu une erreur dans la suppression : '.$resultat.', veuillez réessayer.</p>'.PHP_EOL;
            }
        }

        // on affiche le formulaire, sauf si $affichage_formulaire est à false
        if($affichage_formulaire) {
            ?>
            <form method="post">
                <p>Voulez-vous réellement supprimer la news « <?= $news['titre'] ?> » ?</p>
                <div><input type="submit" name="confirmation" value="Supprimer"/> <a href="index.php">Retour</a></div>
            </form>
            <?php
        }
    } else {
        echo '<p class="error">News introuvable. <a href="index.php">Retour</a></p>'.PHP_EOL;
    }
} else {
    echo '<p class="error">Veuillez choisir une news. <a href="index.php">Retour</a></p>'.PHP_EOL;
}

require_once('footer.php');
