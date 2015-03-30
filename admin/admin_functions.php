<?php

/**
 * exécute une requête SQL puis renvoie true ou un message d’erreur SQL
 * @param string $sql la requête à exécuter
 * @return bool|string le résultat de la requête, ou le message d’erreur
 */
function exec_or_error($sql) {
    global $db;
    $result = $db->exec($sql);

    if(!$result) {
        $erreur = $db->errorInfo();
        return $erreur[2];
    }
    return true;
}

/**
 * Enregistre une news en base
 * @param string $titre
 * @param string $texte
 * @param string extrait
 * @param string auteur
 * @param string image le nom de fichier enregistré
 * @return bool|string le résultat de l’insertion, ou le message d’erreur
 */
function create_news($titre, $texte, $extrait, $auteur, $image) {
    global $db;
    return exec_or_error('INSERT INTO news (titre, texte, extrait, auteur, image, date)
                          VALUES ('.$db->quote($titre).', '
                                   .$db->quote($texte).', '
                                   .$db->quote($extrait).', '
                                   .$db->quote($auteur).', '
                                   .$db->quote($image).', NOW())');
}

/**
 * Modifie une news en base
 * @param int $id
 * @param string $titre
 * @param string $texte
 * @param string extrait
 * @param string auteur
 * @param string image le nom de fichier enregistré
 * @return bool|string le résultat de l’insertion, ou le message d’erreur
 */
function update_news($id, $titre, $texte, $extrait, $auteur, $image) {
    global $db;
    return exec_or_error('UPDATE news SET titre = '.$db->quote($titre).',
                                          texte = '.$db->quote($texte).',
                                          extrait = '.$db->quote($extrait).',
                                          auteur = '.$db->quote($auteur).',
                                          image = '.$db->quote($image).'
                          WHERE id = '.$db->quote($id));
}

/**
 * Supprime une news en base
 * @param int $id
 * @return bool|string le résultat de la suppression, ou le message d’erreur
 */
function delete_news($id) {
    global $db;
    return exec_or_error('DELETE FROM news WHERE id = '.$db->quote($id));
}

/**
 * Renvoie un nom de fichier unique pour le dossier voulu, en renommant si 
 * besoin le nom passé en paramètre
 * @param string $filename
 * @param string $dir le dossier dans lequel on cherche à enregistrer le fichier
 * @return string un nom de fichier basé sur $filename mais qui n’existe pas 
 * encore dans le dossier (ou $filename s’il n’y a pas de doublon)
 */
function get_unique_filename($filename, $dir) {
    // si on nous fournit un $dir sans slash final, on le rajoute manuellement 
    // pour pouvoir concaténer proprement le chemin
    if(substr($dir, -1) != '/') {
        $dir .= '/';
    }
    // on extrait l’extension et le nom de base du fichier $filename, car dans 
    // la boucle qui suit, on se servira de ces 2 parties de fichier pour en 
    // calculer un nouveau, supposément unique
    $extension = strrchr($filename, '.');
    $base_filename = substr($filename, 0, -(strlen($extension)));

    // pour chaque passage dans la boucle while, on incrémentera le compteur 
    // dans le nom de fichier (exemple : image.jpg → image_2.jpg → image_3.jpg…)
    $counter = 1;

    // on cherche à vérifier l’unicité du fichier $filename ; donc, tant qu’un 
    // fichier de ce nom existe, on tente un nouveau nom.
    while(file_exists($dir.$filename)) {
        $counter++;
        // on construit un nouveau nom de fichier à partir de celui de base et 
        // du compteur, qu’on incrémente.
        $filename = $base_filename.'_'.$counter.$extension;
    }
    return $filename;
}

/**
 * Redimensionne l’image passée en paramètres en la réduisant (par cropping) aux 
 * dimensions indiquées dans $config
 * @param string $filename le chemin de l’image
 */
function create_thumbnail($filename) {
    global $config;
    $img = new Imagick($filename);
    $img->cropThumbnailImage($config['miniature_x'], $config['miniature_y']);
    $img->writeImage($filename);
}
