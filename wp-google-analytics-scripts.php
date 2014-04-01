<?php
/*
Plugin Name: WP Google Analytics Scripts
Plugin URI: http://vivacityinfotech.com/
Description: WP Google Analytics Scripts generates detailed statistics about a website's traffic and traffic sources and measures conversions and sales.
Author: vivacityinfotech
Version: 1.0
Author URI: http://vivacityinfotech.com/
Requires at least: 3.8
Text Domain: wp-google-analytics-scripts
License: vivacityinfotech
*/
function Analytics_settings_page( $links ) {
	$settings_block = '<a href="' . admin_url('admin.php?page=wp-google-analytics-scripts' ) .'">Settings</a>';
	array_unshift( $links, $settings_block);
	return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter( "plugin_action_links_$plugin", 'Analytics_settings_page' );

function Analytics_uninstall() {
	unregister_setting( 'Analytics_settings_page', 'Analytics_setting' );
}

register_uninstall_hook( __FILE__, 'Analytics_uninstall' );

function Analytics_footer() {
	ob_start();
		$options = get_option( 'Analytics_setting' );
		$sfhs_footer = isset( $options['footer_scripts_input'] ) ? $options['footer_scripts_input'] : '';
		echo "<script type=text/javascript>\n";
		echo $sfhs_footer;
		echo "\n</script>";
	echo ob_get_clean();
}

function Analytics_render() {
	$options = get_option( 'Analytics_setting' );

	if ( isset( $options['footer_scripts_input'] ) )
		add_action( 'wp_footer', 'Analytics_footer' );
}

add_action( 'init', 'Analytics_render' );

function Analytics_page_register() {
	add_menu_page( 'VIVA Plugins', 'VIVA Plugins', 'manage_options', 'viva_plugins', 'Analytics_rendepage_submenu', plugins_url( '/images/vivacity_logo.png' ,__FILE__), 1001 );
	add_submenu_page( 'viva_plugins','Add Custom Scripts','WP Google Analytics Scripts', 'manage_options', "wp-google-analytics-scripts", 'Analytics_rendepage_submenu' );
}

add_action( 'admin_menu', 'Analytics_page_register' );

function Analytics_settings_register() {
	register_setting( 'Analytics_settings_page', 'Analytics_setting' );

	add_settings_section( 'Analytics_section', __( '', 'rt_polls' ), 'Analytics_block', __FILE__ );

	add_settings_field( 'Analytics_inputbox', __( 'Google Analytics Footer Scripts', 'rt_polls' ), 'Analytics_inputbox', __FILE__, 'Analytics_section' );

	add_settings_field( 'Analytics_footerbox_track', __( 'Google Analytics UA Tracking ID', 'rt_polls' ), 'Analytics_footerbox_track', __FILE__, 'Analytics_section' );
}

add_action('admin_init', 'Analytics_settings_register');

function Analytics_block() {} 


function Analytics_inputbox() {
	
	$options  = get_option('Analytics_setting');
	$field_value   = isset( $options['footer_scripts_input'] ) ? $options['footer_scripts_input'] : ''; ?>
	
	<textarea id="footer-scripts-input" name="Analytics_setting[footer_scripts_input]" placeholder=" Analytics Footer Scripts" style="width:300px; height: 200px;" ><?php echo esc_html( $field_value ) ?></textarea>
	<p class="description"><?php echo 'Enter your Google Analytic Script .';?></p>
	<?php
}
function Analytics_footerbox_track() {
	$options  = get_option('Analytics_setting');
	
	$field_value   = isset( $options['footer_trackid_input'] ) ? $options['footer_trackid_input'] : ''; ?>
	<input id="footer-trackid-input" name="Analytics_setting[footer_trackid_input]" placeholder="UA-2986XXXX-X." style="width:300px; " value="<?php echo esc_html( $field_value ) ?>"/>
	<p class="description"><?php echo 'Enter your Google UA Code/ID here.';?></p>
	<?php
}
add_action('admin_init','enqueue_styles');
function enqueue_styles()
{
	wp_enqueue_style('style_plugin',plugins_url( 'css/plugin_style.css' , __FILE__ ) );	
	wp_enqueue_script('script_plugin',plugins_url( 'js/script.js' , __FILE__ ) );	
	
	}
function Analytics_rendepage_submenu() {
	if ( isset( $_GET['settings-updated'] ) ) : ?>
		<div id="message" class="updated"><p><?php _e( 'Scripts updated successfully.' ); ?></p></div>
	<?php endif; ?>
	<div class="wrap_main">
	<div class="postbox plugin_wrap left">
	<h3 class="hndle plugin_head"><?php echo 'Enter your Google Analytic Settings';?></h3>
	<div class="inside">
		<?php screen_icon(); ?>
		<h2><?php 'Add Custom Scripts Plugin'; ?></h2>
		<p><?php 'Add your own scripts (including Google Analytics) to your header or footer regardless of what theme you are using.' ?></p>
		<form name="myform" action="options.php" method="post" enctype="multipart/form-data">
			<?php settings_fields('Analytics_settings_page'); ?>
			<?php do_settings_sections( __FILE__ ); ?>
			<p class="submit">
				<input name="scripts-submit" type="submit" class="button-primary" value='Update Scripts' onclick="" />
			</p>
		</form>
	</div>
	</div>
	<div class="postbox right ads_bar">
			<h3 class="hndle" style="padding:10px;"><span>Follow Us</span></h3>
			<div class="inside">
			Please take the time to let us and others know about your experiences by leaving a review, so that we can improve the plugin for you and other users.
<br>
<h4>Want More?</h4>
If You Want more functionality or some modifications, just drop us a line what you want and We will try to add or modify the plugin functions.
			
			</div>
			</div>
			</div>
	<?php
}
add_action('wp_footer','viva_ua_code');
function viva_ua_code() {

	$ua_code = get_option( 'Analytics_setting' );
	$ua_id = $ua_code['footer_trackid_input'];
	$home_url = get_home_url();
	$find = array( 'http://', 'https://', 'www.');
	$replace = '';
	$output = str_replace( $find, $replace, $home_url );

	if($ua_id !== '') {
		echo "
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', '".$ua_id."', '".$output."');
	ga('send', 'pageview');
	</script>";
	}

}

