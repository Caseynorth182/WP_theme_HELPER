<?get_header();
    //делаем проверку
    if( is_home()) :

?>

  <main class="main-content">
      <h1 class="sr-only">Страница  блога на сайте спорт-клуба SportIsland</h1>
      <div class="wrapper">
        <?php get_template_part('templates/breadcrumbs');?>
      </div>

     <!--  //NOTE начало Стандартного циклв  -->
       <?php  if(have_posts(  )) :?>
       <!--  //ANCHOR если запись есть (POST), то выводим эту верстку -->
      <section class="last-posts">
        <div class="wrapper">
          <h2 class="main-heading last-posts__h">Последние  записи </h2>
          <ul class="posts-list">
         <!--  //NOTE тег <li> повторяющейся тег и в нем хранится запись -->
            <?php 
            /* //NOTE до тех пор пока есть посты выводим их */
              while(have_posts() ) :
                //NOTE выводит и подготавливает каждую запись
                the_post();
            ?>
            <li class="last-post">
              <a href="<?php the_permalink()?> " class="last-post__link" aria-label="Читать текст статьи: <?php the_title(); ?>">
                <figure class="last-post__thumb">
                  <?php the_post_thumbnail( 'full', [
                    'class' => 'last-post__img',
                  ]); ?>
                </figure>
                <div class="last-post__wrap">
                  <h3 class="last-post__h"><?php the_title();?></h3>
                  <p class="last-post__text"><?php echo get_the_excerpt();?></p>
                  <span class="last-post__more link-more">Подробнее</span>
                </div>
              </a>
            </li>
            <?php

              endwhile;

            ?>                
          </ul>
        </div>
      </section>

           <?  else: ?>
           <!--  //ANCHOR если нет то выводим эту -->
        <?php get_template_part('templates/no-posts');?>

           <? endif; ?>
<!--  //NOTE конец Стандартного циклв  -->

        <!-- //ANCHOR Создаем переменную, для получения категорий -->
       <!--  get_categories(); - возвращает массив категорий -->
       <!-- $cats = будет содержать массив категорий -->
        <?php
          $cats = get_categories(  );
           /*  //NOTE Делаем проверку, есть ли эти категории */
          if($cats) :
            //NOTE if $cats = true
        ?>
      <section class="categories">
        <div class="wrapper">
          <h2 class="categories__h main-heading"> категории </h2>
          <ul class="categories-list">
          <?php 
            foreach($cats as $cat) :
              /* //NOTE внутри каждой итерации нам нужно порлучить ссылку на эту категорию */
              //SECTION $cat - это обьект !!!!!
              $cat_link = get_category_link(  $cat->cat_ID );
              //NOTE получить значение разных категорий при помощи get_field()
              //SECTION в get_field(2 параметра 1 ярлык и 2 это специальный параметр через ниж дефис)
              $img = get_field('cat_thumb', 'category_'.$cat->cat_ID);
              $img_url = $img['url'];
              $img_alt = $img['alt'];
          ?>
            <li class="category">
           <!--  //NOTE вставляем ссылку на категорию, которую мы получили в цикле и поместили в переменную $cat_link -->
              <a href="<?php echo $cat_link;?>" class="category__link">
                <img src="<?php echo $img_url;?>" alt="<?php echo $img_alt;?>" class="category__thumb">
                <span class="category__name"><?php echo $cat->name; ?></span>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </section>

      <?php
        endif;
      ?>
    </main>

    <? else:?>
<main class="main-content">
      <h1 class="sr-only">Страница категорий блога на сайте спорт-клуба SportIsland</h1>
      <div class="wrapper">
        <?php get_template_part('templates/breadcrumbs');?>
      </div>

      
        <!--  //NOTE начало Стандартного циклв  -->
       <?php  if(have_posts(  )) :?>
     
    
      <section class="last-posts">
        <div class="wrapper">
          <h2 class="main-heading last-posts__h"> последние записи </h2>
          <ul class="posts-list">
          <?php
            while(have_posts()) :
              the_post();
          ?>
            <li class="last-post">
              <a href="single.html" class="last-post__link" aria-label="Читать текст статьи:<?php the_title();?>">
                <figure class="last-post__thumb">
                  <img src="img/blog__article_thmb1.jpg" alt="" class="last-post__img">
                  <?php 
                    the_post_thumbnail( 'full', [
                      'class' => 'last-post__img',
                    ]);
                  ?>
                </figure>
                <div class="last-post__wrap">
                  <h3 class="last-post__h"><? the_title(); ?></h3>
                  <p class="last-post__text"><?php get_the_excerpt();?></p>
                  <span class="last-post__more link-more">Подробнее</span>
                </div>
              </a>
            </li>
            <?php endwhile;?>
          </ul>
          <?php the_posts_pagination( ); ?>
        </div>
      </section>
      
           <?  else: ?>
           <!--  //ANCHOR если нет то выводим эту -->
            <?php get_template_part('templates/no-posts');?>

           <? endif; ?>
<!--  //NOTE конец Стандартного циклв  -->
      
    </main>


    <? endif;
    get_footer();
    ?>