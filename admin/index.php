<?php
require_once('init.php');

$page_title = 'Index';
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

echo '<p><a href="insert.php">Ajouter une news</a></p>'.PHP_EOL;

echo '<table class="liste_news">'
    .'<tr><th>ID</th><th>Titre</th><th>Auteur</th><th>Date</th><th>Édition</th></tr>'.PHP_EOL;
foreach($liste_news as $news) {
    $date = new DateTime($news['date']);
    echo '<tr class="news">'.PHP_EOL
        .'<td>'.$news['id'].'</td>'.PHP_EOL
        .'<td>'.$news['titre'].'</td>'.PHP_EOL
        .'<td>'.$news['auteur'].'</cd>'.PHP_EOL
        .'<td>'.$date->format('d/m/Y H:i:s').'</td>'.PHP_EOL
        .'<td><a href="update.php?id='.$news['id'].'">✎</a> <a href="delete.php?id='.$news['id'].'">✗</a></td>'.PHP_EOL
        .'</tr>'.PHP_EOL;
}
echo '</table>';

echo get_navigation_menu($page, $nb_pages);

require_once('footer.php');
