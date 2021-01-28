<?php

/* require_once(__DIR__ . '/inc/widget-contacts.php'); */
require_once( get_template_directory() . '/inc/widget-contacts.php' );


add_action( 'after_setup_theme', 'si_setup' );  
add_action('wp_enqueue_scripts', 'si_scripts');
add_action( 'widgets_init', 'si_register' ); 
//NOTE регистрация Типов записей
add_action('init', 'si_register_types');
//NOTE Что бы в админке появилось новое поле для доп работы с мета(Новое кастомное поле)
add_action('add_meta_boxes', 'si_meta_boxes');
//NOTE //подписываемся на этот ХУК что бы работали наши кастомные мета(лайки) 
add_action('save_post', 'si_like_save_meta');
//NOTE Регистрируем новое поле в админке(для слогана)
add_action('admin_init', 'si_register_my_slogan');
//NOTE подписываемся на хуки из файла admin-post.php для отправки формы 
        //SECTION admin_post_nopriv_*** (*** пишем значение импута value которые мы добавили)
add_action('admin_post_nopriv_si-modal-form', 'si_modal_form_handler' );
add_action('admin_post_si-modal-form', 'si_modal_form_handler' );
//NOTE  подписываемся на AJAX хуки, которые сами создаем
add_action('wp_ajax_nopriv_post-likes', 'si_likes' );
add_action('wp_ajax_post-likes', 'si_likes' );
//NOTE подписываемся на хук, для добовление в админке, при выводи записей , колонки лайков(кастомной колонки)
add_action('manage_posts_custom_column','si_like_column',5,2);

//NOTE Подписываемся на фильтр что еще оду колонку создать
add_filter( 'manage_posts_columns', 'si_add_col_likes' );




//NOTE создаем Шорткод
add_shortcode( 'si-paste-link', 'si_paste_link' );
add_filter('si_widget_text',  'do_shortcode');


//ANCHOR Подключаем скрипты и стили
function si_scripts() {
    wp_enqueue_style('si-style', get_template_directory_uri(  ) . '/assets/css/styles.css' , [], '1.0' , 'all');
    wp_enqueue_script('si-script', get_template_directory_uri(  ) . '/assets/js/js.js', [], '1.1' , true);
};



function si_setup(){
    //сюда передаем параметр, говорим что включить WP
    //включаем лого добовление 
    add_theme_support('custom-logo');
    //включаем title 
    add_theme_support('title-tag');
    //включает миниатюры в записях
    add_theme_support('post-thumbnails');
    //включает раздел меню 1 способ
    /* add_theme_support('menus'); */
    //включаем и регистрируем меню(лучший способ)
    register_nav_menu( 'menu_header', 'меню в шапке' );
    register_nav_menu( 'menu_footer', 'меню в футере' );
    
}

/* //NOTE  регистрация Виждетов */

function si_register(){
   /*  register_widget('si_widget_contacts'); */
    register_sidebar([
        'name'          => 'Контакты в шапке сайта',
        'id'            => 'si_header',
        'before_widget' => null,
        'after_widget'  => null,
    ]);
    register_sidebar([
        'name'          => 'Контакты в подвале сайта',
        'id'            => 'si_footer',
        'before_widget' => null,
        'after_widget'  => null,
    ]);
    register_sidebar([
        'name'          => 'сайдбар в футере колонка 1',
        'id'            => 'si_footer_col_1',
        'before_widget' => null,
        'after_widget'  => null,
    ]);
    register_sidebar([
        'name'          => 'сайдбар в футере колонка 2',
        'id'            => 'si_footer_col_2',
        'before_widget' => null,
        'after_widget'  => null,
    ]);
    register_sidebar([
        'name'          => 'сайдбар в футере колонка 3',
        'id'            => 'si_footer_col_3',
        'before_widget' => null,
        'after_widget'  => null,
    ]);
    register_sidebar([
        'name'          => 'Карта',
        'id'            => 'si_map',
        'before_widget' => null, 
        'after_widget'  => null,
    ]);
    register_sidebar([
        'name'          => 'сайдбар под картой',
        'id'            => 'si_map_widget',
        'before_widget' => null,
        'after_widget'  => null,
    ]);

    //
} 

//ANCHOR создаем шорт код
function si_paste_link($attr){
     $params = shortcode_atts([
         'link' => '',
         'text' => '',
         'type' => ''
     ], $attr);
     //условие
     $params['text'] = $params['text'] ? $params['text'] : $params['link'];
     //проверка
     if($params['link']) {
        $protocol = '';
        switch($params['tipe']){
            case 'email': 
                $protocol = 'mailto:';
                break;

            case 'phone':
                $protocol = 'tel:';
                $params['link'] = preg_replace('/[^+0-9]/', '', $params['link']) ;
                break;

            default:
                $protocol = '';
                break;      
        }
        $link = $protocol . $params['link'];
        $text = $param['text'];
        return "<a href=\"${link}\">${text}</a>";
     } else {
         return '';
     }

}

/* //ANCHOR регистрируем Новые типы Записей и таксономий */

function si_register_types() {
    //NOTE Услуги
    register_post_type('services ', [
       /*  //ANCHOR Настройки, можно копировать в другие проекты */
       //NOTE имя склоняемое
          'labels' => [
            'name'               => 'Услуги', // основное название для типа записи
            'singular_name'      => 'Услуги', // название для одной записи этого типа
            'add_new'            => 'Добавить новую услуги', // для добавления новой записи
            'add_new_item'       => 'Добавить новую услуги', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => 'Редактировать услуги', // для редактирования типа записи
            'new_item'           => 'Новая услуга', // текст новой записи
            'view_item'          => 'Смотреть услуги', // для просмотра записи этого типа.
            'search_items'       => 'Искать услуги', // для поиска по этим типам записи
            'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
            'parent_item_colon'  => '', // для родителей (у древовидных типов)
            'menu_name'          => 'Услуги', // название меню
        ],
        //NOTE Видна или нет в админке
        'public'              => true,
        //NOTE на какой позиции
        'menu_position'       => 20,
        //NOTE иконка 
        'menu_icon'           => 'dashicons-smiley', 

        'hierarchical'        => false,
        //NOTE специальные ярлыки, что можно редактировать в этой записи
        'supports'            => ['title', 'editor'],
        //NOTE Старница на которой будт выведены все типы записей, Fasle-не регестрирует тип записи/ true - регестрируем
        //ANCHOR по этому URL можно получить все записи этого типа
        'has_archive' => true
    ]);
    
    //NOTE ТРЕНЕРЫ
    register_post_type('trainers ', [
       /*  //ANCHOR Настройки, можно копировать в другие проекты */
       //NOTE имя склоняемое
          'labels' => [
            'name'               => 'Тренеры', // основное название для типа записи
            'singular_name'      => 'Тренер', // название для одной записи этого типа
            'add_new'            => 'Добавить нового тренера', // для добавления новой записи
            'add_new_item'       => 'Добавить нового тренера', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => 'Редактировать тренера', // для редактирования типа записи
            'new_item'           => 'Новый тренер', // текст новой записи
            'view_item'          => 'Смотреть тренера', // для просмотра записи этого типа.
            'search_items'       => 'Искать тренера', // для поиска по этим типам записи
            'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
            'parent_item_colon'  => '', // для родителей (у древовидных типов)
            'menu_name'          => 'Тренеры', // название меню
        ],
        //NOTE Видна или нет в админке
        'public'              => true,
        //NOTE на какой позиции
        'menu_position'       => 20,
        //NOTE иконка 
        'menu_icon'           => 'dashicons-universal-access', 

        'hierarchical'        => false,
        //NOTE специальные ярлыки, что можно редактировать в этой записи
        'supports'            => ['title', 'editor'],
        //NOTE Старница на которой будт выведены все типы записей, Fasle-не регестрирует тип записи/ true - регестрируем
        //ANCHOR по этому URL можно получить все записи этого типа
        'has_archive' => true
    ]);

    //NOTE Занятия
    register_post_type('shedule ', [
       /*  //ANCHOR Настройки, можно копировать в другие проекты */
       //NOTE имя склоняемое
          'labels' => [
            'name'               => 'Занятия', // основное название для типа записи
            'singular_name'      => 'Занятия', // название для одной записи этого типа
            'add_new'            => 'Добавить новое Занятия', // для добавления новой записи
            'add_new_item'       => 'Добавить новое Занятия', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => 'Редактировать Занятия', // для редактирования типа записи
            'new_item'           => 'Новое Занятия', // текст новой записи
            'view_item'          => 'Смотреть Занятия', // для просмотра записи этого типа.
            'search_items'       => 'Искать Занятия', // для поиска по этим типам записи
            'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
            'parent_item_colon'  => '', // для родителей (у древовидных типов)
            'menu_name'          => 'Занятия', // название меню
        ],
        //NOTE Видна или нет в админке
        'public'              => true,
        //NOTE на какой позиции
        'menu_position'       => 20,
        //NOTE иконка 
        'menu_icon'           => 'dashicons-thumbs-up', 

        'hierarchical'        => false,
        //NOTE специальные ярлыки, что можно редактировать в этой записи
        'supports'            => ['title', 'editor'],
        //NOTE Старница на которой будт выведены все типы записей, Fasle-не регестрирует тип записи/ true - регестрируем
        //ANCHOR по этому URL можно получить все записи этого типа
        'has_archive' => true
    ]);
        //SECTION Регистрируем таксономию и привязываем к типам записей   
        register_taxonomy('shedule_days', ['shedule'], [
            'labels'                => [
                'name'              => 'Дни недели',
                'singular_name'     => 'День',
                'search_items'      => 'Найти день недели',
                'all_items'         => 'Все дни недели',
                'view_item '        => 'Посмотреть дни недели',
                'edit_item'         => 'Редактировать дни недели',
                'update_item'       => 'Обновить',
                'add_new_item'      => 'Добавить день недели',
                'new_item_name'     => 'Добавить день недели',
                'menu_name'         => 'Все дни недели',
            ],
            'description'           => '',
            'public'                => true,
            'hierarchical'          => true
        ]);
        //SECTION Регистрируем таксономию и привязываем к типам записей   
        register_taxonomy('places', ['shedule'], [
            'labels'                => [
                'name'              => 'Залы',
                'singular_name'     => 'залы',
                'search_items'      => 'Найти залы',
                'all_items'         => 'все залы',
                'view_item '        => 'Посмотреть залы',
                'edit_item'         => 'Редактировать залы',
                'update_item'       => 'Обновить',
                'add_new_item'      => 'Добавить залы',
                'new_item_name'     => 'Добавить залы',
                'menu_name'         => 'Все залы',
            ],
            'description'           => '',
            'public'                => true,
            'hierarchical'          => true
        ]);

    //NOTE Клубные карты(У них не будет своей страницы архива)
     register_post_type('cards', [
       /*  //ANCHOR Настройки, можно копировать в другие проекты */
       //NOTE имя склоняемое
          'labels' => [
            'name'               => 'Карты', // основное название для типа записи
            'singular_name'      => 'Карты', // название для одной записи этого типа
            'add_new'            => 'Добавить новое карта', // для добавления новой записи
            'add_new_item'       => 'Добавить новое карта', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => 'Редактировать карта', // для редактирования типа записи
            'new_item'           => 'Новая карту', // текст новой записи
            'view_item'          => 'Смотреть карту', // для просмотра записи этого типа.
            'search_items'       => 'Искать карту', // для поиска по этим типам записи
            'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
            'parent_item_colon'  => '', // для родителей (у древовидных типов)
            'menu_name'          => 'Карты', // название меню
        ],
        //NOTE Видна или нет в админке
        'public'              => true,
        //NOTE на какой позиции
        'menu_position'       => 20,
        //NOTE иконка 
        'menu_icon'           => 'dashicons-tickets', 

        'hierarchical'        => false,
        //NOTE специальные ярлыки, что можно редактировать в этой записи
        'supports'            => ['title', 'editor'],
        //NOTE Старница на которой будт выведены все типы записей, Fasle-не регестрирует тип записи/ true - регестрируем
        //ANCHOR по этому URL можно получить все записи этого типа
        'has_archive' => false
    ]);

    //NOTE Цена
    register_post_type('prices ', [
       /*  //ANCHOR Настройки, можно копировать в другие проекты */
       //NOTE имя склоняемое
          'labels' => [
            'name'               => 'Цена', // основное название для типа записи
            'singular_name'      => 'Цена', // название для одной записи этого типа
            'add_new'            => 'Добавить новое цену', // для добавления новой записи
            'add_new_item'       => 'Добавить новое цену', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => 'Редактировать цену', // для редактирования типа записи
            'new_item'           => 'Новая цена', // текст новой записи
            'view_item'          => 'Смотреть цену', // для просмотра записи этого типа.
            'search_items'       => 'Искать цену', // для поиска по этим типам записи
            'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
            'parent_item_colon'  => '', // для родителей (у древовидных типов)
            'menu_name'          => 'Цена', // название меню
        ],
        //NOTE Видна или нет в админке
        'public'              => true,
        //NOTE на какой позиции
        'menu_position'       => 20,
        //NOTE иконка 
        'menu_icon'           => 'dashicons-money-alt', 

        'hierarchical'        => false,
        //NOTE специальные ярлыки, что можно редактировать в этой записи
        'supports'            => ['title', 'editor'],
        //NOTE Старница на которой будт выведены все типы записей, Fasle-не регестрирует тип записи/ true - регестрируем
        //ANCHOR по этому URL можно получить все записи этого типа
        'has_archive' => true
    ]);
     //NOTE Тип записи от клинета
    register_post_type('orders ', [
       /*  //ANCHOR Настройки, можно копировать в другие проекты */
       //NOTE имя склоняемое
          'labels' => [
            'name'               => 'Заявки', // основное название для типа записи
            'singular_name'      => 'Заявка', // название для одной записи этого типа
            'add_new'            => 'Добавить новую Заявку', // для добавления новой записи
            'add_new_item'       => 'Добавить новую Заявку', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => 'Редактировать Заявку', // для редактирования типа записи
            'new_item'           => 'Новая Заявка', // текст новой записи
            'view_item'          => 'Смотреть Заявку', // для просмотра записи этого типа.
            'search_items'       => 'Искать Заявку', // для поиска по этим типам записи
            'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
            'parent_item_colon'  => 'Заявка', // для родителей (у древовидных типов)
            'menu_name'          => 'Заявки', // название меню
        ],
        //NOTE Видна или нет в админке
        'public'              => false,
        //SECTION если Public = false то нужно поставить show_ui = true,для отображения в админке
        'show_ui'             => true, 
        //SECTION вместе c show_ui
        'show_in_menu'        => true,
        //NOTE на какой позиции
        'menu_position'       => 8,
        //NOTE иконка 
        'menu_icon'           => 'dashicons-edit-page', 

        'hierarchical'        => false,
        //NOTE специальные ярлыки, что можно редактировать в этой записи
        'supports'            => ['title'],
        //NOTE Старница на которой будт выведены все типы записей, Fasle-не регестрирует тип записи/ true - регестрируем
        //ANCHOR по этому URL можно получить все записи этого типа
        'has_archive' => true
    ]);
}

//ANCHOR (кастомные МЕТА боксы) создаем в админке поле для лайков
function si_meta_boxes(){
    //Выводит количество Лайков в админке
    add_meta_box(
        //создание блока с id si-like
        'si-like',
        //Добавь ему название Количество лайков
        'Количество лайков: ',
        //за верстку этого блока будет отвечать(что эта функция сделает то и добавь на страницу)
        'si_meta_like_cb',
        'post'
    ); 
    //ANCHOR создаем переменную и цикл для короткой записи создания кастомных полейй записи в админке
    $fields = [
        'si_order_date' => 'Дата заявки: ' ,
        'si_order_name' => 'Имя клиента: ',
        'si_order_phone' => 'Номер телефона',
        'si_order_choice' => 'Выбор клиента'
    ];
    foreach ($fields as $slug => $text ) {
         add_meta_box(
            //Выводит дату создания записи
            //создание блока с id si_order_date
            $slug,
            //Добавь ему название Количество лайков
            $text,
            //за верстку этого блока будет отвечать(что эта функция сделает то и добавь на страницу)
            'si_order_fields_cb',
            //Выбераем на какие типы записей будет выводиться это мета поле, ярлык типа записи, обшее это post
            'orders',
            'advanced',
            'default',
            $slug
        ); 
    }
    
    //ANCHOR /* Коментирую, потому что буду сокращать это цикломЮ для сокращения места */
    /* add_meta_box(
        //Выводит дату создания записи
        //создание блока с id si_order_date
        'si_order_date',
        //Добавь ему название Количество лайков
        'Дата заявки: ',
        //за верстку этого блока будет отвечать(что эта функция сделает то и добавь на страницу)
        'si_order_date_cb',
        //Выбераем на какие типы записей будет выводиться это мета поле, ярлык типа записи, обшее это post
        'orders'
    ); 
    add_meta_box(
        //создание блока с id si_order_name
        'si_order_name',
        //Добавь ему название Количество лайков
        'Имя клиента: ',
        //за верстку этого блока будет отвечать(что эта функция сделает то и добавь на страницу)
        'si_order_name_cb',
        //Выбераем на какие типы записей будет выводиться это мета поле, ярлык типа записи, обшее это post
        'orders'
    );  */
}
    //SECTION Callback функция которая будет выводить верстку в админке(как поле будет выглядить)
    //принимает обязательный параметр $post_obj - и это объект
   /*  function si_meta_like_cb($post_obj){
        $likes = get_post_meta($post_obj->ID, 'si-like', true);
        $likes = $likes ? $likes : 0;
        //echo "<input type=\"text\" name=\"si-like\" value=\"${likes}\">";
        echo '<p>' . $likes . '</p>';
    }

    function si_order_date_cb($post_obj){
        //NOTE создаем переменную для 
        $date = get_post_meta($post_obj->ID, 'si_order_date', true);
        //SECTION (Проверка) Если есть переменная $date вообще, если ЕСТЬ то выводим $date, а ИНАЧЕ выводим ''
        $date = $date ? $date : '';
        //echo "<input type=\"text\" name=\"si-like\" value=\"${likes}\">";
        echo '<span>' . $likes . '</span>';
    } */

    //SECTION Каллбэк функция для цикла 
    function si_order_fields_cb($post_obj, $slug){
       $slug = $slug['args'];
       $data = '';
       switch ($slug) {
           case 'si_order_date':
               $data = $post_obj->post_date;
               break;
           case 'si_order_choice':
                // При клике будет выполняться код,
                // тут получать ID того что кликнул
               $id = get_post_meta($post_obj->ID, $slug, true);
               //из ID будет получать Заголовок(TITLE) записи
               $title = get_the_title($id);
               //Тут будет получать ТИП ЗАПИСИ
               $type = get_post_type_object(get_post_type($id))->labels->singular_name ;
               $data = 'Клиент выюрал: <strong>' . $title . '</strong> . <br> Из Раздела: <strong>' . $type . '</strong>';
               break;
           
           default:
                //NOTE создаем переменную для 
                $data = get_post_meta($post_obj->ID, $slug, true);
                //SECTION (Проверка) Если есть переменная $date вообще, если ЕСТЬ то выводим $date, а ИНАЧЕ выводим ''
                $data = $data ? $dadatate : 'Нет данных';
               
               break;
       }
        //echo "<input type=\"text\" name=\"si-like\" value=\"${likes}\">";
        echo '<p>' . $data . '</p>';
    }
//ANCHOR 
function si_like_save_meta( $post_id ){
    //NOTE делаем проверку(если Глобальная переменная $_POST имеет в себе КЛЮЧ 'si-like'? ТО)
   if( isset($_POST['si-like'])) {
        update_post_meta($post_id , 'si-like', $_POST['si-like']);
   }
}


//ANCHOR создаем поле в админке WP в настройках, для замены слогана сайта 
function si_register_my_slogan(){
    add_settings_field(
        //ANCHOR ID под которым все сохроняеться и выводиться на сайте 
        'si_option_field_slogan',
        'Слоган Вашего сайта',
        //ANCHOR в эту функцию будет передавать значения и аргументы в $args;
        'si_option_slogan_cb',
        'general',
        'default',
        //ANCHOR массив с ключом label_for передаеться в аргументы $args функции si_option_slogan_cb($args) как Callback функция
        ['label_for' => 'si_option_field_slogan']
    );
    register_setting(
        'general',
        'si_option_field_slogan',
        //ANCHOR strval - функция языка PHP которая принимает переменную и пытаетьсявернуть строку ;
        'strval'
    ); 
}
    //SECTION функция которая выводит поле для заполнения в Админку WP
    function si_option_slogan_cb($args){
        $slug = $args['label_for'];
        ?>
        <input 
            type="text"
              id="<?php echo $slug;?>"
           value="<?php echo get_option($slug);?>"  
            name="<?php echo $slug;?>"
            class="regular-text code" 

        <?php

    };

//ANCHOR функция для обработк формы
function si_modal_form_handler(){
    $name = $_POST['si-user-name'] ? $_POST['si-user-name'] : 'Аноним';
    $phone = $_POST['si-user-phone'] ? $_POST['si-user-phone']: false;
    $choice = $_POST['form-post-id'] ? $_POST['form-post-id']: 'empty';
    if($phone){
        //NOTE Проверка, если есть номер телефона то обрабатываем поля специальной функцией
        //принимает в себя строку и Удалаяет все HTML Теги и оставляет только текст
        $name = wp_strip_all_tags($name);
        $phone = wp_strip_all_tags($phone);
        $choice = wp_strip_all_tags($choice);
        //NOTE эта функция позволяет нам создать запись без админки, а только кодом и она сохраниться в БАЗЕ ДАННЫХ
        //NOTE wp_slash() функция которая ставит / перед опасными тегами, тем самым экранирует их и делает их безопасными(от SQL иньекции);
        $id = wp_insert_post(wp_slash([
            'post_title' => 'Заявка № ',
            'post_type'  => 'orders',
            'post_status'=> 'publish',
            'meta_input' => [
                'si-order-name' => $name,
                'si-order-phone'=> $phone,
                'si-order-choice'=> $choice
            ]
        ]));
       
        if($id !== 0){
            //NOTE функция которая обновляет ПОСТЫ
            wp_update_post([
                //Выбираем ID поста который нужно изменить или переменную постов
                'ID' => $id,
                //меняем Заголовок Поста(POST_TITLE)
                'post_title' => 'Заявка № ' . $id,
                'meta_input' => [
                'si-order-name' => $name,
                'si-order-phone'=> $phone,
                'si-order-choice'=> $choice
            ]
                
            ]);
            //NOTE функция плагина ACF для изменения ПОЛЯ()
            update_field('order_status', 'new', $id);
        }
    }
    //ANCHOR после того когда нас перебросило после ввода данных и отправки формы, перебрасывает на admin-post.php
    // и этим кодом мы переправляемся этот код перебрасывает нас на главную
    header('Location: '.home_url());
}

//ANCHOR создание функции для лайков
function si_likes() {
    $id = $_POST['id'];
    $todo = $_POST['todo'];
    $current_data = get_post_meta($id,'si-like', true);
    $current_data = $current_data ? $current_data : 0;
    if($todo === 'plus'){
        $current_data++;
    } else {
        $current_data--;
    }
    $res = update_post_meta($id, 'si-like', $current_data );
    if($res){
        echo $current_data;
        wp_die();
    } else {
        wp_die('Лайк не сохранили', 500);
    }
}
 
//ANCHOR создание кастомной колонки(с ЛАЙКАМИ) в админке при выводе Записей ЧАСТЬ1
function si_like_column($col_name, $id){
    if($col_name !== 'col_likes') return;
    //получаем количсество лайков? передаем ID функции(параметр), slug названия поля, под каким мы его сохраняем, true-что бы вернул не массив, а значение
    $likes = get_post_meta($id,'si-like', true);
    echo $likes ? $likes : 0;
}

//ANCHOR создание кастомной колонки(с ЛАЙКАМИ) в админке при выводе Записей ЧАСТЬ2
function si_add_col_likes ($defaults) {
    $type = get_current_screen(); 
    if($type->post_type === 'post') {
        $defaults['col_likes'] = 'Лайки';
    }
    //NOTE так как это у нас финкция которая на фильтре, то обязательно нужно что то возвращать
    return $defaults;
}
