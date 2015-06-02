<?php
/**
 * Sets up the admin functionality for the framework.
 *
 * @package   Cherry_Framework
 * @version   4.0.0
 * @author    Cherry Team <support@cherryframework.com>
 * @copyright Copyright (c) 2012 - 2015, Cherry Team
 * @link      http://www.cherryframework.com/
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Cherry_Admin {

	/**
	 * Holds the instances of this class.
	 *
	 * @since 4.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Initialize the loading admin scripts & styles. Adding the meta boxes.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Loads post meta boxes on the post editing screen.
		add_action( 'load-post.php',     array( $this, 'load_post_meta_boxes' ) );
		add_action( 'load-post-new.php', array( $this, 'load_post_meta_boxes' ) );
	}

	/**
	 * Loads admin-specific javascript.
	 *
	 * @since 4.0.0
	 */
	public function enqueue_admin_scripts( $hook_suffix ) {

		if ( 'toplevel_page_cherry-options-page' == $hook_suffix ) {

			wp_enqueue_media();
			wp_enqueue_script( 'admin-interface', trailingslashit( CHERRY_URI ) . 'admin/assets/js/admin-interface.js', array( 'jquery' ), CHERRY_VERSION, true );

			$messages = array(
				'no_file'         => __( 'Please, select import file', 'cherry' ),
				'invalid_type'    => __( 'Invalid file type', 'cherry' ),
				'success'         => __( 'Cherry Options have been imported.<br>Page will be refreshed to apply changes...', 'cherry' ),
				'section_restore' => __( 'section have been restored.<br>Page will be refreshed to apply changes...', 'cherry' ),
				'options_restore' => __( 'All options have been restored', 'cherry' ),
				'section_loaded'  => __( 'options have been loaded.', 'cherry' ),
				'redirect_url'    => menu_page_url( 'cherry-options', false ),
			);

			wp_localize_script( 'admin-interface', 'cherry_options_page_data', $messages );
		}

		wp_enqueue_style( 'admin-interface', trailingslashit( CHERRY_URI ) . 'admin/assets/css/admin-interface.css', array(), CHERRY_VERSION, 'all' );
		wp_enqueue_style( 'cherry-ui-elements', trailingslashit( CHERRY_URI ) . 'admin/assets/css/cherry-ui-elements.css', array(), CHERRY_VERSION, 'all' );
	}

	/**
	 * Loads custom meta boxes.
	 *
	 * @since 4.0.0
	 */
	public function load_post_meta_boxes() {
		$screen    = get_current_screen();
		$post_type = $screen->post_type;

		if ( !empty( $post_type ) && post_type_supports( $post_type, 'cherry-grid-type' ) ) {
			require_once( trailingslashit( CHERRY_ADMIN ) . 'class-cherry-grid-type.php' );
		}

		if ( !empty( $post_type ) && post_type_supports( $post_type, 'cherry-layouts' ) ) {
			require_once( trailingslashit( CHERRY_ADMIN ) . 'class-cherry-layouts.php' );
		}

		if ( !empty( $post_type ) && post_type_supports( $post_type, 'cherry-post-style' ) ) {
			require_once( trailingslashit( CHERRY_ADMIN ) . 'class-cherry-post-style.php' );
		}
	}

	/**
	 * Returns the instance.
	 *
	 * @since  4.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

Cherry_Admin::get_instance();

// Include theme options page.
global $cherry_options_framework;
$cherry_options_framework = new Cherry_Options_Framework;
$options_framework_admin = new Cherry_Options_Framework_Admin;