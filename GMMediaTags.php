<?php
/**
 * Plugin Name: GM Media Tags
 *
 * Description: Register a taxonomy 'Media Tag' for attachment and gives the ability to bulk assignment attachment taxonomies in backend, even for taxonomies not registered by plugin.
 * Version: 0.1.1
 * Author: Giuseppe Mazzapica
 * Requires at least: 3.5
 * Tested up to: 3.6
 *
 * Text Domain: gmmediatags
 * Domain Path: /lang/
 *
 * @package GMMediaTags
 * @category Media
 * @author Giuseppe Mazzapica
 *
 */


add_action('after_setup_theme', 'init_GMMediaTags', 30);

function init_GMMediaTags() {
	
	if ( ! defined('ABSPATH') ) die();
	
	define('GMMEDIATAGSPATH', plugin_dir_path( __FILE__ ) );
	define('GMMEDIATAGSURL', plugins_url( '/' , __FILE__ ) );
	
	require_once( GMMEDIATAGSPATH . 'inc/GMMediaTags.class.php');
	
	// allow disabling plugin from another plugin or theme via filter
	if ( apply_filters('gm_mediatags_enable', true) ) {
		load_plugin_textdomain('gmmediatags', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		GMMediaTags::init();
	}
	
}