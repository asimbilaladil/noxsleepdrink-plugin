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
    print_r($_POST);
} 

/*
 * Function Name: Noxsleepdrink  Plugin View
 * Description: Create Noxsleepdrink Plugin HTML view function
 */
function noxsleepdrink_plugin_view () {

    //Get products data array 
    $products = get_products();
    
    $html = '<form action="'. get_admin_url() .'admin-post.php" method="POST">';
    $html = $html . '<div class="col-md-12"> <h4>Filter Your Products</h4> </div> <div class="form-group col-md-4">';
    $html = $html . '<table class="table"> <tr> <td> Product </td> <td> Action </td> </tr>';

    foreach ($products as $product) {
        $html = $html . ' <tr> <td> '. $product['product_title'] .' </td>'; 
        $html = $html . '<td> <input type="checkbox" value="'. $product['product_id'] .'" name="product_'. $product['product_id'] .'"> Add </input> </td> </tr>';
    }

    $html = $html . '</table>
        </div> 
        <div class="clearfix"></div>
        <div class="col-md-4"> <input type="submit" class="btn btn-primary" value="Save"/> </div>
        <input name="action" type="hidden" value="products">

        </form>';

    return  $html ;

}
?> 