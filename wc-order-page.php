<?php
/*
Plugin Name: WooCommerce Order Page
Plugin URI: https://github.com/aniab46/Woocommerce-Order-Page-Design
Description: A plugin for event manageent calendar using OOP approach.
Author: Your Muhammad Aniab
version: 1.0
Author URI: https://muhammadaniab.com/
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
        $new_orders_count = $this->get_new_orders_count();
        $submenu_title = 'New Orders';
        if ($new_orders_count > 0) {
            $submenu_title .= sprintf(' <span class="update-plugins count-%d"><span class="plugin-count">%d</span></span>', $new_orders_count, $new_orders_count);
        }
        add_submenu_page("woocommerce","Woocommerce Orders", $submenu_title ,"manage_options","new-orders", array( $this,"orders_page"), '2' );
    }

    public function get_new_orders_count(){
        $args = array(
            'limit' => -1, // Get all orders
            'status' => 'wc-pending',
        );
        $orders = wc_get_orders($args);
        return count($orders);
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