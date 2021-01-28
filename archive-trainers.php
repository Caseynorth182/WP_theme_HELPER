<?php
    get_header();
?>
    <main class="main-content">
      <div class="wrapper">
      </div>
      <section class="trainers">
        <div class="wrapper">
         <?php get_template_part('templates/breadcrumbs');?>
          <h1 class="main-heading trainers__h">Тренеры</h1>
          <?php 
          if(have_posts()) :
          ?>
          <ul class="trainers-list">
          <?php
            while(have_posts()) :
              the_post();
          ?>
            <li class="trainers-list__item">
              <article class="trainer">
                <img src="<?php echo get_field('trainer_pic')['url']?>" alt="<?php echo get_field('trainer_pic')['alt']?>" class="trainer__thumb">
                <div class="trainer__wrap">
                  <h2 class="trainer__name">
                  <?php the_title();?>
                  </h2>
                  <p class="trainer__text">
                  <?php the_field('trainer_descr');?>
                  </p>
                </div>
                 <a data-post-id="<?php echo $id?>" href="#modal-form" class="trainer__subscribe btn btn_modal">записаться</a>
              </article>
            </li>
            <?php
              endwhile;
            ?>
          </ul>
          <?PHP
            else: 
              get_template_part( 'templates/no-posts' );
            endif;
          ?>
        </div>
      </section>
    </main>

<?php
    get_footer();
?>