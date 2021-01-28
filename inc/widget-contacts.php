<?php

class SI_Widget_contacts extends WP_Widget{
    public function __construct(){
        parent:: __construct(
            'si_widget_contacts',
            'SportIsland - виджет контактов',
            [
                'name' => 'SportIsland - виджет контактов',
                'description' => 'Выводит Номер телефона и адресс',
            ]
        );
    }

    public function form($instance)  {
    ?>
        <P>
        <label for="<?php echo $this->get_field_id('id-text'); ?>">
            введите текст
        </label>
        <input 
            id="<?php echo $this->get_field_id('id-text'); ?>"
            type="text"
            name="<?php echo $this->get_feild_name('text')?>"
            value="<?php echo $instance['text']?>"
        >
        </P>
    <?php
    }

    public function widget($args, $instance)  {

    }

    public function update($new_instance, $old_instance)  {
        return $new_instance;

    }
};