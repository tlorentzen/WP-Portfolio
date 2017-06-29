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
        'name'              => _x( 'Types', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Type', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Types', 'textdomain' ),
        'all_items'         => __( 'All Types', 'textdomain' ),
        'parent_item'       => __( 'Parent Type', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Type:', 'textdomain' ),
        'edit_item'         => __( 'Edit Type', 'textdomain' ),
        'update_item'       => __( 'Update Type', 'textdomain' ),
        'add_new_item'      => __( 'Add New Type', 'textdomain' ),
        'new_item_name'     => __( 'New Type Name', 'textdomain' ),
        'menu_name'         => __( 'Types', 'textdomain' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'type' ),
    );

    register_taxonomy('type', array( 'portfolio' ), $args);

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
        'taxonomies'          => array( 'type' ),
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


function portfolio_metabox()
{
    global $post;
    echo '<input type="hidden" name="portfolio_meta_noncename" id="portfolio_meta_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ) .'" />';

    $meta = get_post_meta(get_the_ID());
    echo '<h4 style="margin-bottom: 5px;">Client:</h4>';
    echo '<input type="text" name="client" value="'.$meta['client'][0].'" class="widefat" />';
    echo '<h4 style="margin-bottom: 5px;">Client website:</h4>';
    echo '<input type="text" name="client-website" value="'.$meta['client-website'][0].'" class="widefat" />';
    echo '<h4 style="margin-bottom: 5px;">Project wesbite:</h4>';
    echo '<input type="text" name="project-website" value="'.$meta['project-website'][0].'" class="widefat" />';
    echo '<h4 style="margin-bottom: 5px;">Time period:</h4>';
    echo '<input type="text" name="time-period" value="'.$meta['time-period'][0].'" class="widefat" />';
}

function init_metaboxes()
{
    add_meta_box('portfolio_metabox', 'Project details', 'portfolio_metabox', 'portfolio', 'side', 'default');
}

add_action( 'add_meta_boxes', 'init_metaboxes' );


// Save the Metabox Data
function tl_save_portfolio_meta($post_id, $post) {

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['portfolio_meta_noncename'], plugin_basename(__FILE__) )) {
        return $post->ID;
    }

    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;

    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.

    $client          = $_POST['client'];
    $client_website  = $_POST['client-website'];
    $project_website = $_POST['project-website'];
    $time_period     = $_POST['time-period'];

    if( $post->post_type == 'revision' ) return; // Don't store custom data twice

    // Save client
    if(get_post_meta($post->ID, 'client', FALSE)) { // If the custom field already has a value
        update_post_meta($post->ID, 'client', $client);
    } else { // If the custom field doesn't have a value
        add_post_meta($post->ID, 'client', $client);
    }
    if(!$client) delete_post_meta($post->ID, 'client'); // Delete if blank

    // Save client website
    if(get_post_meta($post->ID, 'client-website', FALSE)) { // If the custom field already has a value
        update_post_meta($post->ID, 'client-website', $client_website);
    } else { // If the custom field doesn't have a value
        add_post_meta($post->ID, 'client-website', $client_website);
    }
    if(!$client_website) delete_post_meta($post->ID, 'client-website'); // Delete if blank

    // Save project website
    if(get_post_meta($post->ID, 'project-website', FALSE)) { // If the custom field already has a value
        update_post_meta($post->ID, 'project-website', $project_website);
    } else { // If the custom field doesn't have a value
        add_post_meta($post->ID, 'project-website', $project_website);
    }
    if(!$project_website) delete_post_meta($post->ID, 'project-website'); // Delete if blank

    // Save time period
    if(get_post_meta($post->ID, 'time-period', FALSE)) { // If the custom field already has a value
        update_post_meta($post->ID, 'time-period', $time_period);
    } else { // If the custom field doesn't have a value
        add_post_meta($post->ID, 'time-period', $time_period);
    }
    if(!$time_period) delete_post_meta($post->ID, 'time-period'); // Delete if blank

}

add_action('save_post_portfolio', 'tl_save_portfolio_meta', 1, 2); // save the custom fields



