<?php
    get_header();
?>

     <main class="main-content">
      <div class="wrapper">
        <ul class="breadcrumbs">
          <li class="breadcrumbs__item breadcrumbs__item_home">
            <a href="index.html" class="breadcrumbs__link">Главная</a>
          </li>
          <li class="breadcrumbs__item">
            <a href="schedule.html" class="breadcrumbs__link">Расписание</a>
          </li>
        </ul>
        <h1 class="main-heading schedule-page__h">расписание</h1>
        <div class="tabs">
          <ul class="tabs-btns">
          <?php 
          //ANCHOR получаем все термины таксономии через функцию get_term([]), схожа с функцией wp_query([])
          //SECTION $days - Объект
            $days = get_terms([
              //NOTE с какой таксономии получаем Термины
              'taxonomy' => 'shedule_days',
              //NOTE фильтруем по слагу(ярлыку)
              'order'    => 'ASC',
              //NOTE сортируем по возростанию слага(ярлыка)
              'orderby' => 'slug',
            ]);
            //ANCHOR создаем переменную которая будет счетчиком дней
            //будет на каждом дне
            $index = 0;
            //ANCHOR создаем переменную с классом который отвечает за активность данного <li>
            //и он пока что пустая строка
            $active_class = '';
            //ANCHOR создаем цикл по дням, перебирая каждый день и обрабатывая его
            foreach($days as $day):
              //anchor создаем проверку, если $index роняеться 0, то ему будет добавлен класс active-tab
              if($index === 0){
                $active_class = ' active-tab';
              } else {
                //ANCHOR если нет, то будет присвоино опять пустая строка, что бы не все были активными из-за счетчика
                $active_class = '';
              }
            
          ?>
            <li class="tabs-btns__item<?php echo $active_class;?>">
              <!-- //ANCHOR добовляем в ссылку Ярлык поределенного дня -->
              <a 
              href="#<?php echo $day->slug?>" 
              class="tabs-btns__btn"
              aria-label="<?php echo $days->description?>"
              
              ><?php echo $day->name?></a>
            </li>
              <?php
              $index++;
              endforeach;
              wp_reset_postdata();
              ?>
           
          </ul>
          <ul class="tabs-content">
          <?php 
            $index = 0;
            foreach($days as $day):
              if($index === 0){
                $active_class = ' active-tab';
              } else {
                $active_class = '';
              }
          ?>
            <li 
            class="tabs-content__item<?php echo $active_class;?>" 
            id="<?php echo $day->slug;?>">
              <h2 class="sr-only"><?php echo $day->description;?></h2>
              <ul class="schedule tabs-content__list">
              <?php
              $actions = new WP_Query([
                'posts_per_page' => -1,
                'post_type'      => 'shedule',
                'shadule_days'           => $day->slug,
                'meta_key'       => 'shedule_time_start',
                'orderby'        => 'meta_value_num',
                'order'          => 'ASC'
              ]);
                if($actions->have_posts()):
                while($actions->have_posts()):
                  //ANCHOR устанавливаем глобальные переменные с конкретным постом
                  //SECTION полсе вызова the_post() Нам доступны глобальные переменные типа $id- каждой записи
                  $actions->the_post(  );
                  $trainer = esc_html(get_title(get_the_field()));
                  $place = get_the_terms($id, 'placec')[0];
              ?>
                <li class="schedule__item">
                  <p class="schedule__time"> <?php the_field('shedule_time_start');?> - <?php the_field('shedule_time_end');?> </p>
                  <h2 class="schedule__h"><?php the_field('schedule_name');?></h2>
                  <p class="schedule__trainer">
                   с <?php echo $trainer;?>
                   </p>
                  <p class="schedule__place"> <?php echo $place->name;?> </p>
                </li>
                <?php 
                  endwhile;
                  wp_reset_postdata();
                  endif;
                ?>
           
                
              </ul>
            </li>
          <?php 
            $index++;
            endforeach;
          ?>  
         
          </ul>
        </div>
      </div>
    </main>
    
<?php
    get_footer();
?>