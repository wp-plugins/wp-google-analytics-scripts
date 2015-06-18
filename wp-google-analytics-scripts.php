<?php
/*
Plugin Name: WP Google Analytics Scripts
Plugin URI: http://www.vivacityinfotech.net
Description: WP Google Analytics Scripts generates detailed statistics about a website's traffic and traffic sources and measures conversions and sales.
Author: Vivacity Infotech Pvt. Ltd.
Version: 1.2
Author URI: http://www.vivacityinfotech.net
Requires at least: 3.8
Text Domain: wp-google-analytics-scripts
*/
/*
Copyright 2014  Vivacity InfoTech Pvt. Ltd.  (email : support@vivacityinfotech.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.


    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
add_filter('plugin_row_meta', 'RegisterPluginLinks',10, 2);
function RegisterPluginLinks($links, $file) {
	if ( strpos( $file, 'wp-google-analytics-scripts.php' ) !== false ) {
		$links[] = '<a href="https://wordpress.org/plugins/wp-google-analytics-scripts/faq/">FAQ</a>';
		$links[] = '<a href="mailto:support@vivacityinfotech.com">Support</a>';
		$links[] = '<a href="http://bit.ly/1icl56K">Donate</a>';
	}
	return $links;
}

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

	add_settings_section( 'Analytics_section', '', 'Analytics_block', __FILE__ );
add_settings_field( 'Analytics_selectbox', 'Google Analytics scripts selector',  'Analytics_selectbox', __FILE__, 'Analytics_section' );
	add_settings_field( 'Analytics_inputbox','Google Analytics Footer Scripts', 'Analytics_inputbox', __FILE__, 'Analytics_section' );
	add_settings_field( 'Analytics_footerbox_track', 'Google Analytics UA Tracking ID', 'Analytics_footerbox_track', __FILE__, 'Analytics_section' );
	add_settings_field( 'Analytics_do_not_track','Visits to ignore:','field_do_not_track',  __FILE__, 'Analytics_section' );
}

add_action('admin_init', 'Analytics_settings_register');
 function field_do_not_track() {
 	$options  = get_option('Analytics_setting');
 
$field_value   = isset( $options['ignore_admin_area'] ) ? $options['ignore_admin_area'] : '';
//echo $field_value;
	/*	$do_not_track = array(
				'ignore_admin_area'       => __( 'Do not log anything in the admin area', 'wp-google-analytics' ),
			); */
		global $wp_roles;
		foreach( $wp_roles->roles as $role => $role_info ) {
			$do_not_track['ignore_role_' . $role] = sprintf( __( 'Do not log %s when logged in', 'wp-google-analytics' ), rtrim( $role_info['name'], 's' ) );
		}
		foreach( $do_not_track as $id => $label ) {
$field_value   = isset( $options[$id] ) ? $options[$id] : '';
			$checked='';
			if($field_value=="true"){$checked= "checked";} 
			echo '<label for="Analytics_setting_' . $id . '">';
			echo '<input id="Analytics_setting_' . $id . '" type="checkbox" name="Analytics_setting[' . $id . ']" value="true" '.$checked.'/>';
			echo '&nbsp;&nbsp;' . $label;
			echo '</label><br />';
		}
	}
function Analytics_block() {} 

function Analytics_selectbox() {
	$options  = get_option('Analytics_setting');
	$field_value   = isset( $options['scripts_selector'] ) ? $options['scripts_selector'] : ''; ?>
	
	<select id="scripts-selector" class="scripts-selector" name="Analytics_setting[scripts_selector]"> 
	<option value="0" <?php if ($field_value=="") echo "selected"; ?>>select an option </option>
	<option value="1" <?php if ($field_value=="1") echo "selected"; ?>>Google Analytics Footer Scripts</option>
	<option value="2" <?php if ($field_value=="2") echo "selected"; ?>>Google Analytics UA Tracking ID </option>
	</select>
	
	<?php
	
	}
function Analytics_inputbox() {
	
	$options  = get_option('Analytics_setting');
	$field_value   = isset( $options['footer_scripts_input'] ) ? $options['footer_scripts_input'] : ''; ?>
	
	<textarea id="ftr-scripts-input" name="Analytics_setting[footer_scripts_input]" placeholder=" Analytics Footer Scripts" style="width:300px; height: 200px;" ><?php echo esc_html( $field_value ) ?></textarea>
	<p class="description"><?php echo 'Enter your Google Analytic Script .';?></p>
	<?php
}
function Analytics_footerbox_track() {
	$options  = get_option('Analytics_setting');
	
	$field_value   = isset( $options['footer_trackid_input'] ) ? $options['footer_trackid_input'] : ''; ?>
	<input id="ftr-trackid-input" name="Analytics_setting[footer_trackid_input]" placeholder="UA-2986XXXX-X." style="width:300px; " value="<?php echo esc_html( $field_value ) ?>"/>
	<p class="description"><?php echo 'Enter your Google UA Code/ID ( "UA-2986XXXX-X" ) here.';?></p>
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
		<form name="myform" class="myform" action="options.php" method="post" enctype="multipart/form-data">
			<?php settings_fields('Analytics_settings_page'); ?>
			<?php do_settings_sections( __FILE__ ); ?>
			<p class="submit">
				<input name="scripts-submit" type="submit" class="button-primary" id="submit" value='Update Scripts' onclick="" />
			</p>
		</form>
	</div>
	</div>
	
 <div class="right">
	<center>
<div class="bottom">
		    <h3 id="download-comments-wvpd" class="title"><?php _e( 'Download Free Plugins', 'wvpd' ); ?></h3>
		     
     <div id="downloadtbl-comments-wvpd" class="togglediv">  
	<h3 class="company">
<p> Vivacity InfoTech Pvt. Ltd. is an ISO 9001:2008 Certified Company is a Global IT Services company with expertise in outsourced product development and custom software development with focusing on software development, IT consulting, customized development.We have 200+ satisfied clients worldwide.</p>	
<?php _e( 'Our Top 5 Latest Plugins', 'wvpd' ); ?>:
</h3>
<ul class="">
<li><a target="_blank" href="https://wordpress.org/plugins/woocommerce-social-buttons/">Woocommerce Social Buttons</a></li>
<li><a target="_blank" href="https://wordpress.org/plugins/vi-random-posts-widget/">Vi Random Post Widget</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-infinite-scroll-posts/">WP EasyScroll Posts</a></li>
<li><a target="_blank" href="https://wordpress.org/plugins/buddypress-social-icons/">BuddyPress Social Icons</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-fb-share-like-button/">WP Facebook Like Button</a></li>
</ul>
  </div> 
</div>		
<div class="bottom">
		    <h3 id="donatehere-comments-wvpd" class="title"><?php _e( 'Donate Here', 'wvpd' ); ?></h3>
     <div id="donateheretbl-comments-wvpd" class="togglediv">  
     <p><?php _e( 'If you want to donate , please click on below image.', 'wvpd' ); ?></p>
	<a href="http://bit.ly/1icl56K" target="_blank"><img class="donate" src="<?php echo plugins_url( 'images/paypal.gif' , __FILE__ ); ?>" width="150" height="50" title="<?php _e( 'Donate Here', 'wvpd' ); ?>"></a>		
  </div> 
</div>	
<div class="bottom">
		    <h3 id="donatehere-comments-wvpd" class="title"><?php _e( 'Woocommerce Frontend Plugin', 'wvpd' ); ?></h3>
     <div id="donateheretbl-comments-wvpd" class="togglediv">  
     <p><?php _e( 'If you want to purchase , please click on below image.', 'wvpd' ); ?></p>
	<a href="http://bit.ly/1HZGRBg" target="_blank"><img class="donate" src="<?php echo plugins_url( 'images/woo_frontend_banner.png' , __FILE__ ); ?>" width="336" height="280" title="<?php _e( 'Donate Here', 'wvpd' ); ?>"></a>		
  </div> 
</div>
<div class="bottom">
		    <h3 id="donatehere-comments-wvpd" class="title"><?php _e( 'Blue Frog Template', 'wvpd' ); ?></h3>
     <div id="donateheretbl-comments-wvpd" class="togglediv">  
     <p><?php _e( 'If you want to purchase , please click on below image.', 'wvpd' ); ?></p>
	<a href="http://bit.ly/1Gwp4Vv" target="_blank"><img class="donate" src="<?php echo plugins_url( 'images/blue_frog_banner.png' , __FILE__ ); ?>" width="336" height="280" title="<?php _e( 'Donate Here', 'wvpd' ); ?>"></a>		
  </div> 
</div>
	</center>
</div>	
	
	
			</div>
	<?php
}


add_action('wp_footer','viva_ua_code');


function viva_ua_code() {

$current_user = wp_get_current_user();
 	$options  = get_option('Analytics_setting');
if (!isset($options['ignore_role_administrator'])) {$options['ignore_role_administrator'] = "";}
if (!isset($options['ignore_role_editor'])) {$options['ignore_role_editor'] = "";}
if (!isset($options['ignore_role_subscriber'])) {$options['ignore_role_subscriber'] = "";}
if (!isset($options['ignore_role_contributor'])) {$options['ignore_role_contributor'] = "";}
if (!isset($options['ignore_role_author'])) {$options['ignore_role_author'] = "";}
 	$user = new WP_User( get_current_user_id() );
//	echo '<pre>'; print_r($options);echo '</pre>';
$user_role = $user->roles[0];
//echo $user_role;
// $screen = get_current_screen();
//echo '<pre>'; 
 //print_r($screen);

	if( ($options['ignore_role_administrator'] && $user_role == 'administrator') || ($options['ignore_role_editor'] && $user_role == 'editor') || ($options['ignore_role_subscriber'] && $user_role == 'subscriber') || ($options['ignore_role_contributor'] && $user_role == 'contributor') || ($options['ignore_role_author'] && $user_role == 'author')) 
		return 0;
		
	$ua_code = get_option( 'Analytics_setting' );
	 $ua_id = $ua_code['footer_trackid_input'];
	 $ua_script = $ua_code['footer_scripts_input'];
	$home_url = get_home_url();
	$find = array( 'http://', 'https://', 'www.');
	$replace = '';
	$output = str_replace( $find, $replace, $home_url );

	if(($ua_id !== '') && ($ua_script == '') ) {
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
