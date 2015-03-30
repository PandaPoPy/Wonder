<?php
require_once('init.php');

$page_title = 'Accueil';
require_once('header.php');
?>
            <section class="page-slider box">
                <div class="page-flexslider flexslider" data-slideshow>
                    <ul class="slides">
                        <li><img src="img/slider/slider1.jpg" alt=""></li>
                        <li><img src="img/slider/slider2.jpg" alt=""></li>
                        <li><img src="img/slider/slider3.jpg" alt=""></li>
                    </ul>
                </div>
                <div class="page-slider-description">
                    <h2 class="lead">This is wonderful. Wonder is a beautiful multipurpose HTML/CSS theme.</h2>
                    <p>For us, the product package represents the core media within shopper marketing.</p>
                </div>
            </section>

            <section class="work">
                <h1 class="title"><span class="title-focus">Take a look</span> at our work</h1>
                <ul class="work-items box">
                    <li class="box-item">
                        <a href="#" title=""><img src="img/work-1.jpg" alt=""></a>
                        <div class="work-item-content">
                            <h2><a href="#" title="">Bad Dinausor</a></h2>
                            <p>Not so bad really.</p>
                        </div>
                    </li>
                    <li class="box-item">
                        <a href="#" title=""><img src="img/work-2.jpg" alt=""></a>
                        <div class="work-item-content">
                            <h2><a href="#" title="">Bad Dinausor</a></h2>
                            <p>Not so bad really.</p>
                        </div>
                    </li>
                    <li class="box-item">
                        <a href="#" title=""><img src="img/work-3.jpg" alt=""></a>
                        <div class="work-item-content">
                            <h2><a href="#" title="">Bad Dinausor</a></h2>
                            <p>Not so bad really.</p>
                        </div>
                    </li>
                    <li class="box-item">
                        <a href="#" title=""><img src="img/work-4.jpg" alt=""></a>
                        <div class="work-item-content">
                            <h2><a href="#" title="">Bad Dinausor</a></h2>
                            <p>Not so bad really.</p>
                        </div>
                    </li>
                </ul>
            </section>

            <div class="box secondary-box">
                <section class="blog box-item">
                    <h1 class="title"><span class="title-focus">From the blog</span> all the latest news</h1>
                    <div class="blog-flexslider flexslider" data-slideshow>
                        <ul class="slides">
                            <?php
                            $last_news = get_last_news(3);
                            foreach($last_news as $news) {
                                $date = new DateTime($news['date']);
                                echo '<li class="blog-slide">
                                    <div class="blog-slide-img">
                                        <img src="img/'.$news['image'].'" alt="">
                                    </div>
                                    <h2 class="blog-slide-title">'.$news['titre'].'</h2>
                                    <p class="blog-slide-info">
                                    Posted on: '.$date->format('M j, Y').'<br/>
                                    Posted by: '.$news['auteur'].'
                                    </p>
                                    <p class="blog-slide-text">'.$news['extrait'].' <a href="news.php?id='.$news['id'].'" title="">Read more</a></p>
                            </li>'.PHP_EOL;
                            }
                            ?>
                        </ul>
                    </div>
                </section>
                <section class="videos box-item">
                    <h1 class="title"><span class="title-focus">Recent video</span> created by us</h1>
                    <div class="video-item">
                        <iframe src="http://player.vimeo.com/video/877053?color=ef4f1d" width="500" height="281" allowFullScreen></iframe>
                    </div>
                </section>
            </div>
<?php require_once('footer.php'); ?>
