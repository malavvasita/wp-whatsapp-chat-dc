<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dialog-chat.com
 * @since      1.0.0
 *
 * @package    Dialog_Chat
 * @subpackage Dialog_Chat/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dialog_Chat
 * @subpackage Dialog_Chat/public
 * @author     Falcon Plugins <falcon@dialog-chat.com>
 */
class Dialog_Chat_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dialog_Chat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dialog_Chat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dialog-chat-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dialog_Chat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dialog_Chat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$dc_options = get_option( "dc_admin_settings" );

		$dc_widget_icon = wp_get_attachment_image_src( $dc_options['design']['close_widget_button'], 'single-post-thumbnail' )[0] ?? "";
		$dc_close_icon 	= wp_get_attachment_image_src( $dc_options['design']['close_icon'], 'single-post-thumbnail' )[0] ?? "";

		if( ! $dc_widget_icon ){
			$dc_widget_icon = DC_WHATSAPP_WIDGET_ICON;
		}

		if( ! $dc_close_icon ){
			$dc_close_icon = DC_WHATSAPP_WIDGET_CLOSE_ICON;
		}

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dialog-chat-public.js', array( 'jquery' ), $this->version, false );
		
		wp_localize_script( $this->plugin_name, 'dc_local_data',
			array( 
				'dc_widget_icon' => $dc_widget_icon,
				'dc_close_icon' => $dc_close_icon
			)
		);

		wp_enqueue_script( $this->plugin_name );

	}

}
