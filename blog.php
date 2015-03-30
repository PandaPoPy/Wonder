<?php
require_once('init.php');

$page_title = 'Blog';
require_once('header.php');

// on récupère le numéro de page souhaité
$nb_pages = get_page_count();
if(isset($_GET['page']) && is_numeric($_GET['page'])) {
    if($_GET['page'] < 1) {
        $page = 0;
    } elseif($_GET['page'] > $nb_pages) {
        $page = $nb_pages - 1;
    } else {
        $page = $_GET['page'] - 1;
    }
} else {
    $page = 0;
}

$liste_news = get_news_from_page($page);

echo '<div class="liste_news">';
foreach($liste_news as $news) {
    $date = new DateTime($news['date']);
    echo '<div class="news">'.PHP_EOL
        .'<h2>'.$news['titre'].'</h2>'.PHP_EOL
        .'<em>Publié le '.$date->format('d/m/Y').' par '.$news['auteur'].'</em>'.PHP_EOL
        .'<p>'.$news['texte'].'</p>'
        .'</div>'.PHP_EOL;
}
echo '</div>';

echo get_navigation_menu($page, $nb_pages);

require_once('footer.php'); ?>
