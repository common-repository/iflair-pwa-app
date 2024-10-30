<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) { die(); }
// Get all options values
$ifpwap_caching_type = get_option('ifpwap_caching_type');
$ifpwap_pre_caching_manual = get_option('ifpwap_pre_caching_manual');
$ifpwap_tooltip_url = plugins_url().'/iflair-pwa-app/assets/admin/images/tooltip_icon.png';
?>
<!-- Start form section -->
<form method="post" action="options.php" enctype='multipart/form-data'>
    <?php settings_fields( 'ifpwap-plugin-caching-strategies-group'); ?>
    <?php do_settings_sections( 'ifpwap-plugin-caching-strategies-group'); ?>
    <div class="ifpwap-main-table-div">
        <div class="ifpwap-col-6">
            <!-- Start form table -->
            <table class="form-table">              
                <!-- Caching Strategies Type field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Caching Strategies Type','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('Caching strategies refer to the methods used to determine how resources such as HTML files, JavaScript, CSS, images, and API responses are stored and retrieved in a web application. These strategies play a crucial role in optimizing performance, reducing network dependencies and enabling offline capabilities especially in Progressive Web Apps (PWAs) and applications utilizing service workers.','ifpwap-pwa-app'); ?></label></span></th>
                    <td>
                        <label for="ifpwap_network_first" class="ifpwap_cache">
                            <input type="radio" name="ifpwap_caching_type" id="ifpwap_network_first" value="network_first" <?php if (!empty($ifpwap_caching_type) && $ifpwap_caching_type == 'network_first') { echo esc_html__('checked=checked','ifpwap-pwa-app'); } ?> />
                            <span><?php echo esc_html__('Network first, then Cache','ifpwap-pwa-app'); ?></span>
                            <span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('In the network-first strategy, the application attempts to fetch the requested resource from the network first. If the network request succeeds, the response is returned to the application. If the network request fails, the application falls back to the cache to retrieve the resource. This strategy ensures that the most up-to-date version of the resource is always served whenever possible.','ifpwap-pwa-app'); ?></label></span>
                        </label>
                        <label for="ifpwap_cache_first" class="ifpwap_cache">
                            <input type="radio" name="ifpwap_caching_type" id="ifpwap_cache_first" value="cache_first" <?php if (!empty($ifpwap_caching_type) && $ifpwap_caching_type == 'cache_first') { echo esc_html__('checked=checked','ifpwap-pwa-app'); } ?> />
                            <span><?php echo esc_html__('Cache first, then Network','ifpwap-pwa-app'); ?></span>
                            <span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('This strategy involves checking the cache for the requested resource first. If the resource is found in the cache, it is returned immediately. If the resource is not found in the cache, the request is made to the network, and the response is cached for future use. This strategy prioritizes serving resources from the cache whenever possible to reduce latency and decrease network traffic.','ifpwap-pwa-app'); ?></label></span>
                        </label>                
                        <label for="ifpwap_steal_while_revalidate" class="ifpwap_cache">
                            <input type="radio" name="ifpwap_caching_type" id="ifpwap_steal_while_revalidate" value="steal_while_revalidate" <?php if (!empty($ifpwap_caching_type) && $ifpwap_caching_type == 'steal_while_revalidate') { echo esc_html__('checked=checked','ifpwap-pwa-app'); } ?> />
                            <span><?php echo esc_html__('Stale While Revalidate','ifpwap-pwa-app'); ?></span>
                                <span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('This strategy involves first serving the resource from the cache while simultaneously making a request to the network to fetch an updated version. The cached version of the resource is immediately displayed to the user, providing a fast response time. This strategy provides a balance between serving cached content and ensuring it stays up-to-date.','ifpwap-pwa-app'); ?></label></span>
                        </label>               
                        <label for="ifpwap_cache_only" class="ifpwap_cache">
                            <input type="radio" name="ifpwap_caching_type" id="ifpwap_cache_only" value="cache_only" <?php if (!empty($ifpwap_caching_type) && $ifpwap_caching_type == 'cache_only') echo esc_html__('checked=checked','ifpwap-pwa-app'); ?> />
                            <span><?php echo esc_html__('Cache only','ifpwap-pwa-app'); ?></span>
                            <span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('The cache-only strategy attempts to retrieve the resource exclusively from the cache without making a network request. If the resource is not found in the cache, the request fails. This strategy is useful for ensuring offline access to cached resources but requires careful management of cached data.','ifpwap-pwa-app'); ?></label></span>
                        </label>
                        <label for="ifpwap_network_only" class="ifpwap_cache">
                            <input type="radio" name="ifpwap_caching_type" id="ifpwap_network_only" value="network_only" <?php if(!empty($ifpwap_caching_type) && $ifpwap_caching_type == 'network_only') echo esc_html__('checked=checked','ifpwap-pwa-app'); ?> />
                            <span><?php echo esc_html__('Network only','ifpwap-pwa-app'); ?></span><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('The network-only strategy fetches the resource exclusively from the network without consulting the cache. It ensures that the application always receives the latest version of the resource from the network. This strategy is suitable for resources that require real-time data and should not be served from the cache.','ifpwap-pwa-app'); ?></label></span>
                        </label>
                    </td>
                </tr>
                <!-- Pre Caching field -->
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Pre Caching Manual','ifpwap-pwa-app'); ?><span class="ifpwap_ctooltip"><sup><img src="<?php echo esc_url($ifpwap_tooltip_url);?>"></sup><label class="ifpwap-tooltip-content"><?php echo esc_html__('Note: Seperate the URLs using Comma(,)','ifpwap-pwa-app'); ?><br><?php echo esc_html__('Place the list of URLs which you want to pre cache by service worker','ifpwap-pwa-app'); ?></label></span></th>
                    <td>
                        <label><textarea placeholder="https://example.com/2019/06/06/hello-world/, https://example.com/2019/06/06/hello-world-2/" name="ifpwap_pre_caching_manual" rows="5" cols="50"><?php echo esc_textarea($ifpwap_pre_caching_manual);?></textarea>
                        </label>   
                    </td>      
                </tr>        
            </table>
            <!-- End form table -->
        </div>
    </div>
    <?php submit_button(); ?>
</form>
<!-- End form section -->