<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://dialog-chat.com
 * @since      1.0.0
 *
 * @package    Dialog_Chat
 * @subpackage Dialog_Chat/public/partials
 */

 add_action( 'init', 'show_data' );

 function show_data() {

    global $wpdb;

    $dc_options = get_option( "dc_admin_settings" );

    $dc_whatsapp_accounts = get_posts([
        'post_type' => 'dialog-chat',
        'post_status' => 'publish',
        'numberposts' => -1
    ]);

    if( ! current_user_can( 'manage_options' ) && empty( $dc_whatsapp_accounts ) ){
        return;
    }

    $dc_widget_icon = wp_get_attachment_image_src( $dc_options['design']['close_widget_button'], 'thumbnail' )[0] ?? DC_WHATSAPP_WIDGET_ICON;

    ob_start();

    ?>
        <div class="dc_whatsapp_chat_wrap">
            <style scoped>
                .dc_chat_popup__text {
                    background-color: <?php echo $dc_options['design']['close_title_bg_color'] ?>;
                }

                .dc_chat_popup__text span{
                    color: <?php echo $dc_options['design']['close_title_color'] ?>;
                    font-size: <?php echo $dc_options['design']['close_title_size'] ?>px;
                }

                .dc_chat_popup__button,
                .dc_chat__title {
                    background-color: <?php echo $dc_options['design']['close_button_bg_color']; ?>;
                }

                .dc_chat__head_text {
                    font-size: <?php echo $dc_options['design']['widget_title_size']; ?>px;
                }

                .dc_chat__head_texts {
                    color: <?php echo $dc_options['design']['widget_title_color']; ?>
                }

                .dc_chat__head_response_time {
                    color: <?php echo $dc_options['design']['close_title_color']; ?>
                }

                .dc_account_link_tile {
                    border-left: 5px solid <?php echo $dc_options['design']['close_button_bg_color']; ?>;
                }

            </style>
            
            <div class="dc_chat_popup_button" style="display: none;">
                <div class="dc_chat_popup__text">
                    <span><?php echo $dc_options['text']['close_title']; ?></span>
                </div>
                <div class="dc_chat_popup__button">
                    <img data-src="<?php echo $dc_widget_icon; ?>" src ="<?php echo $dc_widget_icon; ?>" />
                </div>
            </div>

            <div class="dc_chat_popup" style="display: none;">
                <div class="dc_chat__title">
                    <div class="dc_chat__brand">
                        <img data-src="<?php echo $dc_widget_icon; ?>" src ="<?php echo $dc_widget_icon; ?>" />
                    </div>
                    <div class="dc_chat__head_texts">
                        <div class="dc_chat__head_text"><?php echo $dc_options['text']['widget_title']; ?></div>
                        <p class="dc_chat__head_desc"><?php echo $dc_options['text']['widget_description']; ?></p>
                    </div>
                </div>
                <div class="dc_chat__accounts_wrap">
                    <div class="dc_chat__head_response_time">
                        <p class="dc_chat__head_response_text"><?php echo $dc_options['text']['response_time_text']; ?></p>
                        <div class="dc_chat__accounts">

                            <?php 
                                if( empty( $dc_whatsapp_accounts ) ){
                                    echo "<p class='dc_no_accounts'>";
                                    _e( "<b>Oops!</b> No accounts found. Please add some accounts <a target='_blank' href='" . admin_url( "edit.php?post_type=dialog-chat" ) . "'>here</a>", "dialog-chat" );
                                    echo "</p>";
                                }
                            ?>
                            
                            <?php foreach( $dc_whatsapp_accounts as $dc_whatsapp_account  ){ 
                                $dc_account_name            = get_post_meta( $dc_whatsapp_account->ID, "dc_whatsapp_account_name", true );
                                $dc_account_job_post        = get_post_meta( $dc_whatsapp_account->ID, "dc_job_post", true );
                                $dc_account_number          = get_post_meta( $dc_whatsapp_account->ID, "dc_whatsapp_account_number", true );
                                $dc_account_avatar          = wp_get_attachment_image_src( get_post_thumbnail_id( $dc_whatsapp_account->ID ), 'single-post-thumbnail' )[0] ?? "";
                                $dc_account_availability    = unserialize( get_post_meta( $dc_whatsapp_account->ID, "dc_whatsapp_time", true ) );

                                if( $dc_account_availability ){
                                    $always_online = ( ! empty( $dc_account_availability["always_online"] ) ) ? "yes" : "no";

                                    $status = "online";

                                    if( "no" === $always_online ){
                                        
                                        $current_day = date('l');

                                        if( !empty( $current_day_availability = $dc_account_availability[ strtolower( $current_day ) ] ) ){

                                            $current_time       = strtotime( date( "H:i" ) );
                                            $availability_from  = strtotime( $current_day_availability['from'] );
                                            $availability_to    = strtotime(  $current_day_availability['to'] );

                                            if( $current_time < $availability_from || $current_time > $availability_to ){
                                                $status = "offline";
                                            }

                                        }

                                    }

                                }

                            ?>
                            <div class="dc_chat__account">
                                <a class="dc_account_link_tile <?php echo $status; ?>" target="_blank" href="<?php echo ( "offline" === $status ) ? "#" : DC_WHATSAPP_API_URL . $dc_account_number; ?>">
                                    <div class="dc_chat__account_info">
                                        <p class="dc_chat__account_title"><?php echo $dc_account_name; ?><span class="dc_offline_tag"><?php _e( "offline", "dialog-chat" ) ?></span></p>
                                        <p class="dc_chat__account_position"><?php echo $dc_account_job_post; ?></p>
                                    </div>
                                    <div class="dc_chat__account_avatar">
                                        <img 
                                            src="<?php 
                                                echo !empty( $dc_account_avatar) ? $dc_account_avatar : DC_WHATSAPP_WIDGET_ICON; 
                                                ?>" />
                                    </div>
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php

    echo ob_get_clean();

 }

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
