<?php

/*
Plugin Name: Noxsleepdrink Plugin
Description: Plugin used to create order on Bubble Post 
Author: Asim Bilal
Version: 1
*/

//Wordpress Add menu hook
add_action('admin_menu', 'noxsleepdrink_plugin_setup_menu');

//Wordpress On plugin activation hook
register_activation_hook( __FILE__, 'noxsleepdrink_plugin_db_install' );

//Wordpress hook for adding Bootstrap
add_action( 'admin_enqueue_scripts', 'loadBootstrap' );


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
 
/*
 * Function Name: Noxsleepdrink Plugin Init
 * Description: Noxsleepdrink Plugin initialization function
 */

function noxsleepdrink_plugin_init(){

	$html = 
	'<div class="col-md-12">
		<h4>Filter Your Products</h4>
	</div>	
	<div class="form-group col-md-4">

      <select multiple class=" form-control" id="sel2">
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>5</option>
      </select>
    </div> 
    <div class="clearfix"></div>
    <div class="col-md-4"> <button type="button" class="btn btn-primary">Save</button> </div>
    ';
    echo $html ;
}
 
?>