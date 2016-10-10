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
		product_name text NOT NULL,
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

 
function get_title() {
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
 * Function Name: Noxsleepdrink  Plugin View
 * Description: Create Noxsleepdrink Plugin HTML view function
 */

function noxsleepdrink_plugin_view () {

	//Get products data array 
	$products = get_title();
	
	$html = '<div class="col-md-12">
		<h4>Filter Your Products</h4>
	</div>	
	<div class="form-group col-md-4">

      <select multiple class=" form-control" id="sel2">' ;

    foreach ($products as $key => $item) {

        	$html_option = $html_option . '<option value=" '.$item['product_id']  .'">'. $item['product_title'] .'</option>';
        }    
    

    $html = $html .$html_option .  '</select>
    </div> 
    <div class="clearfix"></div>
    <div class="col-md-4"> <button type="button" class="btn btn-primary">Save</button> </div>
    ';

    return  $html ;

}

?> 