<?php
/*
Plugin Name: PWA App by iFlair
Description: iFlair PWA App plugin transforms your website into a Progressive Web App (PWA), enhancing user experience with app-like features such as faster load times, offline accessibility, and the convenience of adding the site to a smartphone's home screen. It also offers customizable backend settings, allowing for modifications to the app's color, name, and more, ensuring a personalized and seamless integration.
Plugin URI: https://profiles.wordpress.org/iflairwebtechnologies
Version: 1.0.0
Author: The iFlair Team
Text Domain: ifpwap-pwa-app
Author URI: https://www.iflair.com/
License: GPLv2 or later
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) { die(); }
// define plugin version
define( 'IFPWAP_VERSION' , '1.0.0' );
define( 'IFPWAP_FILE', __FILE__ );
define( 'IFPWAP_DIR', dirname(__FILE__));
define( 'IFPWAP_DIR_PATH', plugin_dir_url( __FILE__ ) );

$ifpwap_version = IFPWAP_VERSION;
$ifpwap_webname = get_bloginfo('name');

// Ensure the WP_Filesystem class is loaded
if ( ! class_exists( 'WP_Filesystem' ) ) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
}

// Initialize the WP_Filesystem
global $wp_filesystem;
if ( ! $wp_filesystem ) { WP_Filesystem(); }

// setup plugin menu
add_action('admin_menu', 'ifpwap_plugin_setup_menu'); 
function ifpwap_plugin_setup_menu(){
    add_menu_page( 'PWA App by iFlair Settings', 'PWA App by iFlair', 'manage_options', 'ifpwap-plugin', 'ifpwap_admin_page_html', 'dashicons-desktop' );
}
//admin page
function ifpwap_admin_page_html(){
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) { return; }   
    //Get the active tab from the $_GET param
    $ifpwap_default_tab = null;
    $ifpwap_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $ifpwap_default_tab;
    ?>
    <!-- Our admin page content should all be inside .wrap -->
    <div class="ifpwap-plugin wrap">
        <!-- Print the page title -->
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <!-- Here are our tabs -->
        <nav class="nav-tab-wrapper">
          <a href="?page=ifpwap-plugin" class="nav-tab <?php if($ifpwap_tab===null):?>nav-tab-active<?php endif; ?>"><?php echo esc_html_e('Basic Settings','ifpwap-pwa-app');?></a>
          <a href="?page=ifpwap-plugin&tab=caching-strategies" class="nav-tab <?php if($ifpwap_tab==='caching-strategies'):?>nav-tab-active<?php endif; ?>"><?php echo esc_html_e('Caching Strategies','ifpwap-pwa-app');?></a>
          <a href="?page=ifpwap-plugin&tab=advanced" class="nav-tab <?php if($ifpwap_tab==='advanced'):?>nav-tab-active<?php endif; ?>"><?php echo esc_html_e('Advanced','ifpwap-pwa-app');?></a>
        </nav>
        <!-- Content Section -->
        <div class="tab-content">
        <?php 
        switch($ifpwap_tab) :
          case 'caching-strategies': 
          include_once IFPWAP_DIR.'/includes/admin/caching-strategies.php';
            break;
          case 'advanced': 
          include_once IFPWAP_DIR.'/includes/admin/advanced.php';
            break;
          default: 
          include_once IFPWAP_DIR.'/includes/admin/settings_fields.php';
            break;
        endswitch; ?>
        </div>
    </div>
    <?php 
}
// Display settings link when plugin acive on plugins page
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'ifpwap_plugin_action_links' );
function ifpwap_plugin_action_links( $links ) { 
   $settings = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=ifpwap-plugin') ) .'">'. esc_html__('Settings','ifpwap-pwa-app') .'</a>';  
   array_unshift($links , $settings);
   return $links; 
}
// Enqueue admin JS
add_action( 'admin_enqueue_scripts', 'ifpwap_include_js' );
function ifpwap_include_js() {
    wp_enqueue_script('jquery');
    // WordPress media uploader scripts
    if ( ! did_action( 'wp_enqueue_media' ) ) { wp_enqueue_media(); }
    wp_enqueue_script('ifpwap-custom-js',plugin_dir_url( __FILE__ ). 'assets/admin/js/custom.js',array( 'wp-color-picker' ), false, true);
}
// Enqueue admin CSS
add_action( 'admin_enqueue_scripts', 'ifpwap_enqueue_color_picker' );
function ifpwap_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_style( 'ifpwap-style', plugins_url( '/assets/admin/css/style.css', __FILE__ ), '', IFPWAP_VERSION );
}

// Inside your plugin file or theme's functions.php file
function ifpwap_enqueue_install_prompt_script() {
    wp_enqueue_script('jquery');
    // Ensure the URLs are from a secure source and properly formatted
    $font_awesome_url = esc_url( plugins_url('assets/frontend/font-awesome.css', __FILE__) );
    $style_url = esc_url( plugins_url('assets/frontend/style.css', __FILE__) );
    wp_enqueue_style('ifpwap-font-awesome-style', $font_awesome_url);
    wp_enqueue_style('ifpwap-style', $style_url);
}
add_action('wp_enqueue_scripts', 'ifpwap_enqueue_install_prompt_script');

// default options values added while plugin active
register_activation_hook( __FILE__, 'ifpwap_set_up_options' );
function ifpwap_set_up_options() {
    $home_url = get_home_url();
    $page_id = url_to_postid($home_url);
    add_option('ifpwap_app_title', sanitize_text_field(get_bloginfo('name')));
    add_option('ifpwap_select_start_page', intval($page_id));
    add_option('ifpwap_caching_type', sanitize_title('network_first'));
    add_option('ifpwap_orientation', sanitize_title('portrait'));
    add_option('ifpwap_display', sanitize_title('fullscreen'));
    add_option('ifpwap_text_direction', sanitize_title('rtr'));
    add_option('ifpwap_custom_footer_btn', sanitize_title('yes'));
    add_option('ifpwap_campaign_source', sanitize_text_field(get_bloginfo('name')));
    add_option('ifpwap_campaign_medium', sanitize_text_field(get_bloginfo('name')));
    add_option('ifpwap_campaign_name', sanitize_text_field(get_bloginfo('name')));
}

// Creates the link tag
add_action( 'wp_head', 'ifpwap_inc_manifest_link' );
function ifpwap_inc_manifest_link() {  
    $ifpwap_theme_color = get_option('ifpwap_theme_color'); 
    ?>
    <meta name="theme-color" content="<?php echo esc_attr($ifpwap_theme_color);?>">
    <?php
}

add_action( 'wp_enqueue_scripts', 'ifpwap_enqueue_manifest_link' );
function ifpwap_enqueue_manifest_link() {  
    // Get the URL for the manifest file
    $manifest_url = esc_url( get_site_url() . '/ifpwap-manifest.json' );
    // Enqueue the manifest file as a style
    wp_enqueue_style( 'ifpwap-manifest', $manifest_url, array(), null );
}
add_filter( 'style_loader_tag', 'ifpwap_modify_manifest_link_tag', 10, 4 );
function ifpwap_modify_manifest_link_tag( $html, $handle, $href, $media ) {
    if ( 'ifpwap-manifest' === $handle ) {
        $html = '<link rel="manifest" href="' . esc_url( $href ) . '">';
    }
    return $html;
}

add_action( 'wp_enqueue_scripts', 'ifpwap_enqueue_prefetch_manifest_link' );
function ifpwap_enqueue_prefetch_manifest_link() {  
    // Get the URL for the manifest file
    $manifest_url = esc_url( get_site_url() . '/ifpwap-manifest.json' );
    // Enqueue the prefetch link
    wp_enqueue_script( 'ifpwap-prefetch-manifest', $manifest_url, array(), null, true );
}
add_filter( 'script_loader_tag', 'ifpwap_modify_prefetch_manifest_link_tag', 10, 3 );
function ifpwap_modify_prefetch_manifest_link_tag( $tag, $handle, $src ) {
    if ( 'ifpwap-prefetch-manifest' === $handle ) {
        $tag = '<link rel="prefetch" href="' . esc_url( $src ) . '">';
    }
    return $tag;
}

// Register JS files in footer
add_action('wp_enqueue_scripts', 'ifpwap_register_footer_js');
function ifpwap_register_footer_js() {
    wp_enqueue_script('ifpwap-footer-js', esc_url(get_site_url().'/ifpwap-register-sw.js'), array(), null, true);
}

function ifpwap_common_sanitize($input) {
    return wp_kses_post($input);
}

// register plugin settings
function ifpwap_register_plugin_settings() {
    // start basic settings fields
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_app_title', 'ifpwap_common_sanitize' );
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_app_short_title', 'ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_description' , 'ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_app_icon', 'ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_monocrome_icon', 'ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_space_screen_icon','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_background_color', 'ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_theme_color','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_select_start_page','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_select_offline_page','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_orientation','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_display','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_text_direction','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-settings-group', 'ifpwap_custom_footer_btn','ifpwap_common_sanitize');
    // Start caching-strategies fields
    register_setting('ifpwap-plugin-caching-strategies-group', 'ifpwap_caching_type','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-caching-strategies-group', 'ifpwap_pre_caching_manual','ifpwap_common_sanitize');
    // start utm-tracking fields
    register_setting('ifpwap-plugin-utm-tracking-group', 'ifpwap_campaign_source','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-utm-tracking-group', 'ifpwap_campaign_medium','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-utm-tracking-group', 'ifpwap_campaign_name','ifpwap_common_sanitize');  
    // Start advanced fields
    register_setting('ifpwap-plugin-advanced-group', 'ifpwap_offline_analytics','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-advanced-group', 'ifpwap_cache_external_urls','ifpwap_common_sanitize');
    register_setting('ifpwap-plugin-advanced-group', 'ifpwap_exclude_urls_from_cache_list','ifpwap_common_sanitize');

    if (isset($_REQUEST['page']) && isset($_REQUEST['settings-updated'])) {
        $page = sanitize_text_field($_REQUEST['page']);
        $settings_updated = sanitize_text_field($_REQUEST['settings-updated']);
        $tab = isset($_REQUEST['tab']) ? sanitize_text_field($_REQUEST['tab']) : null;

        if ($page === 'ifpwap-plugin' && $settings_updated === 'true') {
            if ($tab === null) {
                add_settings_error(
                    'ifpwap-notices',
                    'ifpwap-success',
                    esc_html__('Your settings are saved successfully.', 'ifpwap-pwa-app'),
                    'updated'
                );
            } else {
                add_settings_error(
                    'ifpwap-notices',
                    'ifpwap-success',
                    esc_html__('Your settings are saved successfully.', 'ifpwap-pwa-app'),
                    'updated'
                );
            }
        } elseif ($page === 'ifpwap-plugin' && $settings_updated === 'false') {
            if ($tab === null) {
                add_settings_error(
                    'ifpwap-notices',
                    'ifpwap-error',
                    esc_html__('Settings not saved', 'ifpwap-pwa-app'),
                    'error'
                );
            } else {
                add_settings_error(
                    'ifpwap-notices',
                    'ifpwap-error',
                    esc_html__('Settings not saved', 'ifpwap-pwa-app'),
                    'error'
                );
            }
        }
    }
}
add_action( 'admin_init', 'ifpwap_register_plugin_settings' );
// Display success or error messages
function ifpwap_admin_notices() {
    settings_errors('ifpwap-notices');
}
add_action('admin_notices', 'ifpwap_admin_notices');
/////////////////////////////////////////////////
///// Get Options data for json file ////////////
/////////////////////////////////////////////////
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
$ifpwap_select_offline_page = get_option('ifpwap_select_offline_page');
$ifpwap_orientation = get_option('ifpwap_orientation');
$ifpwap_display = get_option('ifpwap_display');
$ifpwap_text_direction = get_option('ifpwap_text_direction');
$ifpwap_app_image = wp_get_attachment_image_url( $ifpwap_app_icon_id );
$ifpwap_space_screen_icon = wp_get_attachment_image_url( $ifpwap_space_screen_icon_id);
$ifpwap_monocrome_icon = wp_get_attachment_image_url( $ifpwap_monocrome_icon_id );
$ifpwap_campaign_source = get_option('ifpwap_campaign_source');
$ifpwap_campaign_medium = get_option('ifpwap_campaign_medium');
$ifpwap_campaign_name = get_option('ifpwap_campaign_name');
// Remove the trailing comma after the last iteration
$ifpwap_start_page_name = get_the_title($ifpwap_select_start_page);
$base = plugin_dir_path(__FILE__);
$root_path = plugin_dir_path(dirname(dirname($base)));

$offline_page_slug = get_post_field( 'post_name', $ifpwap_select_offline_page );
$offline_page_url = home_url( '/' ) . $offline_page_slug;

$start_s_page_slug = get_post_field( 'post_name', $ifpwap_select_start_page );
$start_s_page_url = home_url( '/' ) . $start_s_page_slug;

//////////////////////////////////////////////////////////////////////////////
//////////////////////// manifest json file //////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

$ifpwap_start_page_name = get_the_title($ifpwap_select_start_page);
$start_url = '"' . site_url();
// Check if the current page is the front page
if (!is_front_page()) {
    // Check if the page name matches the title of the page set as the front page
    $front_page = get_option('page_on_front');
    $front_page_title = get_the_title($front_page);
    if ($ifpwap_start_page_name && $ifpwap_start_page_name !== $front_page_title) {
        // Get the corresponding page object and extract the slug
        $start_page = get_page_by_title($ifpwap_start_page_name);
        $slug = isset($start_page->post_name) ? $start_page->post_name : '';
        // Append the slug if it's not the front page
        if ($slug) {
            $start_url .= '/' . $slug;
        }
    }
}
$start_url .= '?utm_source=' . sanitize_text_field($ifpwap_campaign_source) . '&utm_medium=' . sanitize_text_field($ifpwap_campaign_medium) . '&utm_campaign=' . sanitize_text_field($ifpwap_campaign_name);

if (empty($ifpwap_description)) {
    $ifpwap_description = esc_html__("This PWA APP WordPress plugin description", "ifpwap-pwa-app");
}

// put json file in php variable
$ifpwap_manifest_data = array(
    "name" => sanitize_text_field($ifpwap_app_title),
    "short_name" => sanitize_text_field($ifpwap_app_short_title),
    "description" => sanitize_text_field($ifpwap_description),
    "icons" => array(
        array(
            "src" => esc_url($ifpwap_app_image),
            "sizes" => "192x192",
            "type" => "image/png",
            "purpose" => "any"
        ),
        array(
            "src" => esc_url($ifpwap_app_image),
            "sizes" => "192x192",
            "type" => "image/png",
            "purpose" => "maskable"
        ),
        array(
            "src" => esc_url($ifpwap_space_screen_icon),
            "sizes" => "512x512",
            "type" => "image/png",
            "purpose" => "any"
        ),
        array(
            "src" => esc_url($ifpwap_space_screen_icon),
            "sizes" => "512x512",
            "type" => "image/png",
            "purpose" => "maskable"
        )
    ),
    "background_color" => sanitize_text_field($ifpwap_background_color),
    "theme_color" => sanitize_text_field($ifpwap_theme_color),
    "display" => sanitize_text_field($ifpwap_display),
    "dir" => sanitize_text_field($ifpwap_text_direction),
    "orientation" => sanitize_text_field($ifpwap_orientation),
    "start_url" => esc_url($start_url),
    "scope" => esc_url(site_url()),
    "shortcuts" => array(
        array(
            "name" => sanitize_text_field($ifpwap_app_short_title),
            "url" => esc_url(site_url('/')),
            "description" => sanitize_text_field($ifpwap_description),
            "icons" => array(
                array(
                    "src" => esc_url($ifpwap_app_image),
                    "sizes" => "192x192"
                )
            )
        )
    )
);
$ifpwap_manifest_json = json_encode($ifpwap_manifest_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
if ($wp_filesystem) {
    $manifest_fp = trailingslashit($root_path) . "ifpwap-manifest.json";
    if (false === $wp_filesystem->put_contents($manifest_fp, $ifpwap_manifest_json, FS_CHMOD_FILE)) {
        echo esc_html__('Failed to write the manifest file.', 'ifpwap-pwa-app');
    }
} else {
    echo esc_html__('Filesystem API not initialized.', 'ifpwap-pwa-app');
}

//////////////////////////////////////////////////////////////////////////////
//////////////////////// Register Service Worker JS///////////////////////////
//////////////////////////////////////////////////////////////////////////////
$ifpwap_register_service_worker_js = "const swfile = '".esc_url(home_url())."/ifpwap-sw.js';
const scopePath = '".esc_url(site_url())."/';
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register(swfile, { scope: scopePath })
            .then(registration => {
                console.log('ServiceWorker ready');
                console.log('ServiceWorker scope:', registration.scope);
                if (registration.active) {
                    registration.update();
                }
            })
            .catch(error => console.log('Registration failed with ' + error));
    });
}
window.addEventListener('load', () => {
    const manifestLink = document.querySelectorAll(\"link[rel='manifest']\");
    if (manifestLink.length > 1) {
        for (let i = 0; i < manifestLink.length; i++) {
            const href = manifestLink[i].getAttribute(\"href\");
            if (href.indexOf(\"ifpwap-manifest.json\") === -1) {
                manifestLink[i].remove();
            }
        }
    }   
    const ua = window.navigator.userAgent;
    const iOS = ua.match(/iPad|iPod|iPhone/);
    const webkit = ua.match(/WebKit/i);
    const iOSSafari = iOS && webkit && !ua.match(/CriOS/i);
    if (iOSSafari && window.matchMedia('(display-mode: standalone)').matches) {
        setTimeout(() => {
            const anchorFix = document.querySelectorAll(\"a[href='#']\");
            if (anchorFix.length > 1) {
                for (let i = 0; i < anchorFix.length; i++) {
                    anchorFix[i].setAttribute(\"href\", \"javascript:void(0);\");
                }
            }
        }, 600);
    }
});";
if ($wp_filesystem) {
    $registersw_fp = trailingslashit($root_path) . "ifpwap-register-sw.js"; // Ensure path is correctly formatted
    $success = $wp_filesystem->put_contents(
        $registersw_fp,
        $ifpwap_register_service_worker_js,
        FS_CHMOD_FILE // Set proper file permissions
    );
    if (!$success) {
        echo esc_html__('Failed to write the service worker file.', 'ifpwap-pwa-app');
    }
} else {
    echo esc_html__('Filesystem API not initialized.', 'ifpwap-pwa-app');
}
// analytics js code for append
$ifpwap_analytics_js_append = "importScripts(\"https://storage.googleapis.com/workbox-cdn/releases/6.0.2/workbox-sw.js\");
if (workbox.googleAnalytics) {
    try {
        workbox.googleAnalytics.initialize();
    } catch (e) { console.log(e.message); }
}";
// external url cache js append code
$ifpwap_external_url_cache_js_append = "if(new URL(e.request.url).origin !== location.origin) { return; }";
// Get advanced fields value
$ifpwap_start_page_name = get_the_title( $ifpwap_select_start_page );
$ifpwap_start_offline_name = get_the_title( $ifpwap_select_offline_page );
$ifpwap_offline_analytics = get_option('ifpwap_offline_analytics');
// Start get cache list URLs code
$ifpwap_exclude_urls_from_cache_list = get_option('ifpwap_exclude_urls_from_cache_list');
$ifpwap_exclude_cache_list = explode(',', $ifpwap_exclude_urls_from_cache_list);
$ifpwap_exclude_cache_list = array_map('ifpwap_sanitize_cache_url', $ifpwap_exclude_cache_list);
$ifpwap_exclude_cache_list = implode(",", $ifpwap_exclude_cache_list);
// End get cache list URLs code
$caching_type = get_option('ifpwap_caching_type');
$ifpwap_pre_caching_manual = get_option('ifpwap_pre_caching_manual');
// Start get manual cache URLs code
$ifpwap_manual_cache_list = explode(',', $ifpwap_pre_caching_manual);
$ifpwap_manual_cache_list = array_map('ifpwap_sanitize_cache_url', $ifpwap_manual_cache_list);
$ifpwap_manual_cache_list = implode(",", $ifpwap_manual_cache_list);
// End get manual cache URLs code
$ifpwap_cache_external_urls = get_option('ifpwap_cache_external_urls');
if($ifpwap_cache_external_urls == 'yes'){
    $ifpwap_external_url_cache_js = $ifpwap_external_url_cache_js_append;
}
// Function to sanitize cache URLs
function ifpwap_sanitize_cache_url($url)
{
    $url = str_replace(["\n\r", "\n", "\r"], '', $url);
    $url = str_replace(PHP_EOL, '', $url);
    return "'{$url}'";
}
//////////////////////////////////////////////////////////////////////////////
//////////////////////// Service Worker JS ///////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
// Put JS code in php variable
$ifpwap_sw_js = "'use strict';
const cacheName = 'ifpwap-".sanitize_text_field($ifpwap_version)."';
const startPage = '".sanitize_text_field($start_s_page_url)."';
const offlinePage = '".sanitize_text_field($offline_page_url)."';
const filesToCache = [startPage, offlinePage,".sanitize_text_field($ifpwap_manual_cache_list)."];
const neverCacheUrls = [/\/wp-admin/, /\/wp-login/, /preview=true/, /\/cart/, /ajax/, /login/,".sanitize_text_field($ifpwap_exclude_cache_list)."];
self.addEventListener('install', (e) => {
    console.log('PWA service worker installation');
    e.waitUntil(
        caches.open(cacheName).then((cache) => {
            console.log('PWA service worker caching dependencies');
            return cache.addAll(filesToCache);
        })
    );
});
self.addEventListener('activate', (e) => {
    console.log('PWA service worker activation');
    e.waitUntil(
        caches.keys().then((keyList) => {
            return Promise.all(
                keyList.map((key) => {
                    if (key !== cacheName) {
                        console.log('PWA old cache removed', key);
                        return caches.delete(key);
                    }
                })
            );
        })
    );
    return self.clients.claim();
});
self.addEventListener('fetch', (e) => {
    if (!neverCacheUrls.every(checkNeverCacheList, e.request.url)) {
        console.log('PWA: Current request is excluded from cache.');
        return;
    }
    if (!e.request.url.startsWith('http')) {
        return;
    }
    // start external url cache js
    ".sanitize_text_field($ifpwap_external_url_cache_js)."
    // end external url cache js
    if (e.request.method !== 'GET') {
        e.respondWith(
            fetch(e.request)
                .catch(() => {
                    return caches.match(offlinePage);
                })
        );
        return;
    } 
    if (e.request.headers.get('range')) {
        fetchRangeData(e);
        return;
    }
    let responsePromise;
    if ((e.request.mode === 'navigate' || e.request.mode === 'cors') && navigator.onLine) {
        responsePromise = fetch(e.request)
            .then((response) => {
                return caches.open(cacheName).then((cache) => {
                    cache.put(e.request, response.clone());
                    return response;
                });
            })
            .catch(() => {
                return caches.match(e.request.url);
            });
    } else {
        // start caching type script
        switch ('".sanitize_text_field($caching_type)."') {
            case 'network_first':
                responsePromise = networkFirstStrategy(e);
                break;
            case 'cache_first':
                responsePromise = cacheFirstStrategy(e);
                break;
            case 'steal_while_revalidate':
                responsePromise = stealWhileRevalidateStrategy(e);
                break;
            case 'cache_only':
                responsePromise = cacheOnlyStrategy(e);
                break;
            case 'network_only':
                responsePromise = networkOnlyStrategy(e);
                break;
            default:
                // Default strategy
                responsePromise = defaultStrategy(e);
        }
    }
    e.respondWith(responsePromise);
});
// Define caching strategy functions
function networkFirstStrategy(e) {
    return caches.match(e.request)
        .then((response) => {
            return response || fetch(e.request)
                .then((fetchResponse) => {
                    return caches.open(cacheName).then((cache) => {
                        cache.put(e.request, fetchResponse.clone());
                        return fetchResponse;
                    });
                })
                .catch(() => {
                    return caches.match(offlinePage);
                });
        });
}
function cacheFirstStrategy(e) {
    return caches.open(cacheName)
        .then((cache) => cache.match(e.request)
            .then((cacheResponse) => {
                return cacheResponse || fetch(e.request)
                    .then((networkResponse) => {
                        cache.put(e.request, networkResponse.clone());
                        return networkResponse;
                    });
            })
        )
        .catch(() => {
            return fetch(e.request.url)
                .then((response) => {
                    return caches.open(cacheName)
                        .then((cache) => {
                            cache.put(e.request, response.clone());
                            return response;
                        });
                });
        });
}
function stealWhileRevalidateStrategy(e) {
    return caches.open(cacheName)
        .then((cache) => cache.match(e.request)
            .then((cacheResponse) => {
                return fetch(e.request)
                    .then((networkResponse) => {
                        cache.put(e.request, networkResponse.clone());
                        return cacheResponse || networkResponse;
                    });
            })
        );
}
function cacheOnlyStrategy(e) {
    return caches.open(cacheName)
        .then((cache) => cache.match(e.request));
}
function networkOnlyStrategy(e) {
    return fetch(e.request);
}
function defaultStrategy(e) {
    console.log('defaultStrategy');
}
const fetchRangeData = (event) => {
    const pos = Number(/^bytes=(\d+)-$/g.exec(event.request.headers.get('range'))[1]);
    console.log('Range request for', event.request.url, ', starting position:', pos);
    event.respondWith(
        caches.open(cacheName)
            .then((cache) => cache.match(event.request.url))
            .then((res) => {
                if (!res) {
                    return fetch(event.request)
                        .then((res) => {
                            return res.arrayBuffer();
                        });
                }
                return res.arrayBuffer();
            })
            .then((ab) => {
                return new Response(
                    ab.slice(pos),
                    {
                        status: 206,
                        statusText: 'Partial Content',
                        headers: [
                            ['Content-Range', 'bytes ' + pos + '-' +
                                (ab.byteLength - 1) + '/' + ab.byteLength]
                        ],
                    }
                );
            })
    );
};
function checkNeverCacheList(url) {
    return !this.match(url);
}";
// Append analytics code if enabled
if ($ifpwap_offline_analytics == 'yes') {
    $ifpwap_final_sw_js = $ifpwap_sw_js . "\n" . $ifpwap_analytics_js_append;
} else {
    $ifpwap_final_sw_js = $ifpwap_sw_js;
}
if ( $wp_filesystem ) {
    $sw_fp = trailingslashit($root_path) . "ifpwap-sw.js"; // Ensure path is correctly formatted
    $success = $wp_filesystem->put_contents(
        $sw_fp,
        $ifpwap_final_sw_js,
        FS_CHMOD_FILE // Set proper file permissions
    );
    if (!$success) {
        echo esc_html__('Failed to write the service worker file.', 'ifpwap-pwa-app');
    }
} else {
    echo esc_html__('Filesystem API not initialized.', 'ifpwap-pwa-app');
}

// Footer custom intall button and banner
$ifpwap_custom_footer_btn = get_option('ifpwap_custom_footer_btn');
if (isset($ifpwap_custom_footer_btn) && !empty($ifpwap_custom_footer_btn))
{
    // Add the inline script
    function ifpwap_inline_custom_script() {
        wp_add_inline_script('jquery', '
            window.addEventListener("beforeinstallprompt", (event) => {
                let deferredPrompt;
                event.preventDefault();
                document.body.classList.add("ifpwap-custom-pwa-banner");

                // Store the event for later use
                deferredPrompt = event;

                // Function to show the installation modal
                function ifpwap_showInstallationModal() {
                    // Hide the small banner
                    ifpwap_hideSmallBanner();

                    const ifpwap_installModal = document.createElement("div");
                    ifpwap_installModal.innerHTML = `<div><p>Would you like install App?</p><button id="ifpwap_installButton">Install</button>&nbsp;&nbsp;<button id="ifpwap_cancelButton">Cancel</button></div>`;
                    document.body.appendChild(ifpwap_installModal);

                    // Add an event listener to the custom install button
                    const ifpwap_installButton = document.getElementById("ifpwap_installButton");
                    ifpwap_installButton.addEventListener("click", () => {
                        // Trigger the deferred prompt
                        if (deferredPrompt) {
                            deferredPrompt.prompt();
                            // Wait for the user to respond to the prompt
                            deferredPrompt.userChoice
                                .then((choiceResult) => {
                                    if (choiceResult.outcome === "accepted") {
                                        console.log("User accepted the A2HS prompt");
                                        document.body.classList.remove("custom-pwa-banner");
                                    } else {
                                        console.log("User dismissed the A2HS prompt");
                                    }
                                })
                                .catch((error) => {
                                    console.error("Error while waiting for userChoice:", error);
                                })
                                .finally(() => {
                                    // Reset the deferredPrompt variable
                                    deferredPrompt = null;
                                    // Remove the custom modal
                                    ifpwap_installModal.parentNode.removeChild(ifpwap_installModal);
                                });
                        }
                    });

                    // Add an event listener to the cancel button
                    const ifpwap_cancelButton = document.getElementById("ifpwap_cancelButton");
                    ifpwap_cancelButton.addEventListener("click", () => {
                        // Remove the custom modal
                        ifpwap_installModal.parentNode.removeChild(ifpwap_installModal);
                        // Show the small banner again
                        ifpwap_showSmallBanner();
                        jQuery("#ifpwap_smallBanner").show();
                    });
                }

                // Function to handle the click on the small banner
                function ifpwap_handleSmallBannerClick() {
                    // Show the installation modal
                    ifpwap_showInstallationModal();
                }

                // Function to hide the small banner
                function ifpwap_hideSmallBanner() {
                    const ifpwap_smallBanner = document.getElementById("ifpwap_smallBanner");
                    if (ifpwap_smallBanner) {
                        ifpwap_smallBanner.style.display = "none";
                    }
                }

                // Function to show the small banner with PWA icon
                // Function to show the small banner with a rounded CSS circle icon
                function ifpwap_showSmallBanner() {
                    const ifpwap_existingSmallBanner = document.getElementById("ifpwap_smallBanner");

                    if (!ifpwap_existingSmallBanner && document.body) {
                        const ifpwap_smallBanner = document.createElement("div");
                        ifpwap_smallBanner.id = "ifpwap_smallBanner";
                        ifpwap_smallBanner.innerHTML = `<p><i class="fas fa-mobile-alt"></i></p>`;

                        document.body.appendChild(ifpwap_smallBanner);

                        // Add an event listener to the small banner
                        ifpwap_smallBanner.addEventListener("click", ifpwap_handleSmallBannerClick);
                    }
                }

                // Check if the browser supports beforeinstallprompt
                if ("BeforeInstallPromptEvent" in window) {
                    // Add an event listener for beforeinstallprompt
                    window.addEventListener("beforeinstallprompt", (event) => {
                        // Prevent the default prompt
                        event.preventDefault();
                        // Store the event for later use
                        deferredPrompt = event;
                        jQuery("#ifpwap_smallBanner").show();
                    });
                }

                // Initial call to show the small banner
                ifpwap_showSmallBanner();
            });
        ', 'after');
    }
    add_action('wp_enqueue_scripts', 'ifpwap_inline_custom_script');
}

// Action hook to execute the code within the WordPress environment
function ifpwap_check_files_and_https_status() {
    // Check if WordPress environment is loaded
    if (function_exists('get_site_url')) {
        // Specify the files to check
        $files_to_check = array(
            'ifpwap-sw.js',
            'ifpwap-register-sw.js',
            'ifpwap-manifest.json'
        );
        // Initialize array to store file URLs
        $file_urls = array();
        // Check if files exist and get their URLs
        foreach ($files_to_check as $file) {
            $file_path = ABSPATH . $file;
            if (file_exists($file_path)) {
                $file_urls[$file] = get_site_url(null, '/' . $file);
            }
        }
        // Check if website is served over HTTPS
        $https_status = (is_ssl()) ? 'Yes' : 'No';
        // Output HTML with file URLs and HTTPS status
        ?>        
        <div class="ifpwap_files_url">
            <h3><?php echo esc_html__('Status','ifpwap-pwa-app');?></h3>
            <?php 
            foreach ($file_urls as $file => $url) { ?>
                <p class='ifpwap_<?php echo esc_attr($file);?>'><a class='ifpwap_icon-link' target='_blank' href='<?php echo esc_url($url);?>'><?php echo esc_html($file);?></a><?php echo esc_html__(' File successfully generated.','ifpwap-pwa-app');?></p>
            <?php } ?>
            <p class='ifpwap_https ifpwap_icon-link'><?php echo esc_html__('Website served over HTTPS:','ifpwap-pwa-app') . ' ' . esc_html($https_status);?></p>
        </div>
    <?php } else {
        echo esc_html__('WordPress environment not found.','ifpwap-pwa-app');
    }
}