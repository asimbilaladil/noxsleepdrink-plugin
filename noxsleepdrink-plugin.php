<?php

/*
Plugin Name: Noxsleepdrink Plugin
Description: Plugin used to create order on Bubble Post 
Author: Asim Bilal
Version: 1
*/
require('function.php');

//Wordpress Add menu hook
add_action('admin_menu', 'noxsleepdrink_plugin_setup_menu');

//Wordpress On plugin activation hook
register_activation_hook( __FILE__, 'noxsleepdrink_plugin_db_install' );

//Wordpress hook for adding Bootstrap
add_action( 'admin_enqueue_scripts', 'loadBootstrap' );




/*
 * Function Name: Noxsleepdrink Plugin Init
 * Description: Noxsleepdrink Plugin initialization function
 */

function noxsleepdrink_plugin_init(){
	
	$html_view = noxsleepdrink_plugin_view();
	echo $html_view;
    
}
 
?>