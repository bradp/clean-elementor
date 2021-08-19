<?php
/**
 * Plugin Name: Clean Elementor
 * Description: Set Elementor settings and clean up the dashboard.
 * Version:     1.0.1
 * Author:      Brad Parbs
 * Author URI:  https://bradparbs.com/
 * License:     GPLv2
 * Text Domain: clean-elementor
 * Domain Path: /lang/
 *
 * @package clean-elementor
 */

namespace CleanElementor;

// Kick it all off!
add_action( 'plugins_loaded', __NAMESPACE__ . '\\add_options_filters' );
add_action( 'get_user_metadata', __NAMESPACE__ . '\\add_user_meta_filters', 9, 4 );
add_action( 'admin_menu', __NAMESPACE__ . '\\remove_settings_page', 21 );

/**
 * Filter elementor options to not be user-changeable.
 */
function add_options_filters() {
	$options = [
		'elementor_cpt_support'                           => [ 'post', 'page' ],   // General: Post Types.
		'elementor_disable_color_schemes'                 => 'yes',                // General: Disable Default Colors.
		'elementor_disable_typography_schemes'            => 'yes',                // General: Disable Default Fonts.
		'elementor_allow_tracking'                        => 'no',                 // General: Usage Data Sharing.
		'elementor_css_print_method'                      => 'external',           // Advanced: CSS Print Method.
		'elementor_editor_break_lines'                    => '',                   // Advanced: Switch Editor Loader Method.
		'elementor_unfiltered_files_upload'               => '',                   // Advanced: Enable Unfiltered File Uploads.
		'elementor_font_display'                          => 'swap',               // Advanced: Google Fonts Load.
		'elementor_load_fa4_shim'                         => '',                   // Advanced: Load Font Awesome 4 shim.
		'elementor_experiment-e_dom_optimization'         => 'active',             // Experiments: Optimized DOM Output.
		'elementor_experiment-e_optimized_assets_loading' => 'active',             // Experiments: Improved Asset Loading.
		'elementor_experiment-a11y_improvements'          => 'active',             // Experiments: Accessibility Improvements.
		'elementor_experiment-e_import_export'            => 'inactive',           // Experiments: Import Export Template Kit.
		'elementor_experiment-landing-pages'              => 'active',             // Experiments: Landing Pages.
	];

	// Dynamically add each filter, always outputting the value we want.
	foreach ( $options as $name => $value ) {
		add_filter(
			"pre_option_{$name}",
			function () use ( $value ) {
				return $value;
			}
		);
	}
}

/**
 * Filter the user meta for notices.
 *
 * @param mixed  $value     The value to return, either a single metadata value or an array
 *                          of values depending on the value of `$single`. Default null.
 * @param int    $object_id ID of the object metadata is for.
 * @param string $meta_key  Metadata key.
 * @param bool   $single    Whether to return only the first value of the specified `$meta_key`.
 *
 * @return mixed Single metadata value, or array of values. Null if the value does not exist.
 */
function add_user_meta_filters( $value, $object_id, $meta_key, $single ) {
	if ( 'elementor_admin_notices' !== $meta_key ) {
		return $value;
	}

	$notices = [ [ 'elementor_dev_promote' => 'true' ] ]; // Stringed on purpose.

	// Return the value, respecting the $single param.
	return $single ? $notices : [ $notices ];
}


/**
 * Remove the Elementor menu.
 */
function remove_settings_page() {
	remove_menu_page( 'elementor' );
}
