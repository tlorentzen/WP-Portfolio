<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tlorentzen.net
 * @since             1.0.0
 * @package           Portfolio WP
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Portfolio
 * Plugin URI:        https://tlorentzen.net
 * Description:       A simple and lightweight portfolio plugin for WordPress.
 * Version:           1.0.0
 * Author:            Thomas Lorentzen
 * Author URI:        https://tlorentzen.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-portfolio
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_portfolio_plugin() {

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_portfolio_plugin() {

}

register_activation_hook( __FILE__, 'activate_portfolio_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_portfolio_plugin' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function create_portfolio_posttype() {

    $labels = array(
        'name'              => _x( 'Groups', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Group', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Groups', 'textdomain' ),
        'all_items'         => __( 'All Groups', 'textdomain' ),
        'parent_item'       => __( 'Parent Group', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Group:', 'textdomain' ),
        'edit_item'         => __( 'Edit Group', 'textdomain' ),
        'update_item'       => __( 'Update Group', 'textdomain' ),
        'add_new_item'      => __( 'Add New Group', 'textdomain' ),
        'new_item_name'     => __( 'New Group Name', 'textdomain' ),
        'menu_name'         => __( 'Groups', 'textdomain' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'group' ),
    );

    register_taxonomy('group', array( 'portfolio' ), $args);

    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Portfolio', 'Post Type General Name', 'twentythirteen' ),
        'singular_name'       => _x( 'Portfolio', 'Post Type Singular Name', 'twentythirteen' ),
        'menu_name'           => __( 'Portfolio', 'twentythirteen' ),
        'parent_item_colon'   => __( 'Parent Portfolio', 'twentythirteen' ),
        'all_items'           => __( 'All Portfolio', 'twentythirteen' ),
        'view_item'           => __( 'View Portfolio', 'twentythirteen' ),
        'add_new_item'        => __( 'Add New Portfolio', 'twentythirteen' ),
        'add_new'             => __( 'Add New', 'twentythirteen' ),
        'edit_item'           => __( 'Edit Portfolio', 'twentythirteen' ),
        'update_item'         => __( 'Update Portfolio', 'twentythirteen' ),
        'search_items'        => __( 'Search Portfolio', 'twentythirteen' ),
        'not_found'           => __( 'Not Found', 'twentythirteen' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
    );

    $args = array(
        'label'               => __( 'portfolio', 'twentythirteen' ),
        'description'         => __( 'Events from Flowland.', 'twentythirteen' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail'),
        'taxonomies'          => array( 'groups' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => false,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'menu_icon'           => 'dashicons-portfolio',
    );

    // Register portfolio post type.
    register_post_type('portfolio', $args);
}

add_action( 'init', 'create_portfolio_posttype' );