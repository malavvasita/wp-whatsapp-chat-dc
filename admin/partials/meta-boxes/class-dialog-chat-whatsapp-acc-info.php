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
class Dialog_Chat_Whatsapp_Acc_Info {

    public static function whatsapp_acc_info_mb() {
        ob_start();

        ?>
            <div class="dc_whatsapp_meta_box">
                <style scoped>
                    .dc_whatsapp_meta_box{
                        display: grid;
                        grid-template-columns: max-content 1fr;
                        grid-row-gap: 10px;
                        grid-column-gap: 20px;
                    }
                    .dc_whatsapp_meta{
                        display: contents;
                    }
                </style>
                <p class="meta-options dc_whatsapp_meta">
                    <label for="dc_whatsapp_account_number"><?php _e( 'WhatsApp Account Number', 'dialog-chat' ); ?></label>
                    <input id="dc_whatsapp_account_number" 
                        type="text" 
                        name="dc_whatsapp_account_number"
                        placeholder="WhatsApp Account Number"
                        required="required"
                        value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dc_whatsapp_account_number', true ) ); ?>">
                </p>
                <p class="meta-options dc_whatsapp_meta">
                    <label for="dc_whatsapp_account_name"><?php _e( 'WhatsApp Account Name or Title', 'dialog-chat' ); ?></label>
                    <input id="dc_whatsapp_account_name" 
                        type="text" 
                        name="dc_whatsapp_account_name"
                        placeholder="WhatsApp Account Name"
                        value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dc_whatsapp_account_name', true ) ); ?>">
                </p>
                <p class="meta-options dc_whatsapp_meta">
                    <label for="dc_job_post"><?php _e( 'Job Post', 'dialog-chat' ); ?></label>
                    <input 
                        id="dc_job_post" 
                        type="text"
                        name="dc_job_post"
                        placeholder="Job Post in the Organization"
                        value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dc_job_post', true ) ); ?>">
                </p>
            </div>
        <?php

        echo ob_get_clean();
    }

    public static function save_whatsapp_acc_info_mb( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( $parent_id = wp_is_post_revision( $post_id ) ) {
            $post_id = $parent_id;
        }
        $fields = [
            'dc_whatsapp_account_number',
            'dc_whatsapp_account_name',
            'dc_job_post',
            'dc_whatsapp_time',
        ];
        foreach ( $fields as $field ) {
            if ( array_key_exists( $field, $_POST ) ) {
                update_post_meta( $post_id, $field, is_array( $_POST[$field] ) ? serialize( $_POST[$field] ) : sanitize_text_field( $_POST[$field] ) );
            }
        }
    }

}
