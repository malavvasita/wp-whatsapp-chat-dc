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
class Dialog_Chat_Whatsapp_Acc_Time {

    public static function whatsapp_acc_time_mb() {

        $dc_days_list = array(
            __( "sunday", "dialog-chat" ),
            __( "monday", "dialog-chat" ),
            __( "tuesday", "dialog-chat" ),
            __( "wednesday", "dialog-chat" ),
            __( "thursday", "dialog-chat" ),
            __( "friday", "dialog-chat" ),
            __( "saturday", "dialog-chat" ),
        );

        $dc_time_val = unserialize( get_post_meta( get_the_ID(), 'dc_whatsapp_time', true ) );

        ob_start();

        ?>
            <div class="dc_whatsapp_meta_box_time">

                <p class="meta-options dc_always_online">
                    <label for="dc_whatsapp_time[always_online]"><?php _e( 'Always Show Online', 'dialog-chat' ); ?></label>
                    <input 
                        id="dc_whatsapp_time[always_online]"
                        class="dc_always_online_checkbox"
                        type="checkbox" 
                        name="dc_whatsapp_time[always_online]"
                        <?php echo ( ! empty( $dc_time_val['always_online'] ) || ! isset( $dc_time_val['always_online'] ) ) ? "checked='checked'" : ""; ?>>
                </p>
                
                <?php
                    foreach( $dc_days_list as $day ) {
                    ?>
                        <p class="meta-options dc_whatsapp_meta_time" style="display: none;">
                            <label for="dc_whatsapp_time_<?php echo $day; ?>"><?php echo ucfirst( $day ); ?></label>
                            <span>From</span>
                            <input id="dc_whatsapp_time_<?php echo $day; ?>" 
                                type="time" 
                                name="dc_whatsapp_time[<?php echo $day; ?>][from]"
                                value="<?php echo $dc_time_val[$day]['from'] ?>">
                            <span>To</span>
                            <input 
                                type="time" 
                                name="dc_whatsapp_time[<?php echo $day; ?>][to]"
                                value="<?php echo $dc_time_val[$day]['to'] ?>">
                        </p>
                    <?php
                    }
                ?>
            </div>
        <?php

        echo ob_get_clean();
    }

    public static function save_whatsapp_acc_time_mb( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( $parent_id = wp_is_post_revision( $post_id ) ) {
            $post_id = $parent_id;
        }
        $fields = [
            'dc_whatsapp_account_number',
            'dc_whatsapp_account_name',
            'dc_job_post',
        ];
        foreach ( $fields as $field ) {
            if ( array_key_exists( $field, $_POST ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
            }
        }
    }

}
