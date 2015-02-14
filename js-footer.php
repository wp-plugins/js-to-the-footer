<?php
/*
Plugin Name: JS to the Footer
Description: Move all of your JS to the footer, load your CSS and images first, to avoid a flickr of un-styled content.
Stable Tag: 4.1
Author: Kostas Vrouvas
Author URI: http://kosvrouvas.com/
Version: 1.0
 */

// Prevent direct access to this file.
if( !defined( 'ABSPATH' ) ) {
        exit( 'You are not allowed to access this file directly.' );
}

// The "Core"
function footer_enqueue_scripts() {
    remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);
    add_action('wp_footer', 'wp_print_scripts', 5);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5);
}
add_action( 'wp_enqueue_scripts', 'footer_enqueue_scripts' ); 

// Action link
function jsfooter_action_links( $links ) {
    $settings_link = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8DH2U6LZZZLBL" target="_blank">' . __( 'Donate' ) . '</a>';
    array_push( $links, $settings_link );
        return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'jsfooter_action_links' );


// Welcome screen
register_activation_hook( __FILE__, 'welcome_screen_activate' );
function welcome_screen_activate() {
set_transient( '_welcome_screen_activation_redirect', true, 30 );
}
add_action( 'admin_init', 'welcome_screen_do_activation_redirect' );
function welcome_screen_do_activation_redirect() {
// Bail if no activation redirect
if ( ! get_transient( '_welcome_screen_activation_redirect' ) ) {
return;
}
// Delete the redirect transient
delete_transient( '_welcome_screen_activation_redirect' );
// Bail if activating from network, or bulk
if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
return;
}
// Redirect to bbPress about page
wp_safe_redirect( add_query_arg( array( 'page' => 'welcome-to-js-footer' ), admin_url( 'index.php' ) ) );
}
add_action('admin_menu', 'welcome_screen_pages');
function welcome_screen_pages() {
add_dashboard_page(
'Welcome To JS to the footer',
'Welcome To JS to the footer',
'read',
'welcome-to-js-footer',
'welcome_screen_content'
);
}
function welcome_screen_content() {
?>
<div class="wrap">
<h1 stye="font-size:15px;">Welcome To JS to the footer</h1>
 
<h4>
This plugin moves all of your JS and Scripts to the footer and loads your CSS and images first. If a website is loaded in a browser using HTTP, scripts don’t allow parallel downloads, this way a JavaScript file may take 4-5 seconds to load, blocking your CSS files. This way your website speeds up and you avoid a flickr of un-styled content.
</h4>
<H4>IMPORTANT: Some plugins may conflict with this or will not load correctly! This is due to the plugins specified script load order, and not JS to the Foot's fault. Please report any findings to the plugin support page!</H4>
<h3>Changelog:</h3>
<h4>1.0<br>
- Initial release.
</h4>
<p>If you like my work, consider <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8DH2U6LZZZLBL" target="blank">buying me a beer</a> or contact me at <a href="http://kosvrouvas.com/" target="blank">kosvrouvas.com</a>.
</div>
<?php
}
add_action( 'admin_head', 'welcome_screen_remove_menus' );
function welcome_screen_remove_menus() {
remove_submenu_page( 'index.php', 'welcome-to-js-footer' );
}

?>