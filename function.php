<?php

/*
 * Function Name: loadBootstrap
 * Description: Used to load bootstrap classes for view
 */

function loadBootstrap() {
    wp_register_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js' );
    wp_register_style( 'bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css' );
    wp_enqueue_script( 'bootstrap-js' );
    wp_enqueue_style( 'bootstrap-css' );
}

global $jal_db_version;
$jal_db_version = '1.0';

/*
 * Function Name: Noxsleepdrink Plugin db Install
 * Description: Create table for products records
 */

function noxsleepdrink_plugin_db_install() {

    global $wpdb;
    global $jal_db_version;

    $table_name = $wpdb->prefix . 'productRecords';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        product_id int NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'jal_db_version', $jal_db_version );

}

/*
 * Function Name: Noxsleepdrink Plugin Setup Menu
 * Description: Setup menu item on wordpress admin panel
 */
 
function noxsleepdrink_plugin_setup_menu(){
    add_menu_page( 'Noxsleepdrink Plugin', 'Noxsleepdrink Plugin', 'manage_options', 'noxsleepdrink-plugin', 'noxsleepdrink_plugin_init' );
}

/*
 * Function Name: Get Products
 * Description: get all woo commerce products and id 
 */
 
function get_products() {
    $products = array();
    $args = array( 'post_type' => 'product' );

    $loop = new WP_Query( $args );

    while ( $loop->have_posts() ) : $loop->the_post(); 
    global $product; 

    $data['product_id'] = $product->id;
    $data['product_title'] = get_the_title();

    array_push($products , $data);
    endwhile; 
    wp_reset_query();   

    return $products;
}

/*
 * Function Name: Post Product
 * Description: get forms parameters on admin-post page
 */

function post_product(){

    global $wpdb;

    if (isset($_POST['product'])) {

        $wpdb->query('TRUNCATE TABLE wp_productrecords');

        $products = $_POST['product'];

        foreach ($products as $product) {
           $wpdb->insert( 'wp_productrecords', array( 'product_id' => $product ));
        }
    
    }
    
    wp_redirect( admin_url( '?page=noxsleepdrink-plugin' ));

}

/*
 * Function Name: Get Selected Product
 * Description: Get selected products from productrecord table
 */
function getSelectedProduct() {

    global $wpdb;

    $products = array();
    $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."productrecords");
    foreach ($results as $item) {
        array_push($products, $item->product_id);
    }

    return $products;
}

/*
 * Function Name: Order Notify
 * Description: Get order details from order id
 */

function order_notify($order_id){

    global $wpdb;
    $order = new WC_Order( $order_id );
    $items = $order->get_items();

    foreach ( $items as $item ) {

        $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."productrecords where product_id=" . $item['product_id']);

        //check if product exist in filter
        if ($results) {
            curlRequest($order, $item);
        }

    } 
}

/*
 * Function Name: Noxsleepdrink  Plugin View
 * Description: Create Noxsleepdrink Plugin HTML view function
 */
function noxsleepdrink_plugin_view () {

    $selectedProducts = getSelectedProduct();

    //Get products data array 
    $products = get_products();
    
    $html = '<form action="'. get_admin_url() .'admin-post.php" method="POST">';
    $html = $html . '<div class="col-md-12"> <h4>Filter Your Products</h4> </div> <div class="form-group col-md-4">';
    $html = $html . '<table class="table"> <tr> <td> Product </td> <td> Action </td> </tr>';

    foreach ($products as $product) {
        $html = $html . ' <tr> <td> '. $product['product_title'] .' </td>'; 
        $html = $html . '<td> <input type="checkbox" value="'. $product['product_id'] .'" name="product[]" '. (in_array($product['product_id'], $selectedProducts) ? 'checked' : '') .'> Add </input> </td> </tr>';
    }

    $html = $html . '</table>
        </div> 
        <div class="clearfix"></div>
        <div class="col-md-4"> <input type="submit" class="btn btn-primary" value="Save"/> </div>
        <input name="action" type="hidden" value="products">

        </form>';

    return  $html ;

}

/**
 * Curl request to API
 * @param $order 
 * @param $item
 */
function curlRequest($order, $item) {

    $first_name = $order->billing_first_name;
    $last_name = $order->billing_last_name;
    $email = $order->billing_email;
    $postcode = $order->billing_postcode;
    $phone = $order->billing_phone;
    $street = $order->shipping_address_1 . " " . $order->shipping_address_2;
    $country = $order->shipping_country;
    $state = $order->shipping_state;
    $city = $order->shipping_city;
    $phone = $order->billing_phone;
    $comment = $order->customer_note;

    $data = array (
      'type' => 'drop',
      'comment' => '',
      'tracking_number' => '',
      'reference' => '',
      'due_dates' => 
      array (
        0 => 
        array (
          'date' => '2015-05-14'
        )
      ),
      'items' => 
      array (
        0 => 
        array (
          'name' => $item['name'],
          'count' => $item['qty']
        )
      ),
      'recipient' => 
      array (
        'name' => $first_name . " " . $last_name,
        'street' => $street ,
        'city' => $city,
        'zip' => $postcode,
        'country' => $country,
        'phone' => $phone,
        'email' => $email,
      ),
    );

    $data_string = json_encode($data);

    error_log($data_string);

    //php Curl Request to post data to CRM                                                                                                                                                                                           
    $curlRequest = curl_init('https://staging.bubblepost.be/v0.2/deliveries');                                                                      
    curl_setopt($curlRequest, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($curlRequest, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($curlRequest, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Authorization:' . 'EuedpwF4MujB6OKhJugKYyyUhmWoUG915eUzPn77cQEEVQBHtL'                                                          
    ));                                                                                                                   

    // Post API Response                                                                                                                     
    $result = curl_exec($curlRequest);

    error_log(json_encode($result)) ;

}

?> 