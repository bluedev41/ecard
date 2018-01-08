<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
     die;
}
 // Include dependencies needed to instantiate the plugin.
require_once('class-submenu.php');
require_once('class-submenu-page.php');

add_action('plugins_loaded', 'ecard_super_light_settings');

function ecard_super_light_settings() {
    $plugin = new Submenu( new Submenu_Page() );
    $plugin->init();
}
