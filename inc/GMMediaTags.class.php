<?php
/**
 * GMMediaTags class
 *
 * @package GMMediaTags
 * @author Giuseppe Mazzapica
 *
 */
class GMMediaTags {
	
	
	/**
	* Plugin version
	*
	* @since	0.1.0
	*
	* @access	protected
	*
	* @var	string
	*/
	protected static $version = '0.1.0';
	
	
	
	
	/**
	* Prevent register method from run more than one time. 
	*
	* @since	0.1.0
	*
	* @access	protected
	*
	* @var	bool
	*/
	protected static $done = false;
	
	
	
	
	/**
	* Default arguments to register taxonomies.
	*
	* @since	0.1.0
	*
	* @access	protected
	*
	* @var	array
	*/
	protected static $defaults;
	
	
	
	
	/**
	* Registered taxonomies names
	*
	* @since	0.1.0
	*
	* @access	public
	*
	* @var	array
	*/
	protected static $registered = array();
	
	
	
	
	/**
	 * Constructor. Doing nothing
	 *
	 * @since	0.1.0
	 *
	 * @access	public
	 * @return	null
	 *
	 */
	function __construct() {
		_doing_it_wrong( 'GMMediaTags::__construct', 'GMMediaTags Class is intented to be used statically.' );
	}
	

	
	
	/**
	 * Initialize the plugin. Run on 'after_setup_theme' hook
	 *
	 * @since	0.1.0
	 *
	 * @access	public
	 * @return	null
	 *
	 */
	static function init() {
		
		if ( ! defined('GMMEDIATAGSPATH') ) die();
		
		self::$defaults = array(
			'label'					=>	_x('Media Tags', 'taxonomy general label', 'gmmediatags'),
			'public'				=>	true,
			'show_ui'				=>	true,
			'show_in_nav_menus'		=>	false,
			'show_tagcloud'			=>	false,
			'show_admin_column'		=>	true,
			'hierarchical'			=>	false,
			'update_count_callback'	=>	'_update_generic_term_count',
			'rewrite'				=>	true
		);
		
		add_action('init', array(__CLASS__, 'register'), 999 );
		add_action('admin_init', array(__CLASS__, 'admin_init') );
		
	}
	
	
	
	
	/**
	 * Register the taxonomies. Run on 'init' hook
	 *
	 * @since	0.1.0
	 *
	 * @access	public
	 * @return	null
	 *
	 */
	static function register() {
		
		if ( ! apply_filters('gm_mediatags_enable_register', true) ) return;
		
		if ( self::$done ) {
			_doing_it_wrong( 'GMMediaTags::register', 'GMMediaTags register method have to run only one time.' );
			return;
		}
		
		if ( empty(self::$defaults) ) {
			_doing_it_wrong( 'GMMediaTags::register', 'GMMediaTags register method must be called after class initizialization.' );
			return;
		}
		
		$taxonomies = apply_filters('gm_mediatags_tax', array( 'media_tag' => self::$defaults ) );
		
		self::$done = true;
		
		self::$registered = get_object_taxonomies('attachment');
		
		if ( ! empty( $taxonomies) ) { foreach ( $taxonomies as $tax => $args ) {
			if ( in_array($tax, self::$registered) ) continue;
			if ( $tax != 'media_tag' ) {
				if ( ! isset($args['label']) || empty($args['label']) ) $args['label'] = $tax;
				$tax = sanitize_title( $tax );
				$default_labels = array(
					'name'					=> _x($args['label'], 'taxonomy general name label', 'gmmediatags'),
					'singular_name'			=> _x($args['label'], 'taxonomy singular name label', 'gmmediatags'),
					'menu_name'				=> _x($args['label'], 'taxonomy menu name label', 'gmmediatags'),
					'all_items'				=> _x('All Items', 'taxonomy all items label', 'gmmediatags'),
					'edit_item'				=> _x('Edit Item', 'taxonomy edit item label', 'gmmediatags'),
					'view_item'				=> _x('View Item', 'taxonomy view item label', 'gmmediatags'),
					'update_item'			=> _x('Update Item', 'taxonomy update item label', 'gmmediatags'),
					'add_new_item'			=> _x('Add New Item', 'taxonomy add new item label', 'gmmediatags'),
					'new_item_name'			=> _x('New Item Name', 'taxonomy new item name label', 'gmmediatags'),
					'parent_item'			=> _x('Parent Item', 'taxonomy parent item label', 'gmmediatags'),
					'parent_item_colon'		=> _x('Parent:', 'taxonomy parent item colon label', 'gmmediatags'),
					'search_items'			=> _x('Search Items', 'taxonomy search items label', 'gmmediatags'),
					'popular_items'			=> _x('Popular Items', 'taxonomy popular items label', 'gmmediatags'),
					'add_or_remove_items'	=> _x('Add or Remove Items', 'taxonomy add or remove items label', 'gmmediatags'),
					'choose_from_most_used'	=> _x('Choose from Most Used Items', 'taxonomy choose from most used label', 'gmmediatags'),
					'not_found'				=> _x('No Items Found', 'taxonomy not found label', 'gmmediatags'),
				);
				$args = wp_parse_args($args, self::$defaults);
				if ( ! isset($args['labels']) || empty($args['labels']) ) $args['labels'] = array();
				$args['labels'] = wp_parse_args($args['labels'], $default_labels);
			} else {
				$labels = array(
					'name'					=> _x('Media Tags', 'taxonomy general name label', 'gmmediatags'),
					'singular_name'			=> _x('Media Tag', 'taxonomy singular name label', 'gmmediatags'),
					'menu_name'				=> _x('Media Tags', 'taxonomy menu name label', 'gmmediatags'),
					'all_items'				=> _x('All Media Tags', 'taxonomy all items label', 'gmmediatags'),
					'edit_item'				=> _x('Edit Media Tag', 'taxonomy edit item label', 'gmmediatags'),
					'view_item'				=> _x('View Media Tag', 'taxonomy view item label', 'gmmediatags'),
					'update_item'			=> _x('Update Media Tag', 'taxonomy update item label', 'gmmediatags'),
					'add_new_item'			=> _x('Add New Media Tag', 'taxonomy add new item label', 'gmmediatags'),
					'new_item_name'			=> _x('New Media Tag Name', 'taxonomy new item name label', 'gmmediatags'),
					'parent_item'			=> _x('Parent Media Tag', 'taxonomy parent item label', 'gmmediatags'),
					'parent_item_colon'		=> _x('Parent:', 'taxonomy parent item colon label', 'gmmediatags'),
					'search_items'			=> _x('Search Media Tags', 'taxonomy search items label', 'gmmediatags'),
					'popular_items'			=> _x('Popular Media Tags', 'taxonomy popular items label', 'gmmediatags'),
					'add_or_remove_items'	=> _x('Add or Remove Media Tags', 'taxonomy add or remove items label', 'gmmediatags'),
					'choose_from_most_used'	=> _x('Choose from Most Used Media Tags', 'taxonomy choose from most used label', 'gmmediatags'),
					'not_found'				=> _x('No Media Tags Found', 'taxonomy not found label', 'gmmediatags'),
				);
				$args['labels'] = apply_filters('gm_mediatags_default_labels', $labels);
				$args = apply_filters('gm_mediatags_default_args', $args);
			}
			if ( $tax ) {
				register_taxonomy( $tax, 'attachment', $args );
				self::$registered[] = $tax;
			}
		} }
		
	}
	
	
	
	
	/**
	 * Add the action for backend. Run on 'admin_init' hook
	 *
	 * @since	0.1.0
	 *
	 * @access	public
	 * @return	null
	 *
	 */
	static function admin_init() {
		require( GMMEDIATAGSPATH . 'inc/GMMediaTagsAdmin.class.php');
		GMMediaTagsAdmin::init();
	}

}