<?php
/*
Plugin Name: Simple Tweets
Plugin URI: https://github.com/wdalmut/wp-simple-tweets
Description: Just a simple example to show how to integrate Twig into a WP plugin.
Version: 0.0.1
Author: Walter Dal Mut
Author URI: http://www.corley.it/
License: MIT
*/

if (!class_exists("\Composer\Autoload\ClassLoader")) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

add_action('wp_dashboard_setup', 'wptwig_add_dashboard_widgets' );

function wptwig_add_dashboard_widgets() {
    wp_add_dashboard_widget('wpcon_dashboard_widget', 'A Simple News Widget', 'wpcon_dashboard_widget_function');
}

function wpcon_dashboard_widget_function() {
    $url = 'http://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=corleycloud&count=10';
    $unpack = @json_decode(file_get_contents($url));

    $template = get_twig()->loadTemplate('tweets.twig');
    echo $template->render(array('elements' => $unpack));
}

function get_twig()
{
    static $twig;
    if (!$twig) {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/views');
        $twig = new Twig_Environment($loader, array('cache' => __DIR__ . '/cache'));
    }

    return $twig;
}
