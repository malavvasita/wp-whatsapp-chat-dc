<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dialog-chat.com
 * @since      1.0.0
 *
 * @package    Dialog_Chat
 * @subpackage Dialog_Chat/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dialog_Chat
 * @subpackage Dialog_Chat/admin
 * @author     Falcon Plugins <falcon@dialog-chat.com>
 */
class Dialog_Chat_Admin_Main {

	public function __construct() {
        global $typenow;

        add_action( 'init', array( $this, 'custom_post_type' ), 0 );
        add_action('admin_menu', array( $this, 'dc_settings_page') );

        if( is_admin() && 
            ( 
                ( isset( $_REQUEST['post'] ) && "dialog-chat" === get_post_type( $_REQUEST['post'] ) ) ||
                ( isset( $_REQUEST['post_type'] ) && "dialog-chat" === $_REQUEST['post_type'] )
            )
        ){
            
            require_once( plugin_dir_path( __FILE__ ) . 'meta-boxes/class-dialog-chat-whatsapp-acc-info.php' );
            require_once( plugin_dir_path( __FILE__ ) . 'meta-boxes/class-dialog-chat-whatsapp-acc-time.php' );
            
            add_action( 'add_meta_boxes', array( $this, 'dc_register_meta_boxes' ) );
            
            add_action( 'save_post_dialog-chat', array( 'Dialog_Chat_Whatsapp_Acc_Info', 'save_whatsapp_acc_info_mb' ), 10, 999 );

            add_filter('manage_dialog-chat_posts_columns', function($columns) {
                return array_merge($columns, [
                            'account_name'      => __('Account Name', 'dialog-chat'),
                            'account_number'    => __('Account Number', 'dialog-chat'),
                            'job_post'          => __('Job Post', 'dialog-chat'),
                            'online'            => __('Always Online', 'dialog-chat'),
                            'avatar'            => __('Contact Image', 'dialog-chat'),
                        ]
                    );
            });
    
            add_action('manage_dialog-chat_posts_custom_column', function($column_key, $post_id) {
                
                if ($column_key == 'account_name') {
                    $account_name = get_post_meta($post_id, 'dc_whatsapp_account_name', true);
                    if ($account_name) {
                        echo $account_name;
                    }
                }

                if ($column_key == 'account_number') {
                    $account_number = get_post_meta($post_id, 'dc_whatsapp_account_number', true);
                    if ($account_number) {
                        echo $account_number;
                    }
                }

                if ($column_key == 'job_post') {
                    $job_post = get_post_meta($post_id, 'dc_job_post', true);
                    if ($job_post) {
                        echo $job_post;
                    }
                }

                if ($column_key == 'avatar') {
                    $avatar = wp_get_attachment_url( get_post_thumbnail_id($post_id), 'thumbnail' );
                    if ($avatar) {
                        echo "<img class='dialog-chat-list-avatar' src='" . $avatar . "' alt='avatar_" . $post_id . "' />";
                    }
                }

                if ($column_key == 'online') {

                    $online = unserialize( get_post_meta($post_id, 'dc_whatsapp_time', true) );
                    if ($online) {
                        echo ( ! empty( $online['always_online'] ) ? __( "YES", "dialog-chat" ) : __( "NO", "dialog-chat" ) );
                    }
                }

            }, 10, 2);

            add_filter('manage_dialog-chat_posts_columns', function($columns) {
                $taken_out = $columns['date'];
                unset($columns['date']);
                $columns['date'] = $taken_out;
                return $columns;
            });

        }

	}

    function dc_settings_page() {
        add_submenu_page( 
            'edit.php?post_type=dialog-chat', 
            'Settings - Dialog Chat', 
            'Settings', 
            'manage_options', 
            'dialog-chat-settings', 
            array( $this, 'dialog_chat_settings_page_callback' ) 
        ); 
    }

    function dialog_chat_settings_page_callback() {

        require_once( plugin_dir_path( __FILE__ ) . 'class-dialog-chat-admin-settings.php' );
        $dc_admin_settings = new Dialog_Chat_Admin_Settings();

        $dc_admin_settings->dc_whatsapp_settigngs_page_render();

    }

    public function dc_register_meta_boxes() {
        add_meta_box( 'dc-acc-info', __( 'WhatsApp Account Details', 'dialog-chat' ), array( $this, 'dc_whatsapp_info_callback' ), 'dialog-chat' );
        add_meta_box( 'dc-acc-time', __( 'Active Time Details', 'dialog-chat' ), array( $this, 'dc_whatsapp_time_callback' ), 'dialog-chat' );
    }

    public function dc_whatsapp_info_callback( $post ) {

        $dc_whatsapp_account_mb = new Dialog_Chat_Whatsapp_Acc_Info();
        $dc_whatsapp_account_mb->whatsapp_acc_info_mb();

    }

    public function dc_whatsapp_time_callback( $post ) {
        
        $dc_whatsapp_time_mb = new Dialog_Chat_Whatsapp_Acc_Time();
        $dc_whatsapp_time_mb->whatsapp_acc_time_mb();

    }

    /*
    * Creating a function to create our CPT
    */
  
    public function custom_post_type() {
  
        // Set UI labels for Custom Post Type
        $labels = array(
            'name'                => _x( 'DialogChat', 'Post Type General Name', 'dialog-chat' ),
            'singular_name'       => _x( 'DialogChat', 'Post Type Singular Name', 'dialog-chat' ),
            'menu_name'           => __( 'DialogChat', 'dialog-chat' ),
            'parent_item_colon'   => __( 'Parent Contact', 'dialog-chat' ),
            'all_items'           => __( 'All Contacts', 'dialog-chat' ),
            'view_item'           => __( 'View Contact', 'dialog-chat' ),
            'add_new_item'        => __( 'Add New Contact', 'dialog-chat' ),
            'add_new'             => __( 'Add New Contact', 'dialog-chat' ),
            'edit_item'           => __( 'Edit Contact', 'dialog-chat' ),
            'update_item'         => __( 'Update Contact', 'dialog-chat' ),
            'search_items'        => __( 'Search Contact', 'dialog-chat' ),
            'not_found'           => __( 'Not Found', 'dialog-chat' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'dialog-chat' ),
            'featured_image'        => __( 'Contact Image', 'dialog-chat' ),
            'set_featured_image'    => __( 'Set contact image', 'dialog-chat' ),
            'remove_featured_image' => _x( 'Remove contact image', 'dialog-chat' ),
            'use_featured_image'    => _x( 'Use as contact image', 'dialog-chat' ),
        );
          
        // Set other options for Custom Post Type
          
        $args = array(
            'label'               => __( 'dialog-chat', 'dialog-chat' ),
            'description'         => __( 'Enable WhatsApp Chat for WordPress', 'dialog-chat' ),
            'labels'              => $labels,
            // Features this CPT supports in Post Editor
            'supports'            => array( 'title', 'thumbnail',  'revisions' ),
            // You can associate this CPT with a taxonomy or custom taxonomy. 
            'taxonomies'          => array( 'genres' ),
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'hierarchical'        => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'menu_icon'           => 'dashicons-format-status',
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest' => true,
      
        );
          
        // Registering your Custom Post Type
        register_post_type( 'dialog-chat', $args );
      
    }

}
