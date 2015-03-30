<?php

/**
 * Récupère les dernières news du site
 * @param int $count le nombre de news à récupérer
 * @return PDOStatement la liste des news, contenant les colonnes id, titre,
 * image, auteur, extrait, date
 */
function get_last_news($count) {
    global $db;
    $liste_articles = $db->query('SELECT id, titre, image, auteur, extrait, date FROM news ORDER BY date DESC LIMIT '.$count);
    return $liste_articles;
}

/**
 * Récupère une news à partir de son ID
 * @param int $id l’identifiant de la news
 * @return array|false un tableau représentant la news, contenant les clés id, titre,
 * texte, image, auteur, extrait, date ; ou false si la news n’existe pas
 */
function get_news($id) {
    global $db;
    $news = $db->query('SELECT id, titre, texte, image, auteur, extrait, date FROM news WHERE id = '.$db->quote($id))->fetch();
    return $news;
}

/**
 * Renvoie le nombre de news présentes en base
 * @return int
 */
function get_news_count() {
    global $db;
    $news_count_query = $db->query('SELECT COUNT(id) FROM news');
    $news_count = $news_count_query->fetchColumn();
    return $news_count;
}

/**
 * Renvoie le nombre de pages correspondant au nombre d’images en base
 * @return int
 */
function get_page_count() {
    global $config;
    $news_count = get_news_count();
    $page_count = ceil($news_count / $config['news_par_page']);
    return $page_count;
}

/**
 * Renvoie la liste de news correspondant à une page donnée
 * @param int $page le numéro de page souhaité (commençant par 0)
 * @return PDOStatement la liste des news, contenant les colonnes id, titre,
 * image, auteur, extrait, date
 */
function get_news_from_page($page) {
    global $config, $db;
    $offset = $page * $config['news_par_page'];
    $liste_articles = $db->query('SELECT id, titre, image, auteur, extrait, date, texte FROM news ORDER BY date DESC LIMIT '.$offset.','.$config['news_par_page']);
    return $liste_articles;
}

/**
 * Crée un menu HTML permettant de naviguer entre les pages
 * @param int $page le numéro de la page courante
 * @param int $page_count le nombre de pages
 * @return string le HTML correspondant au menu
 */
function get_navigation_menu($page, $page_count) {
    $html = '<ul class="menu">'.PHP_EOL;
    for($i = 0; $i < $page_count; $i++) {
        if($i == $page) {
            $html .= '<li><strong>'.($i+1).'</strong></li>'.PHP_EOL;
        } else {
            $html .= '<li><a href="?page='.($i+1).'">'.($i+1).'</a></li>'.PHP_EOL;
        }
    }
    $html .= '</ul>'.PHP_EOL;
    return $html;
}

/**
 * Affiche une représentation sous forme de tableau HTML d’un objet PDOStatement
 * @param PDOStatement $ps un objet issu d’une requête
 */
function ps_dump($ps) {
    echo '<table border="1">'.PHP_EOL;
    foreach($ps as $num => $ligne) {
        if($num == 0) {
            echo '<tr>'.PHP_EOL;
            foreach($ligne as $header => $_) {
                echo '<th>'.$header.'</th>';
            }
            echo '</tr>';
        }
        echo '<tr>'.PHP_EOL;
        foreach($ligne as $valeur) {
            echo '<td>'.$valeur.'</td>'.PHP_EOL;
        }
        echo '</tr>'.PHP_EOL;
    }
    echo '</table>'.PHP_EOL;
}
