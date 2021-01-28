<?php
    get_header();

    //ANCHOR получаем ЗАГОЛОВОК ТЕКУЩЕЙ КАТЕГОРИИ(не выводим а получаем)
    /* 1 параметр - что пишем перед категорией */
    /* 2 параметр - выводим ее сейчас или нет */
    $title = single_cat_title('', false );
?>
    <main class="main-content">
      <h1 class="sr-only"> Страница категории <? echo $title?> в блоге сайта спортклуба SportIsland </h1>
      <div class="wrapper">
        <?php get_template_part('templates/breadcrumbs');?>
      </div>
      <?
        if(have_posts()) :
      ?>
      <section class="category-posts">
        <div class="wrapper">
          <h2 class="main-heading category-posts__h"><? echo $title?></h2>
          <ul class="posts-list">
          <?php
            while(have_posts()) :
              the_post();
          ?>
            <li class="last-post">
              <a href="<?php the_permalink();?>" class="last-post__link" aria-label="Читать текст статьи: <?php the_title();?>">
                <figure class="last-post__thumb">
                  <?php 
                    the_post_thumbnail('full', [
                      'class' => 'last-post__img'
                    ]);
                  ?>
                </figure>
                <div class="last-post__wrap">
                  <h3 class="last-post__h"><?php the_title();?></h3>
                  <p class="last-post__text"><?php echo get_the_excerpt( );?></p>
                  <span class="last-post__more link-more">Подробнее</span>
                </div>
              </a>
            </li>
            <?php 
              endwhile;
            ?>
          </ul>
          <!-- <nav class="pagination">
            <h3 class="sr-only">Навигация</h3>
            <ul class="pagination__links">
              <li class="pagination__block pagination__block_current">
                <a href="#" class="pagination__link"> 1 </a>
              </li>
              <li class="pagination__block">
                <a href="#" class="pagination__link"> 2 </a>
              </li>
              <li class="pagination__block">
                <a href="#" class="pagination__link"> 3 </a>
              </li>
              <li class="pagination__block">
                <a href="#" class="pagination__link"> 4 </a>
              </li>
              <li class="pagination__block">
                <a href="#" class="pagination__link"> 5 </a>
              </li>
            </ul>
          </nav> -->
          <?php 
            the_posts_pagination();
          ?>
        </div>
      </section>
        <? else: ?>
        
           <!--  //ANCHOR если нет то выводим эту -->
        <?php get_template_part('templates/no-posts');?>

           <? endif; ?>
    </main>

<?php
    get_footer();
?>