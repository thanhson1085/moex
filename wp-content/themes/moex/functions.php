<?php
/**
 * Sun Aug 05, 2012 14:17:48 added by Thanh Son 
 * Email: thanhson1085@gmail.com 
 */

add_filter('excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
	return 20; 
}
function new_excerpt_more($more) {
        return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');
add_action('template_redirect', 'add_edit_link_to_title');
function add_edit_link_to_title() {
	add_filter('the_title', 'edit_link_to_title', 10, 2);
}
function edit_link_to_title($title, $id){
	if (current_user_can('edit_post')):
		$title = $title.'<a href="'.get_bloginfo('url').'/wp-admin/post.php?post='.$id.'&action=edit" class="title-edit-link" target="_blank">(edit)</a>';
	endif;
    return $title;
}
function custom_login_logo() {
    echo '<style type="text/css">
    h1 a { background-image: url('.get_bloginfo('template_directory').'/images/logo2.png) !important; background-size: 200px !important}
    </style>';
}
add_action('login_head', 'custom_login_logo');
add_filter( 'show_admin_bar', '__return_false' );
if (function_exists('wp_nav_menu'))
{
  	function wp_moex_menus()
  	{
    	register_nav_menus(array('primary-menu' => __('Moex Menu'), 'second-menu' => __('Menu Top')));
  	}
  	add_action('init', 'wp_moex_menus');
}
