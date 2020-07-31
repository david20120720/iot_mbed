<?php
require get_template_directory() . '/inc/menu_class.php';

function davidserver_load_style() {
	
	wp_enqueue_style('style-davidserver', get_stylesheet_uri());
	wp_enqueue_script( 'jquery213', get_template_directory_uri() . '/js/jquery-2.1.3.js');	
	wp_enqueue_script( 'davidserver_menu', get_template_directory_uri() . '/js/menu.js');	
	
}
add_action('wp_enqueue_scripts', 'davidserver_load_style');

function davidserver_support_thumbnail() {
	add_theme_support('post-thumbnails');
	add_image_size('small-thumbnail',160,100);
	add_image_size('big-thumbnail',500,300);
}
add_action('after_setup_theme','davidserver_support_thumbnail');

function davidserver_excerpt_length($length) {

	return 20;
}
add_filter('excerpt_length','davidserver_excerpt_length');

function davidserver_add_menus() {
	add_theme_support('menus');

	register_nav_menus( array(
	'first_nav' => __('First-menu','davidserver'),
	'second_nav' => __('Second-menu','davidserver'),
	'third_nav' => __('Third-menu','davidserver'),
	'fourth_nav' => __('Forth-menu','davidserver'),
	'fifth_nav' => __('Fifth-menu','davidserver'),
	) );
}
add_action( 'after_setup_theme', 'davidserver_add_menus' );

/*
* Register  davidserver widget areas.
*/
function davidserver_widgets_init() {

		register_sidebar( array(
			'name' => __('Bottom Sidebar A', 'davidserver'),
			'id' => 'bottom-sidebar-a',
			'description' => __('Bottom Sidebar A', 'davidserver')	
	));

		register_sidebar( array(
			'name' => __('Bottom Sidebar B', 'davidserver'),
			'id' => 'bottom-sidebar-b',
			'description' => __('Bottom Sidebar B', 'davidserver')	
	));
		register_sidebar( array(
			'name' => __('Bottom Sidebar C', 'davidserver'),
			'id' => 'bottom-sidebar-c',
			'description' => __('Bottom Sidebar C', 'davidserver')	
	));
	
}
add_action('widgets_init','davidserver_widgets_init');


function add_loginout_menu( $items, $args ){

	if( is_admin() ||  $args->theme_location != 'second_nav' )	return $items; 

	$redirect = ( is_home() ) ? false : get_permalink();
	if( is_user_logged_in( ) )
		$link = '<a href="' . wp_logout_url( $redirect ) . '" title="' .  __( 'Logout' ) .'">' . __( 'Logout', 'davidserver' ) . '</a>';
	else  $link = '<a href="' . wp_login_url( $redirect  ) . '" title="' .  __( 'Login' ) .'">' . __( 'Login', 'davidserver' ) . '</a>';

	return $items.= '<li id="log-in-out-link" class="menu-item menu-type-link">'. $link . '</li>';
}
add_filter( 'wp_nav_menu_items', 'add_loginout_menu', 50, 2 );

function datepicker_method() {
wp_enqueue_script( 'datepicker', get_stylesheet_directory_uri() . '/js/datepicker.js', array( 'jquery' ), '버전', 'true or false' );
}
add_action( 'wp_enqueue_scripts', 'datepicker_method' );



