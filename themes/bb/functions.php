<?php
/**
 * Boreal Bees functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Boreal_Bees
 */

if ( ! defined( 'BB_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'BB_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bb_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Boreal Bees, use a find and replace
		* to change 'bb' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'bb', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary', 'bb' ),
			'header-menu' => __( 'Header Menu' ),
			'footer-menu' => __( 'Footer Menu' )
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	add_theme_support( 'wp-block-styles' );

	/**
	 * Add support for custom page title structure & styling (plugin).
	*/
	add_theme_support( 'wp-block-nicola-gutenberg-composite-title-block' );

}
add_action( 'after_setup_theme', 'bb_setup' );

/**
* Add theme support for WooCommerce
*/
function bb_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'bb_add_woocommerce_support' );
// remove WooCommerce breadcrumb
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bb_content_width() {
	// !CHANGE WIDTH TO MATCH FRONT END
	$GLOBALS['content_width'] = apply_filters( 'bb_content_width', 1280 );
}
add_action( 'after_setup_theme', 'bb_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bb_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'bb' ),
			'id'            => 'sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'bb' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Social', 'bb' ),
			'id'            => 'social',
			'description'   => esc_html__( 'Add widgets here.', 'bb' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Connect', 'bb' ),
			'id'            => 'connect',
			'description'   => esc_html__( 'Add widgets here.', 'bb' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Products', 'bb' ),
			'id'            => 'products',
			'description'   => esc_html__( 'Add widgets here.', 'bb' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'bb_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function bb_scripts() {

	wp_enqueue_style( 'bb-style', get_stylesheet_uri(), array(), BB_VERSION );
	wp_enqueue_style( 
        'app-style',
        get_template_directory_uri() . '/assets/css/app.css'
    );
	// register script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bb_scripts' );

/**
* Custom post type setup (Events page)
*/
function bb_event_page() {
	$labels = array(
		'name'               => _x( 'Events', 'post type general name' ),
		'singular_name'      => _x( 'Event', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'event' ),
		'add_new_item'       => __( 'Add New Event' ),
		'edit_item'          => __( 'Edit Event' ),
		'new_item'           => __( 'New Event' ),
		'all_items'          => __( 'All Events' ),
		'view_item'          => __( 'View Event' ),
		'search_items'       => __( 'Search Events' ),
		'not_found'          => __( 'No events found' ),
		'not_found_in_trash' => __( 'No events found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Events'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'A list of all future & past events held by Boreal Bees.',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'has_archive'   => true,
	);
	register_post_type( 'event', $args ); 
}
add_action( 'init', 'bb_event_page' );
/**
 * Add custom meta boxes for event
 */
// define meta box
add_action( 'add_meta_boxes', 'event_date_box' );
function event_date_box() {
    add_meta_box( 
        'event_date_box',
        __( 'Event Date', 'myplugin_textdomain' ),
        'event_date_box_content',
        'event',
        'side',
        'high'
    );
}
// define contents
function event_date_box_content( $post ) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'event_date_box_content_nonce' );
	echo '<label for="event_date_box"></label>';
	echo '<input type="date" id="event_date_box" name="event_date_box" />';
}
// define custom field
function event_date_box_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !wp_verify_nonce( $_POST['event_date_box_content_nonce'], plugin_basename( __FILE__ ) ) )
	return;

	if ( 'page' == $_POST['event'] ) {
	if ( !current_user_can( 'edit_page', $post_id ) )
	return;
	} else {
	if ( !current_user_can( 'edit_post', $post_id ) )
	return;
	}
	$event_date_box = $_POST['event_date_box'];
	update_post_meta( $post_id, 'event_date_box', $event_date_box );
}
add_action( 'save_post', 'event_date_box_save' );
/**
 * Update messages for custom post type (Events)
 */
function event_messages( $messages ) {
	global $post, $post_ID;
	$messages['event'] = array(
		0  => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Event updated. <a href="%s">View event</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Event updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Event restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Event published. <a href="%s">View event</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Event saved.'),
		8 => sprintf( __('Event submitted. <a target="_blank" href="%s">Preview event</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Event scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview event</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Event draft updated. <a target="_blank" href="%s">Preview event</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'event_messages' );

/**
 * Add contextual help for custom post type (Events)
 */
function bb_event_contextual_help( $contextual_help, $screen_id, $screen ) { 
if ( 'event' == $screen->id ) {

	$contextual_help = '<h2>Events</h2>
	<p>Events show the details of upcoming special events hosted by Boreal Bees. You can see a list of them on this page in reverse chronological order - the latest one we added is first.</p> 
	<p>You can view/edit the details of each event by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';

} elseif ( 'edit-event' == $screen->id ) {

	$contextual_help = '<h2>Editing events</h2>
	<p>This page allows you to view/modify event details. Please make sure to fill out the available boxes with the appropriate details and <strong>not</strong> add these details to the event description.</p>';

}
return $contextual_help;
}
add_action( 'contextual_help', 'bb_event_contextual_help', 10, 3 );

/**
 * Custom taxonomies for Events page
 */
function bb_taxonomies_event() {
	$labels = array(
		'name'              => _x( 'Event Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Event Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Event Categories' ),
		'all_items'         => __( 'All Event Categories' ),
		'parent_item'       => __( 'Parent Event Category' ),
		'parent_item_colon' => __( 'Parent Event Category:' ),
		'edit_item'         => __( 'Edit Event Category' ), 
		'update_item'       => __( 'Update Event Category' ),
		'add_new_item'      => __( 'Add New Event Category' ),
		'new_item_name'     => __( 'New Event Category' ),
		'menu_name'         => __( 'Event Categories' ),
	);
	$args = array(
	'labels' => $labels,
	'hierarchical' => true,
	);
register_taxonomy( 'event_category', 'event', $args );
}
add_action( 'init', 'bb_taxonomies_event', 0 );

/**
 * Font Awesome Kit Setup
 * 
 * This will add your Font Awesome Kit to the front-end, the admin back-end,
 * and the login screen area.
 */
if (! function_exists('fa_custom_setup_kit') ) {
	function fa_custom_setup_kit($kit_url = '') {
	foreach ( [ 'wp_enqueue_scripts', 'admin_enqueue_scripts', 'login_enqueue_scripts' ] as $action ) {
		add_action(
		$action,
		function () use ( $kit_url ) {
			wp_enqueue_script( 'font-awesome-kit', $kit_url, [], null );
		}
		);
	}
	}
}
fa_custom_setup_kit('https://kit.fontawesome.com/3238166796.js');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Block Editor additions.
 */
require get_template_directory() . '/inc/block-editor.php';