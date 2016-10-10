<?php
/*
Plugin Name: Noxsleepdrink Plugin
Description: Plugin used to create order on Bubble Post 
Author: Asim Bilal
Version: 1
*/
add_action('admin_menu', 'noxsleepdrink_plugin_setup_menu');
 
function noxsleepdrink_plugin_setup_menu(){
        add_menu_page( 'Noxsleepdrink Plugin', 'Noxsleepdrink Plugin', 'manage_options', 'noxsleepdrink-plugin', 'noxsleepdrink_plugin_init' );
}
 
function noxsleepdrink_plugin_init(){
        echo "<h1>Hello  Noxsleepdrink Plugin!</h1>";
}
 
?>