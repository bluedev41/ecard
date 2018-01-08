<?php
/**
 * Creates the submenu item for the plugin.
 *
 * @package Custom_Admin_Settings
 */
 
/**
 * Create a submenu item for the admin section
 *
 * Registers a new menu item under 'Tools' and uses the dependency passed into
 * the constructor in order to display the page corresponding to this menu item.
 *
 */

class Submenu {
    private $submenu_page;

    public function __construct( $submenu_page ) {
        $this->submenu_page = $submenu_page;
    }

    public function init() {
        add_action('admin_enqueue_scripts', array($this, 'load_scripts') );
        add_action('admin_menu', array( $this, 'add_submenu_page' ) );
        add_action('admin_init', array( $this->submenu_page, 'plugin_admin_init') );
    }

    public function load_scripts() {
        wp_enqueue_media(); 
        wp_enqueue_script( 'ecard-super-light-admin-js', plugins_url('/js/wp_media_uploader.js', __FILE__), array(), '1.0' );
    }

    public function add_submenu_page() {
        add_submenu_page('options-general.php', 'Super Simple E-Card', 'E-Card', 'manage_options', 'esl-admin-menu', array($this->submenu_page,'init_admin') );
    }
}