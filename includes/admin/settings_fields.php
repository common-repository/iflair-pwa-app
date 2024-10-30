<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) { die(); }
// Get all options values
$ifpwap_app_title = get_option('ifpwap_app_title');
$ifpwap_app_short_title = get_option('ifpwap_app_short_title');
$ifpwap_description = get_option('ifpwap_description');
$ifpwap_app_icon_id = get_option('ifpwap_app_icon');
$ifpwap_monocrome_icon_id = get_option('ifpwap_monocrome_icon');
$ifpwap_space_screen_icon_id = get_option('ifpwap_space_screen_icon');
$ifpwap_background_color = get_option('ifpwap_background_color');
$ifpwap_theme_color = get_option('ifpwap_theme_color');
$ifpwap_select_start_page = get_option('ifpwap_select_start_page');
$ifpwap_orientation = get_option('ifpwap_orientation');
$ifpwap_display = get_option('ifpwap_display');
$ifpwap_text_direction = get_option('ifpwap_text_direction');
$ifpwap_custom_footer_btn = get_option('ifpwap_custom_footer_btn');
$ifpwap_tooltip_url = plugins_url().'/iflair-pwa-app/assets/admin/images/tooltip_icon.png';
?>
<!-- Start form section -->
<form method="post" action="options.php" enctype='multipart/form-data'>
    <?php settings_fields( 'ifpwap-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'ifpwap-plugin-settings-group' ); ?>
    <!-- Start form table -->
    <div class="ifpwap-main-table-div">
        <div class="ifpwap-col-6">
            <table class="form-table first-sec">
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Display custom button in footer?','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('You can see custom Install button at footer at frontside','ifpwap-pwa-app'); ?></label></span></th>
                    <td>                
                        <label for="ifpwap_custom_footer_btn" class="ifpwap_toggle"> 
                          <input type="checkbox" id="ifpwap_custom_footer_btn" name="ifpwap_custom_footer_btn" value="yes" <?php if(!empty($ifpwap_custom_footer_btn) && $ifpwap_custom_footer_btn == 'yes') echo esc_html__('checked=checked','ifpwap-pwa-app'); ?>>
                          <span class="ifpwap_slider"></span>
                        </label>                
                    </td>
                </tr>
                <!-- APP Name Field -->
                <tr valign="top">
                	<th scope="row"><?php echo esc_html__('APP Name :','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('The "App Name" typically refers to the name or title of the web application as it appears on the users device, such as on the home screen, in the app launcher, or in the task switcher. ','ifpwap-pwa-app'); ?></label></span></th>
                	<td>
                		<input type="text" name="ifpwap_app_title" id="ifpwap_app_title" value="<?php if(isset($ifpwap_app_title) && !empty($ifpwap_app_title)) { echo esc_html($ifpwap_app_title); } ?>">
                    </td>
                </tr>
                <!-- APP Short Name field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('APP Short Name :','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('The "App Short Name" refers to a shortened version of the applications name that is used in places where space is limited, such as on the devices home screen, in the app launcher, or in notifications.','ifpwap-pwa-app'); ?></label></span></th>
                    <td>
                        <input type="text" name="ifpwap_app_short_title" id="ifpwap_app_short_title" value="<?php if(isset($ifpwap_app_short_title) && !empty($ifpwap_app_short_title)) { echo esc_html($ifpwap_app_short_title); } ?>">
                    </td>          
                </tr>
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('App Icon :','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('Note: Image should be 192x192 and png format for mobile device','ifpwap-pwa-app'); ?></th>
                    <td>
                    <?php 
                    $ifpwap_app_image = wp_get_attachment_image_url( $ifpwap_app_icon_id, 'medium' );
                    if(isset($ifpwap_app_image) && !empty($ifpwap_app_image) ) : ?>
                        <div class="ifpwap-upload-wrap">
                        <a href="#" class="ifpwap-icon-upload">
                            <img src="<?php echo esc_url( $ifpwap_app_image ) ?>" />
                        </a>
                        <a href="#" class="ifpwap-icon-remove"><?php echo esc_html__('Remove image','ifpwap-pwa-app');?></a>
                        <input type="hidden" name="ifpwap_app_icon" value="<?php echo esc_attr(absint( $ifpwap_app_icon_id ) ); ?>"></div>
                    <?php else : ?>
                        <div class="ifpwap-upload-wrap">
                        <a href="#" class="button ifpwap-icon-upload"><?php echo esc_html__('Upload image','ifpwap-pwa-app');?></a>
                        <a href="#" class="ifpwap-icon-remove" style="display:none"><?php echo esc_html__('Remove image','ifpwap-pwa-app');?></a>
                        <input type="hidden" name="ifpwap_app_icon" value="<?php echo esc_attr(absint($ifpwap_app_icon_id)); ?>"></div>
                    <?php endif; ?>
                    </td>
                </tr>
                <!-- Space Screen Icon field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Space Screen Icon :','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('Note: Image should be 512x512 and png format for desktop device.','ifpwap-pwa-app'); ?></label></span></th>
                    <td>
                    <?php
                    $ifpwap_space_screen_icon = wp_get_attachment_image_url( $ifpwap_space_screen_icon_id, 'medium' ); 
                    if(isset($ifpwap_space_screen_icon) && !empty($ifpwap_space_screen_icon) ) : ?>
                    <div class="ifpwap-upload-wrap">
                        <a href="#" class="ifpwap-icon-upload">
                            <img src="<?php echo esc_url( $ifpwap_space_screen_icon ) ?>"/>
                        </a>
                        <a href="#" class="ifpwap-icon-remove"><?php echo esc_html__('Remove image','ifpwap-pwa-app');?></a>
                        <input type="hidden" name="ifpwap_space_screen_icon" value="<?php echo esc_attr( absint( $ifpwap_space_screen_icon_id ) ); ?>">
                    </div>
                    <?php else : ?>
                        <div class="ifpwap-upload-wrap">
                        <a href="#" class="button ifpwap-icon-upload"><?php echo esc_html__('Upload image','ifpwap-pwa-app');?></a>
                        <a href="#" class="ifpwap-icon-remove" style="display:none"><?php echo esc_html__('Remove image','ifpwap-pwa-app');?></a>
                        <input type="hidden" name="ifpwap_space_screen_icon" value="<?php echo esc_attr(absint($ifpwap_space_screen_icon_id)); ?>"></div>
                    <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="ifpwap-col-6">
            <table class="form-table second-sec">
                <!-- Background Color field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Background Color :','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('The "background color" property refers to the color that is displayed behind the content of the application.','ifpwap-pwa-app'); ?></label></span></th>
                    <td>
                    <input class="ifpwap-background-color" name="ifpwap_background_color" type="text" value="<?php if(isset($ifpwap_background_color) && !empty($ifpwap_background_color)) { echo esc_attr($ifpwap_background_color); } ?>" data-default-color="#effeff" />    
                    </td>
                </tr>
                <!-- Theme color field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Theme Color :','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('The "theme color" property refers to the primary color that is used to customize the browsers UI elements to match the color scheme of the PWA. ','ifpwap-pwa-app'); ?></label></span></th>
                    <td>
                    <input class="ifpwap-theme-color" name="ifpwap_theme_color" type="text" value="<?php if(isset($ifpwap_theme_color) && !empty($ifpwap_theme_color)) { echo esc_attr($ifpwap_theme_color); } ?>" data-default-color="#effeff" />      
                    </td>
                </tr>
                <!-- Start page field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Start Page:', 'ifpwap-pwa-app'); ?>
                    <span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('The start page refers to the initial webpage or landing page that users encounter when they first visit a website or open a web browser.','ifpwap-pwa-app'); ?></label></span>
                    </th>
                    <td>
                        <select name="ifpwap_select_start_page"> 
                            <option disabled="disabled" value="<?php echo esc_attr(''); ?>" <?php selected('', esc_attr(get_option('ifpwap_select_start_page')), true); ?>><?php echo esc_html__('Select page', 'ifpwap-pwa-app'); ?>
                            </option> 
                            <?php
                            // Define allowed HTML tags and attributes
                            $allowed_html = array(
                                'option' => array(
                                    'value' => true,
                                    'selected' => true,
                                ),
                            );
                            $selected_page = get_option('ifpwap_select_start_page');
                            $pages = get_pages(); 
                            foreach ($pages as $page) {
                                $option = '<option value="' . esc_attr($page->ID) . '" ';
                                $option .= selected($page->ID, $selected_page, false);
                                $option .= '>';
                                $option .= esc_html($page->post_title);
                                $option .= '</option>';
                                echo wp_kses($option, $allowed_html);
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <!-- Offline page field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Offline Page:', 'ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('An offline page is a webpage that is displayed to users when they attempt to access a website or web application while offline, meaning they dont have an active internet connection and the requested page is not already cached.','ifpwap-pwa-app'); ?></label></span></th>
                    <td>
                        <select name="ifpwap_select_offline_page"> 
                            <option disabled="disabled" value="<?php echo esc_attr(''); ?>" <?php selected('', esc_attr(get_option('ifpwap_select_offline_page')), true); ?>>
                                <?php echo esc_html__('Select page', 'ifpwap-pwa-app'); ?>
                            </option> 
                            <?php
                            // Define allowed HTML tags and attributes
                            $allowed_html = array(
                                'option' => array(
                                    'value' => true,
                                    'selected' => true,
                                ),
                            );
                            $selected_page = get_option('ifpwap_select_offline_page');
                            $pages = get_pages(); 
                            foreach ($pages as $page) {
                                $option = '<option value="' . esc_attr($page->ID) . '" ';
                                $option .= selected($page->ID, $selected_page, false);
                                $option .= '>';
                                $option .= esc_html($page->post_title);
                                $option .= '</option>';
                                echo wp_kses($option, $allowed_html);
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <!-- Orientation field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Orientation :','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('Orientation refers to the positioning or alignment of an object, typically in relation to a reference point or axis.','ifpwap-pwa-app'); ?></label></span></th>
                    <td>
                        <select name="ifpwap_orientation">
                            <option value="any"<?php if(!empty($ifpwap_orientation) && $ifpwap_orientation == "any"){ echo esc_html__('selected','ifpwap-pwa-app'); }?>><?php echo esc_html__('Any','ifpwap-pwa-app'); ?></option>
                            <option value="portrait"<?php if(!empty($ifpwap_orientation) && $ifpwap_orientation == "portrait"){ echo esc_html__('selected','ifpwap-pwa-app'); }?>><?php echo esc_html__('Portrait','ifpwap-pwa-app'); ?></option>
                            <option value="landscape"<?php if(!empty($ifpwap_orientation) && $ifpwap_orientation == "landscape"){ echo esc_html__('selected','ifpwap-pwa-app'); }?>><?php echo esc_html__('Landscape','ifpwap-pwa-app'); ?></option>
                        </select>
                    </td>
                </tr>
                <!-- Display field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Display :','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('The "display" property allows developers to specify how the web application will be presented to users when launched from the home screen or app launcher. ','ifpwap-pwa-app'); ?></label></span></th>
                    <td>
                        <select name="ifpwap_display">
                            <option value="fullscreen"<?php if(!empty($ifpwap_display) && $ifpwap_display == "fullscreen"){ echo esc_html__('selected','ifpwap-pwa-app'); }?>><?php echo esc_html__('Full Screen','ifpwap-pwa-app'); ?></option>
                            <option value="standalone"<?php if(!empty($ifpwap_display) && $ifpwap_display == "standalone"){ echo esc_html__('selected', 'ifpwap-pwa-app'); }?>><?php echo esc_html__('Standalone','ifpwap-pwa-app'); ?></option>
                            <option value="minimal-ui"<?php if(!empty($ifpwap_display) && $ifpwap_display == "minimal-ui"){ echo esc_html__('selected','ifpwap-pwa-app'); }?>><?php echo esc_html__('Minimal UI','ifpwap-pwa-app'); ?></option>
                        </select>
                    </td>
                </tr>
                <!-- Text Direction field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Text Direction :','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('The "text direction" property allows developers to specify the direction in which text is displayed within the application. This property is particularly relevant for languages that are written from right to left (RTL), such as Arabic, Hebrew, and Persian, as opposed to languages that are written from left to right (LTR), such as English. ','ifpwap-pwa-app'); ?></label></span></th>
                    <td>
                        <select name="ifpwap_text_direction">
                            <option value="ltr"<?php if(!empty($ifpwap_text_direction) && $ifpwap_text_direction == "ltr"){ echo esc_html__('selected','ifpwap-pwa-app'); }?>><?php echo esc_html__('LTR','ifpwap-pwa-app'); ?></option>
                            <option value="rtr"<?php if(!empty($ifpwap_text_direction) && $ifpwap_text_direction == "rtr"){ echo esc_html__('selected','ifpwap-pwa-app'); }?>><?php echo esc_html__('RTL','ifpwap-pwa-app'); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php
ifpwap_check_files_and_https_status();
?>
<!-- End form table -->
<?php submit_button(); ?>
</form>
<!-- End form section -->