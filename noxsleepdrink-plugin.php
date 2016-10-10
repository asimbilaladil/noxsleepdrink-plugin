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
    echo "<h1>Hello  Noxsleepdrink Plugin!</h1>";
}
 
?>