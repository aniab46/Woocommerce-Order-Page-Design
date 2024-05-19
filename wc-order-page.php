<?php
/*
Plugin Name: WooCommerce Order Page
Plugin URI: https://example.com/custom-database-plugin
Description: A plugin for event manageent calendar using OOP approach.
Author: Your Muhammad Aniab
version: 1.0
Author URI: http://muhammadaniab.com/
*/

class Wc_order_page{
    public function __construct(){
        add_action("init", array( $this,"init") );
    }

    public function init(){
        add_action("admin_menu", array( $this,"create_wc_submenu"),99 );
        add_action("admin_enqueue_scripts", array( $this,"style_asset") );
    }

    public function create_wc_submenu(){
        add_submenu_page("woocommerce","Woocommerce Orders","New Orders","manage_options","new-orders", array( $this,"orders_page") );
    }

    public function orders_page(){
        include_once plugin_dir_path( __FILE__ ) . 'pages/order-page.php';
    }
    public function style_asset(){
        $plugin_dir= plugin_dir_url( __FILE__ );
        wp_enqueue_style('css_handle',$plugin_dir."assets/style.css", array(), '1.0');
    }
}

new Wc_order_page();