<?php
/**
 * Sun Aug 05, 2012 14:17:48 added by Thanh Son 
 * Email: thanhson1085@gmail.com 
 */

$result = add_role('cs', 'CS', array(
    'read' => true,
    'edit_posts' => true,
    'edit_pages' => true,
    'delete_posts' => false,
));
$result = add_role('router', 'ROUTER', array(
    'read' => true,
    'edit_posts' => true,
    'edit_pages' => true,
    'delete_posts' => false,
));

$result = add_role('cs_router', 'CS + ROUTER', array(
    'read' => true,
    'edit_posts' => true,
    'edit_pages' => true,
    'delete_posts' => false,
));

$result = add_role('accountant', 'ACCOUNTANT', array(
    'read' => true,
    'edit_posts' => true,
    'edit_pages' => true,
    'delete_posts' => false,
));

$role = get_role('cs_router');
$role->add_cap('edit_pages');
$role->add_cap('edit_others_pages');

function custom_login_logo() {
    echo '<style type="text/css">
    h1 a { background-image: url('.get_bloginfo('template_directory').'/images/logo3.png) !important; background-size: 200px !important}
    </style>';
}
add_action('login_head', 'custom_login_logo');
add_filter( 'show_admin_bar', '__return_false' );
function my_login_redirect($redirect_to, $request){
	return home_url(); 
}
add_filter("login_redirect", "my_login_redirect", 10, 3);

