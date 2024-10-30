<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) { die(); }
// Get all options values
$ifpwap_offline_analytics = get_option('ifpwap_offline_analytics');
$ifpwap_cache_external_urls = get_option('ifpwap_cache_external_urls');
$ifpwap_exclude_urls_from_cache_list = get_option('ifpwap_exclude_urls_from_cache_list');
$ifpwap_tooltip_url = plugins_url().'/iflair-pwa-app/assets/admin/images/tooltip_icon.png';
?>
<!-- Start form section -->
<form method="post" action="options.php">
    <?php settings_fields( 'ifpwap-plugin-advanced-group' ); ?>
    <?php do_settings_sections( 'ifpwap-plugin-advanced-group' ); ?>
    <div class="ifpwap-main-table-div">
        <div class="ifpwap-col-6">
            <!-- Start form table -->
            <table class="form-table">
                <!-- Offline Analytics field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Cache External Origin URLs','ifpwap-pwa-app'); ?>
                    <span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('Caching external origin URLs involves storing copies of resources (such as images, scripts, or stylesheets) from external websites or servers in a local cache. This caching mechanism helps improve website performance by reducing latency and server load, as the resources can be retrieved quickly from the local cache instead of fetching them from the external origin every time a user accesses the website.','ifpwap-pwa-app'); ?></label></span></th>
                    <td>                
                        <label for="ifpwap_cache_external_urls" class="ifpwap_toggle"> 
                          <input type="checkbox" id="ifpwap_cache_external_urls" name="ifpwap_cache_external_urls" value="yes" <?php if(!empty($ifpwap_cache_external_urls) && $ifpwap_cache_external_urls == 'yes') echo esc_html__('checked=checked','ifpwap-pwa-app'); ?>>
                          <span class="ifpwap_slider"></span>
                        </label>                
                    </td>      
                </tr>
                <!-- Exclude URLs from Cache list textarea -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Exclude URLs from Cache list','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('Note: This feature is useful when you have certain resources that you do not want to be cached, either because they are dynamic or because caching them could lead to undesirable behavior. Seperate the URLs using Comma(,)','ifpwap-pwa-app'); ?></label></span></th>
                    <td>                
                        <textarea cols="50" rows="5" placeholder="https://example.com/2019/06/06/hello-world/, https://example.com/2019/06/06/hello-world-2/" name="ifpwap_exclude_urls_from_cache_list"><?php if(isset($ifpwap_exclude_urls_from_cache_list) && !empty($ifpwap_exclude_urls_from_cache_list)) { echo esc_textarea($ifpwap_exclude_urls_from_cache_list); } ?></textarea>
                    </td>      
                </tr>
                <!-- Advanced section end -->
            </table>
            <!-- End form table -->
        </div>
    </div>
    <?php submit_button(); ?>
</form>
<!-- End form section -->