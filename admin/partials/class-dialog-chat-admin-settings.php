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
class Dialog_Chat_Admin_Settings {

    public function __construct() {
        wp_enqueue_media();
    }

    public function dc_whatsapp_settigngs_page_render() {

        if( !empty( $_POST['dc_admin_settings'] ) ){
            if( update_option( 'dc_admin_settings', $_POST['dc_admin_settings'], true ) ) {
                echo '<div class="notice notice-success is-dismissible">
                    <p>' . __( 'Settings saved successfully!!', 'dialog-chat' ) . '</p>
                </div>';
            }
        }

        $dc_admin_settings = get_option( 'dc_admin_settings' );

        $default_image          = DC_PLUGIN_URL . "includes/assets/icons/whatsapp-icon.svg";
        $default_close_image    = DC_PLUGIN_URL . "includes/assets/icons/close-icon.png";

        $src        = ( ! empty( $dc_admin_settings['design']['close_widget_button'] ) ) ? wp_get_attachment_image_src( $dc_admin_settings['design']['close_widget_button'], 'full' )[0] : "" ;
        $close_src  = ( ! empty( $dc_admin_settings['design']['close_icon'] ) ) ? wp_get_attachment_image_src( $dc_admin_settings['design']['close_icon'], 'full' )[0] : "" ;

        if( empty( $src ) ) {
            $src = $default_image;
        }

        if( empty( $close_src ) ) {
            $close_src = $default_close_image;
        }

        ob_start();
        ?>
        
        <div class="wrap">
            <h2><?php _e( "Settings > Dialog Chat", "dialog-chat" ); ?></h2>
            <div class="dc_texts_icons_settings_wrap">
                <div class="dc_settings_tabs">
                    <ul>
                        <li class="dc_setting_tab active" data-tab="dc-text-settings">
                            <img src="<?php echo DC_PLUGIN_URL . "includes/assets/icons/admin-text-settings.png" ?>" alt="Admin Text Settings" class="image"/>
                            <?php _e( "Text Settings", "dialog-chat" ); ?>
                        </li>
                        <li class="dc_setting_tab" data-tab="dc-design-settings">
                            <img src="<?php echo DC_PLUGIN_URL . "includes/assets/icons/admin-design-settings.png" ?>" alt="Admin Design Settings" class="image">
                            <?php _e( "Design Settings", "dialog-chat" ); ?>
                        </li>
                    </ul>
                </div>
                <div class="dc_settings_playarea">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="dc_admin_settings dc_text_settings" id="dc-text-settings">
                            <h3><strong><?php _e( "Text Settings - Dialog Chat", "dialog-chat" ); ?></strong></h3>
                                <table>
                                    <tr>
                                        <td><strong><?php _e( "Closed Widget Text", "dialog-chat" ); ?></strong></td>
                                        <td><input type="text" name="dc_admin_settings[text][close_title]" value="<?php echo $dc_admin_settings['text']['close_title'] ?? ""; ?>" /></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php _e( "Widget Title", "dialog-chat" ); ?></strong></td>
                                        <td><input type="text" name="dc_admin_settings[text][widget_title]" value="<?php echo $dc_admin_settings['text']['widget_title'] ?? ""; ?>" /></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php _e( "Widget Description", "dialog-chat" ); ?></strong></td>
                                        <td>
                                            <?php
                                                $settings = array(
                                                    'teeny' => true,
                                                    'textarea_rows' => 5,
                                                    'tabindex' => 1,
                                                    'media_buttons' => false
                                                );
                                                wp_editor(
                                                    $dc_admin_settings['text']['widget_description'] ?? "",
                                                    'dc_admin_settings[text][widget_description]', 
                                                    $settings
                                                );
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><?php _e( "Response Time Text", "dialog-chat" ); ?></strong></td>
                                        <td>
                                            <?php
                                                $settings = array(
                                                    'teeny' => true,
                                                    'textarea_rows' => 5,
                                                    'tabindex' => 1,
                                                    'media_buttons' => false
                                                );
                                                wp_editor(
                                                    $dc_admin_settings['text']['response_time_text'] ?? "",
                                                    'dc_admin_settings[text][response_time_text]', 
                                                    $settings
                                                );
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                <?php
                                    wp_nonce_field( 'dc_text_settings_nonce', 'dc_text_settings_nonce_field' );
                                    submit_button( 'Save Text Settings', 'primary', 'submit', true );
                                ?>
                        </div>
                        <div class="dc_admin_settings dc_design_settings hidden" id="dc-design-settings">
                            <h3><strong><?php _e( "Design Settings - Dialog Chat", "dialog-chat" ); ?></strong></h3>
                            <table>
                                <tr>
                                    <td><strong><?php _e( "Widget Button", "dialog-chat" ); ?></strong></td>
                                    <td>
                                    <div class="upload" style="max-width:100px;">
                                        <img data-src="<?php echo $default_image; ?>" src="<?php echo $src; ?>" style="max-width:25%; height:auto; padding: 1em; background-color: <?php echo $dc_admin_settings['design']['close_button_bg_color'] ?? "#000" ?>" />
                                        <div>
                                            <input type="hidden" name="dc_admin_settings[design][close_widget_button]" id="dc_admin_settings[design][close_widget_button]" value="<?php echo $dc_admin_settings['design']['close_widget_button'] ?? ""; ?>" />
                                            <button type="submit" class="upload_image_button button"><?php _e('Upload', 'igsosd'); ?></button>
                                            <button type="submit" class="remove_image_button button">&times;</button>
                                        </div>
                                    </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong><?php _e( "Close Icon", "dialog-chat" ); ?></strong></td>
                                    <td>
                                    <div class="upload-close-icon" style="max-width:100px;">
                                        <img data-src="<?php echo $default_close_image; ?>" src="<?php echo $close_src; ?>" style="max-width:25%; height:auto; padding: 1em; background-color: <?php echo $dc_admin_settings['design']['close_button_bg_color'] ?? "#000" ?>" />
                                        <div>
                                            <input type="hidden" name="dc_admin_settings[design][close_icon]" id="dc_admin_settings[design][close_icon]" value="<?php echo $dc_admin_settings['design']['close_icon'] ?? ""; ?>" />
                                            <button type="submit" class="upload_image_button button"><?php _e('Upload', 'igsosd'); ?></button>
                                            <button type="submit" class="remove_image_button button">&times;</button>
                                        </div>
                                    </div>
                                    </td>
                                </tr>

                                <tr class="dc_separate_table">
                                    <td><strong><?php _e( "Closed Widget Button Background Color", "dialog-chat" ); ?></strong></td>
                                    <td><input type="text" class="dc-color-picker" name="dc_admin_settings[design][close_button_bg_color]" value="<?php echo $dc_admin_settings['design']['close_button_bg_color'] ?? ""; ?>" /></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e( "Closed Widget Text Size", "dialog-chat" ); ?></strong></td>
                                    <td class="dc_admin_settings_size_input"><input type="number" name="dc_admin_settings[design][close_title_size]" value="<?php echo $dc_admin_settings['design']['close_title_size'] ?? ""; ?>" /></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e( "Closed Widget Text Color", "dialog-chat" ); ?></strong></td>
                                    <td><input type="text" class="dc-color-picker" name="dc_admin_settings[design][close_title_color]" value="<?php echo $dc_admin_settings['design']['close_title_color'] ?? ""; ?>" /></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e( "Closed Widget Text Background Color", "dialog-chat" ); ?></strong></td>
                                    <td><input type="text" class="dc-color-picker" name="dc_admin_settings[design][close_title_bg_color]" value="<?php echo $dc_admin_settings['design']['close_title_bg_color'] ?? ""; ?>" /></td>
                                </tr>
                                <tr></tr>
                                <tr class="dc_separate_table">
                                    <td><strong><?php _e( "Widget Title Text Size", "dialog-chat" ); ?></strong></td>
                                    <td class="dc_admin_settings_size_input"><input type="number" name="dc_admin_settings[design][widget_title_size]" value="<?php echo $dc_admin_settings['design']['widget_title_size'] ?? ""; ?>" /></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e( "Widget Title Text Color", "dialog-chat" ); ?></strong></td>
                                    <td><input type="text" class="dc-color-picker" name="dc_admin_settings[design][widget_title_color]" value="<?php echo $dc_admin_settings['design']['widget_title_color'] ?? ""; ?>" /></td>
                                </tr>
                            </table>
                            <?php
                                wp_nonce_field( 'dc_design_settings_nonce', 'dc_design_settings_nonce_field' );
                                submit_button( 'Save Design Settings', 'primary', 'submit', true );
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php

    }

}
