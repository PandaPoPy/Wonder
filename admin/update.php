<?php
require_once('init.php');

function form_valid($titre, $texte, $extrait, $auteur, $image = null) {

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

    if($image !== null && is_uploaded_file($image['tmp_name'])) {
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

$page_title = 'Modification';
require_once('header.php');

if(isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $news = get_news($id);
    if($news) {
        // initialisation des variables qui pré-rempliront le formulaire
        $form_titre = $news['titre'];
        $form_texte = $news['texte'];
        $form_extrait = $news['extrait'];
        $form_auteur = $news['auteur'];
        $form_image_name = $news['image'];
        $affichage_formulaire = true;

        // est-ce que le formulaire est renvoyé ?
        if(isset($_POST['titre'], $_POST['texte'], $_POST['extrait'], $_POST['auteur'])) {
            $form_titre = htmlspecialchars($_POST['titre']);
            $form_texte = htmlspecialchars($_POST['texte']);
            $form_extrait = htmlspecialchars($_POST['extrait']);
            $form_auteur = htmlspecialchars($_POST['auteur']);
            $form_image = null;
            if(isset($_FILES['image'])) {
                $form_image = $_FILES['image'];
            }

            // on valide les données
            $form_valid = form_valid($form_titre, $form_texte, $form_extrait, $form_auteur, $form_image);
            if($form_valid === true) {
                $upload_ok = true;
                if($form_image !== null && is_uploaded_file($form_image['tmp_name'])) {
                    // on traite l’upload
                    $image_name = get_unique_filename($form_image['name'], '../img/');
                    if(move_uploaded_file($form_image['tmp_name'], '../img/'.$image_name)) {
                        create_thumbnail('../img/'.$image_name);
                        unlink('../img/'.$form_image_name);
                        $form_image_name = $image_name;
                    } else {
                        $upload_ok = false;
                    }
                }
                if($upload_ok) {
                    // on insère
                    $resultat = update_news($id, $form_titre, $form_texte, $form_extrait, $form_auteur, $form_image_name);
                    if($resultat === true) {
                        // succès ! \o/
                        echo '<p class="success">La news a été mise à jour avec succès.</p><p><a href="index.php">Retour</a></p>'.PHP_EOL;
                        $affichage_formulaire = false;
                    } else {
                        echo '<p class="error">Il y a eu une erreur dans la mise à jour : '.$resultat.', veuillez réessayer.</p>'.PHP_EOL;
                    }
                } else {
                    echo '<p class="error">Il y a eu une erreur dans l’envoi de l’image, veuillez réessayer.</p>'.PHP_EOL;
                }
            } else {
                echo '<p class="error">Le formulaire est invalide : '.$form_valid.'.</p>'.PHP_EOL;
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
                <div><input type="submit" value="Modifier"/> <a href="index.php">Retour</a></div>
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
