<?php
    get_header();
?>
    <main class="main-content">
      <h1 class="sr-only">Цены на наши услуги и клубные карты</h1>
      <div class="wrapper">
        <ul class="breadcrumbs">
          <li class="breadcrumbs__item breadcrumbs__item_home">
            <a href="index.html" class="breadcrumbs__link">Главная</a>
          </li>
          <li class="breadcrumbs__item">
            <a href="prices.html" class="breadcrumbs__link">Цены</a>
          </li>
        </ul>
        <section class="prices">
          <h2 class="main-heading prices__h">Цены</h2>
          <table>
            <thead>
              <tr>
                <td>Услуга</td>
                <td>Разовая покупка</td>
                <td>1 месяц</td>
                <td>6 месяцев</td>
                <td>12 месяцев</td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td> Фитнес </td>
                <td> 200 <span> р.- </span>
                </td>
                <td> 900 <span> р.- </span>
                </td>
                <td> 4800 <span> р.- </span>
                </td>
                <td> 9000 <span> р.- </span>
                </td>
              </tr>
              <tr>
                <td> Тренажерный зал </td>
                <td> 200 <span> р.- </span>
                </td>
                <td> 900 <span> р.- </span>
                </td>
                <td> 4800 <span> р.- </span>
                </td>
                <td> 9000 <span> р.- </span>
                </td>
              </tr>
              <tr>
                <td> Женский фитнес </td>
                <td> 200 <span> р.- </span>
                </td>
                <td> 1100 <span> р.- </span>
                </td>
                <td> 6000 <span> р.- </span>
                </td>
                <td> 11000 <span> р.- </span>
                </td>
              </tr>
              <tr>
                <td> Бокс </td>
                <td> 200 <span> р.- </span>
                </td>
                <td> 900 <span> р.- </span>
                </td>
                <td> 4800 <span> р.- </span>
                </td>
                <td> 9000 <span> р.- </span>
                </td>
              </tr>
            </tbody>
          </table>
        </section>
        <?php
            if(have_posts()):
              while(have_posts()):
                the_post();
          ?>

          <?php      
            endwhile;
            else :
                get_template_part('templates/no-posts');
            endif;
          ?>
          <?php
          //выводим клубные карты индивидуальным сособом через new WP_Query
          $query = new WP_query([
            'numberposts' => -1,
            'post_type'   => 'cards',
            /* 'meta_key'    => 'club_order', */
            'orderby'     => 'meta_value_num',
            'order'       => 'ASC'
          ]);
            //выводим условие, если есть в Квери посты то выводим
            if($query->have_posts()):
        ?>
      <section class="cards ">
        <div class="wrapper">
          <h2 class="main-heading cards__h"> клубные карты </h2>
          <ul class="cards__list row">
              <?php
                while($query->have_posts()):
                   $query->the_post();
                   $profit_class = '';
                   if(get_field('club_profit')){
                     $profit_class = ' card_profitable';
                   };
                   $benefits = get_field('club_benefits');
                   $benefits = explode("\n", $benefits);
                   $bg = get_field('club_bg');

                   //NOTE тернарный оператор( условие) если $bg есть то вставляем код в него, если нет, то пустая строка
                   $defoult =  get_template_directory_uri() . '/img/index__cards_card1.jpg';
                   /* $bg = $bg ?
                    "style=\"background-image: url(${bg})\";" :
                    "style=\"background-image: url(${defoult})\"" */
              ?>
            <li class="card<?php echo $profit_class;?>" >
              <h3 class="card__name"><?php the_title();?></h3>
              <p class="card__time">
               <?php the_field('club_time_start');?> 
               &ndash; 
               <?php the_field('club_time_end');?>
               </p>
              <p class="card__price price"><?php the_field('club_price');?><span class="price__unit" aria-label="рублей в месяц">р.-/мес.</span>
              </p>
              <ul class="card__features">
              <?php 
                foreach($benefits as $bn):
              ?>
                <li class="card__feature"><?php echo $bn; ?></li>
              <?php 
                endforeach;
              ?>    
              </ul>
              <a data-post-id="<?php echo $id?>" href="#modal-form" class="card__buy btn btn_modal">купить</a>
            </li>
                <?php
                
                  endwhile;
                  //обязательно сброс постдата
                  wp_reset_postdata(  );
                ?>
          </ul>
        </div>
      </section>
        <?php
           endif; 
        ?>
        <!-- <section class="cards">
          <h2 class="page-heading cards__h"> клубные карты </h2>
          <ul class="cards__list row">
            <li class="card">
              <h3 class="card__name"> полный день </h3>
              <p class="card__time"> 7:00 &ndash; 22:00 </p>
              <p class="card__price price"> 3200 <span class="price__unit">р.-/мес.</span>
              </p>
              <ul class="card__features">
                <li class="card__feature">Безлимит посещений клуба</li>
                <li class="card__feature">Вводный инструктаж</li>
                <li class="card__feature">Групповые занятия</li>
                <li class="card__feature">Сауна</li>
              </ul>
              <a href="#" class="card__buy btn">купить</a>
            </li>
            <li class="card card_profitable">
              <h3 class="card__name"> полный день </h3>
              <p class="card__time"> 7:00 &ndash; 22:00 </p>
              <p class="card__price price"> 3200 <span class="price__unit">р.-/мес.</span>
              </p>
              <ul class="card__features">
                <li class="card__feature">Безлимит посещений клуба</li>
                <li class="card__feature">Вводный инструктаж</li>
                <li class="card__feature">Групповые занятия</li>
                <li class="card__feature">Сауна</li>
              </ul>
              <a href="#" class="card__buy btn">купить</a>
            </li>
            <li class="card">
              <h3 class="card__name"> полный день </h3>
              <p class="card__time"> 7:00 &ndash; 22:00 </p>
              <p class="card__price price"> 3200 <span class=" price__unit">р.-/мес.</span>
              </p>
              <ul class="card__features">
                <li class="card__feature">Безлимит посещений клуба</li>
                <li class="card__feature">Вводный инструктаж</li>
                <li class="card__feature">Групповые занятия</li>
                <li class="card__feature">Сауна</li>
              </ul>
              <a href="#" class="card__buy btn">купить</a>
            </li>
          </ul>
        </section> -->
      </div>
    </main>

<?php
    get_footer();
?>