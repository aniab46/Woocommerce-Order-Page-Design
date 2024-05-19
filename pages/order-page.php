<?php
global $wpdb;

        // Query to get WooCommerce orders
        $orders = wc_get_orders(array(
            'limit' => -1, // Get all orders
            'orderby' => 'date',
            'order' => 'DESC',
            'status' => array('wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-failed'),
        ));

        if(isset( $_GET['tab'] ) && $_GET['tab'] === 'active'){
            include_once plugin_dir_path( __FILE__ ) . 'active-tab.php';
        }

        if(isset( $_GET['tab'] ) && $_GET['tab'] === 'completed'){
            include_once plugin_dir_path( __FILE__ ) . 'completed-tab.php';
        }

        if(isset( $_GET['tab'] ) && $_GET['tab'] === 'cancelled'){
            include_once plugin_dir_path( __FILE__ ) . 'cancelled-tab.php';
        }
        

        if(!isset($_GET['tab']) || $_GET['tab'] === 'all'){
            ?>
            <div class="wrap">
                <h1 class="manage-order-heading">Manage Orders</h1>
                <a href="<?php echo admin_url('admin.php?page=wc-orders&action=new') ?>" class="page-title-action" >Add Order</a>
                <nav class="nav-tab-wrapper order-button">
			        <a href="<?php echo admin_url( 'admin.php?page=new-orders&tab=all'); ?>" class="nav-tab nav-tab-active">All</a>
                    <a href="<?php echo admin_url( 'admin.php?page=new-orders&tab=active'); ?>" class="nav-tab">Active</a>
                    <a href="<?php echo admin_url( 'admin.php?page=new-orders&tab=completed'); ?>" class="nav-tab ">Completed</a>
                    <a href="<?php echo admin_url( 'admin.php?page=new-orders&tab=cancelled'); ?>" class="nav-tab ">Cancelled</a>
                    	
                </nav>
                <table class="wp-list-table widefat fixed striped table-view-list orders wc-orders-list-table wc-orders-list-table-shop_order order-table">
			        <thead>
                        <tr class="table-heading">
                            <th colspan="8">All Orders</th>
                        </tr>
	                    <tr class="second-row-order-table">
                            <th>Seller</th>
                            <th colspan="2">Product</th>
                            <th>Order</th>
                            <th>Delivered At</th>
                            <th>Total</th>
                            <th>Invoice</th>
                            <th>Status</th>
	                    </tr>	
	                </thead>

	                <tbody class="order-table-content">
                        <?php
                        foreach ($orders as $order) {
                            $order_id = $order->get_id();
                            $user_id = $order->get_user_id();
                            $user_info = get_userdata($user_id);
                            $user_name = $user_info ? $user_info->display_name : 'Guest';
                            $order_items = $order->get_items();
                            $order_date = $order->get_date_created()->date('Y-m-d H:i:s');
                            $order_total = $order->get_formatted_order_total();
                            $order_status = wc_get_order_status_name($order->get_status());
                            $invoice_url = '#'; // Replace with actual invoice URL if available
                            $image_url = plugin_dir_url(__FILE__) . 'images/invoice.png';
                            foreach( $order_items as $item ) {
                                $product_name = $item->get_name();
                            }
                            
                                ?>
                                <tr>
                                    
                                    <td class="order-user"><?php echo esc_html($user_name); ?></td>
                                    <td colspan="2" class="order-product-name"><a href="<?php echo admin_url( 'admin.php?page=wc-orders&action=edit&id='.$order_id); ?>"><?php echo esc_html($product_name); ?></a></td>
                                    <td>#<?php echo esc_html($order_id); ?></td>
                                    <td><?php echo esc_html($order_date); ?></td>
                                    <td><?php echo wp_kses_post($order_total); ?></td>
                                    <td><a href="<?php echo esc_url($invoice_url); ?>"><img src="<?php echo esc_url($image_url); ?>" alt="Invoice Image" style="width: 16px; height: 16px;" /></a></td>
                                    <?php 
                                        if($order_status=='Completed'){
                                            echo "<td class='completed-status'><span>Completed</span></td>";
                                        }
                                        if( $order_status == "On hold" || $order_status == "Processing"){
                                            echo "<td class='active-status'><span>Active</span></td>";
                                        }
                                        if( $order_status == "Cancelled"){
                                            echo "<td class='cancelled-status'><span>Cancelled</span></td>";
                                        }
                                    ?>
                                    
                                    
                                </tr>
                                <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>    
            <?php   
        }
        ?>
         