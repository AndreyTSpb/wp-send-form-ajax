<?php
/**
 * Plugin Name: Send Email To AJAX
 * Description: WordPress плагин для оптравки писем через ajax
 * Plugin URI: https://
 * Author: Andrey Tynyany
 * Version: 1.0.1
 * Author URI: http://tynyany.ru
 *
 * Text Domain: Send Email To AJAX
 *
 * @package Send Email To AJAX
 */

defined('ABSPATH') or die('No script kiddies please!');

define( 'SEA_VERSION', '1.0.1' );

define( 'SEA_REQUIRED_WP_VERSION', '5.5' );

define( 'SEA_PLUGIN', __FILE__ );

define( 'SEA_PLUGIN_BASENAME', plugin_basename( SEA_PLUGIN ) );

define( 'SEA_PLUGIN_NAME', trim( dirname( SEA_PLUGIN_BASENAME ), '/' ) );

define( 'SEA_PLUGIN_DIR', untrailingslashit( dirname( SEA_PLUGIN ) ) );

define( 'SEA_PLUGIN_URL',
    untrailingslashit( plugins_url( '', SEA_PLUGIN ) )
);

if(is_admin()){
    /**
     * Сохранение писем в Админке
     */
    add_action('init', 'sea_mail_callback');

    function sea_mail_callback(){
        $labels = array(
            "name"          => "Mail",
            "singular_name" => "Mail",
            "menu_name"     => "Mail",
            "all_items"     => "All mail",
            "add_new"       => "Add New",
            "add_new_item"  => "Add New",
            "edit"          => "Edit",
            "edit_item"     => "Edit",
            "new_item"      => "New item",
            "view"          => "View",
            "view_item"     => "View item",
            "search_items"  => "Search item",
            "not_found"     => "No found",
            "not_found_in_trash" => "Not found",
        );
        $args = array(
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "show_ui" => true,
            "has_archive" => false,
            "show_in_menu" => true,
            "exclude_from_search" => true,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => true,
            "rewrite"   => false,
            "query_var" => true,
            "menu_position" => 7,
            "menu_icon" => "dashicons-email-alt",
            "supports" => array('title', "editor")
        );

        register_post_type("mail", $args);
    }

}
/**
 * Подключили скрипт для обработки
 */
add_action('wp_footer', 'send_email_ajax_add_script');

add_action('wp_ajax_send_email_ajax', 'send_email_ajax');
add_action('wp_ajax_nopriv_send_email_ajax', 'send_email_ajax');

function send_email_ajax()
{

    //print_r($_POST);
    /**
     * Array
     * (
     * [fio] => test
     * [email] => atynyany@gmail.com
     * [phone] => 666666666
     * [subject] => Запрос с формы - связаться с руководителем
     * )
     */

    $fio = $_POST['fio'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    $subject = $_POST['subject'];

    $to = $_POST['to_email'];//кому отправляем

    $body = "fio: " . $fio . "<br/>
         email: " . $email . "<br/>
         phone: " . $phone . "<br/>
         message: " . $message . "<br/>";

    // удалим фильтры, которые могут изменять заголовок $headers
    remove_all_filters('wp_mail_from');
    remove_all_filters('wp_mail_from_name');

    $headers = array(
        'From: Math123 <info@math123.ru>',
        'Content-type: text/html',
    );

    $res = wp_mail($to, $subject, $body, $headers);
    var_dump($res);

        /**
         * Сохраняем письмо в БД
         */
        $post_data = array(
            "post_title" => $fio,
            'post_content' => $body,
            "post_status" => 'publish',
            "post_autor" => 1,
            "post_type" => 'mail'
        );

        wp_insert_post($post_data);

    wp_die();
}

function send_email_ajax_add_script()
{
    /**
     * регистрация скриптов
     */
    wp_register_script('send_email_ajax_script', plugins_url('script.js', __FILE__));


    /**
     * подключение скриптов
     */
    wp_enqueue_script('send_email_ajax_script');
}