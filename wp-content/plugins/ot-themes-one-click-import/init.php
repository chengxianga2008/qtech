<?php
	/**
 	* Plugin Name: OT One Click Import Demo
 	* Plugin URI: http://www.oceanthemes.net/
 	* Description: One click import demo content
 	* Version: 1.0
 	* Author: OceanThemes
 	* Author URI: http://www.oceanthemes.net/
 	* License: GPL2
 	*/
 	
require_once(  dirname( __FILE__ ) .'/importer/radium-importer.php' ); //load admin theme data importer
class Radium_Theme_Demo_Data_Importer extends Radium_Theme_Importer {
    /**
     * Holds a copy of the object for easy reference.
     *
     * @since 0.0.1
     *
     * @var object
     */
    private static $instance;
    
    /**
     * Set the key to be used to store theme options
     *
     * @since 0.0.2
     *
     * @var object
     */
    public $theme_option_name = 'theme_option'; //set theme options name here - Davis change
		
	public $theme_options_file_name = 'theme_options.json';
	
	public $widgets_file_name 		=  'widgets.json';
	
	public $content_demo_file_name  =  'content.xml';
	
	/**
	 * Holds a copy of the widget settings 
	 *
	 * @since 0.0.2
	 *
	 * @var object
	 */
	public $widget_import_results;
	
    /**
     * Constructor. Hooks all interactions to initialize the class.
     *
     * @since 0.0.1
     */
    public function __construct() {
		$this->demo_files_path = dirname(__FILE__) . '/demo-files/';
        self::$instance = $this;
		parent::__construct();
    }
	
	/**
	 * Add menus
	 *
	 * @since 0.0.1
	 */
	public function set_demo_menus() {
		// Menus to Import and assign - you can remove or add as many as you want
		$main_menu = get_term_by('name', 'Main Menu', 'nav_menu'); // - Davis change				
		set_theme_mod( 'nav_menu_locations', array(
                'primary' => $main_menu->term_id, // - Davis change				                
            )
        );

	}
	
	/**
	 * Set Home Page - Show home page on frontend
	 *
	 * @since 0.0.1
	 */
	public function set_demo_home(){
		$page = get_page_by_title( 'Home' ); // Edit home page name you want to set here. - Davis change
		if ( isset( $page->ID ) ) {
			update_option( 'page_on_front', $page->ID );
			update_option( 'show_on_front', 'page' );
		}								
		$this->flag_as_imported['home'] = true;
	}	
}
new Radium_Theme_Demo_Data_Importer;
?>