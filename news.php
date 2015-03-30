<?php
require_once('init.php');

$page_title = 'Affichage d’une news';
require_once('header.php');

if(isset($_GET['id'])) {
    $news = get_news($_GET['id']);
    if($news) {
        $date = new Datetime($news['date']);
        echo '<article class="blog-slide">
            <div class="blog-slide-img">
                <img src="img/'.$news['image'].'" alt="">
            </div>
            <h2 class="blog-slide-title">'.$news['titre'].'</h2>
            <p class="blog-slide-info">
            Posted on: '.$date->format('M j, Y H:i:s').'<br/>
            Posted by: '.$news['auteur'].'
            </p>
            <p class="blog-slide-text">'.$news['texte'].'</p>
        </article>'.PHP_EOL;
    } else {
        echo '<p class="error">La news demandée n’existe pas.</p>';
    }
} else {
    echo '<p class="error">Veuillez sélectionner une news.</p>';
}

require_once('footer.php');
