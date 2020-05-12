<?php
/**
 * Plugin Name: Fields and Actions for Elementor Forms
 * Description: The Best field collection plugin for Elementor.
 * Plugin URI:  https://elementor.com/
 * Version:     1.0
 * Author:      Josh Marom
 * Author URI:  http://josh.co.il/
 * Text Domain: e-signature
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

final class Elementor_Form_Fields {

	/**
	 * Plugin Version
	 *
	 * @since 1.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0';

	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	const MINIMUM_PHP_VERSION = '7.0';

	public function __construct() {
		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'e-signature' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function init() {
/*
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/init' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );

			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );

			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );

			return;
		}
*/
		// Once we get here, We have passed all validation checks so we can safely include our plugin
		add_action( 'elementor_pro/init', function() {
			include_once 'fields/wysiwyg.php';
			include_once 'fields/signature.php';

			new Wysiwyg();
			new Signature();
		} );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf( /* translators: 1: Plugin name 2: Elementor */ esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'e-signature' ), '<strong>' . esc_html__( 'Signature Field', 'e-signature' ) . '</strong>', '<strong>' . esc_html__( 'Elementor', 'e-signature' ) . '</strong>' );

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf( /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */ esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'e-signature' ), '<strong>' . esc_html__( 'Elementor Hello World', 'e-signature' ) . '</strong>', '<strong>' . esc_html__( 'Elementor', 'e-signature' ) . '</strong>', self::MINIMUM_ELEMENTOR_VERSION );

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf( /* translators: 1: Plugin name 2: PHP 3: Required PHP version */ esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'e-signature' ), '<strong>' . esc_html__( 'Elementor Hello World', 'e-signature' ) . '</strong>', '<strong>' . esc_html__( 'PHP', 'e-signature' ) . '</strong>', self::MINIMUM_PHP_VERSION );

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

// Instantiate Elementor_Hello_World.
new Elementor_Form_Fields();
