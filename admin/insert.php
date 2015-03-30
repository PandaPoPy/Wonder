<?php
require_once('init.php');

function form_valid($titre, $texte, $extrait, $auteur, $image) {

    $erreurs = [];

    if(empty($titre) || empty($texte) || empty($extrait) || empty($auteur)) {
        $erreurs[] = 'tous les champs sont requis';
    }

    if(mb_strlen($titre, 'utf8') > 100) {
        $erreurs[] = 'le titre ne peut pas faire plus de 100 caractères';
    }

    if(mb_strlen($extrait, 'utf8') > 150) {
        $erreurs[] = 'l’extrait ne peut pas faire plus de 150 caractères';
    }

    if(mb_strlen($auteur, 'utf8') > 100) {
        $erreurs[] = 'l’auteur ne peut pas faire plus de 100 caractères';
    }

    if(mb_strlen($texte, 'utf8') < 5 && !empty($texte)) {
        $erreurs[] = 'Le texte doit faire plus de 5 caractères';
    }

    if(!is_uploaded_file($image['tmp_name'])) {
        $erreurs[] = 'Le fichier n’a pas été envoyé';
    } else {
        // vérification du type
        $authorized_mimetypes = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/gif'];
        $authorized_extensions = ['.jpg', '.jpeg', '.png', '.svg', '.gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimetype = finfo_file($finfo, $image['tmp_name']);
        if(!in_array($mimetype, $authorized_mimetypes)) {
            $erreurs[] = 'Le format de fichier est incorrect (formats reconnus : '.implode(', ', $authorized_mimetypes).')';
        }
        $extension = strtolower(strrchr($image['name'], '.'));
        if(!in_array($extension, $authorized_extensions)) {
            $erreurs[] = 'L’extension du fichier est incorrecte (formats reconnus : '.implode(', ', $authorized_extensions).')';
        }
    }

    if(count($erreurs)) {
        return implode(', ', $erreurs);
    }

    return true;

}

$page_title = 'Index';
require_once('header.php');

// initialisation des variables qui pré-rempliront le formulaire
$form_titre = $form_texte = $form_extrait = $form_auteur = '';
$affichage_formulaire = true;

// est-ce que le formulaire est renvoyé ?
if(isset($_POST['titre'], $_POST['texte'], $_POST['extrait'], $_POST['auteur'])) {
    $form_titre = htmlspecialchars($_POST['titre']);
    $form_texte = htmlspecialchars($_POST['texte']);
    $form_extrait = htmlspecialchars($_POST['extrait']);
    $form_auteur = htmlspecialchars($_POST['auteur']);
    if(isset($_FILES['image'])) {
        $form_image = $_FILES['image'];

        // on valide les données
        $form_valid = form_valid($form_titre, $form_texte, $form_extrait, $form_auteur, $form_image);
        if($form_valid === true) {
            // on insère
            // gestion de l’upload
            $image_name = get_unique_filename($form_image['name'], '../img/');
            if(move_uploaded_file($form_image['tmp_name'], '../img/'.$image_name)) {
                create_thumbnail('../img/'.$image_name);
                $resultat = create_news($form_titre, $form_texte, $form_extrait, $form_auteur, $image_name);
                if($resultat === true) {
                    // succès ! \o/
                    echo '<p class="success">La news a été ajoutée avec succès.</p><p><a href="index.php">Retour</a></p>'.PHP_EOL;
                    $affichage_formulaire = false;
                } else {
                    echo '<p class="error">Il y a eu une erreur dans l’insertion : '.$resultat.', veuillez réessayer.</p>'.PHP_EOL;
                }
            } else {
                echo '<p class="error">Il y a eu une erreur lors de la copie de l’image, veuillez réessayer.</p>'.PHP_EOL;
            }
        } else {
            echo '<p class="error">Le formulaire est invalide : '.$form_valid.'.</p>'.PHP_EOL;
        }
    } else {
        echo '<p class="error">Veuillez sélectionner un fichier.</p>'.PHP_EOL;
    }
}

// on affiche le formulaire, sauf si $affichage_formulaire est à false
if($affichage_formulaire) {
    ?>
    <form method="post" enctype="multipart/form-data">
        <div><label for="id_titre">Titre :</label>
             <input type="text" name="titre" id="id_titre" value="<?= $form_titre ?>" />
        </div>
        <div><label for="id_auteur">Auteur :</label>
             <input type="text" name="auteur" id="id_auteur" value="<?= $form_auteur ?>" />
        </div>
        <div><label for="id_extrait">Extrait :</label>
             <input type="text" name="extrait" id="id_extrait" value="<?= $form_extrait ?>" />
        </div>
        <div><label for="id_texte">Texte :</label>
             <textarea name="texte" id="id_texte"><?= $form_texte ?></textarea>
        </div>
        <div><label for="id_image">Image :</label>
             <input type="file" name="image" id="id_image"/>
        </div>
        <div><input type="submit" value="Ajouter"/> <a href="index.php">Retour</a></div>
    </form>
    <?php
}

require_once('footer.php');
