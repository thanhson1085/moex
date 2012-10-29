<?php
/**
 * Sun Aug 05, 2012 14:17:48 added by Thanh Son 
 * Email: thanhson1085@gmail.com 
 */
include_once(get_template_directory().'/lib/claviska/simple-php-captcha.php');

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
$role = get_role('cs_router');
$role->add_cap('edit_pages');

add_filter('excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
	return 20; 
}
function new_excerpt_more($more) {
        return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');
/*
add_action('template_redirect', 'add_edit_link_to_title');
function add_edit_link_to_title() {
	add_filter('the_title', 'edit_link_to_title', 10, 2);
}
function edit_link_to_title($title, $id){
	if (current_user_can('level_10')):
		$title = $title.'<a href="'.get_bloginfo('url').'/wp-admin/post.php?post='.$id.'&action=edit" class="title-edit-link" target="_blank">(edit)</a>';
	endif;
    return $title;
}
*/
function custom_login_logo() {
    echo '<style type="text/css">
    h1 a { background-image: url('.get_bloginfo('template_directory').'/images/logo3.png) !important; background-size: 200px !important}
    </style>';
}
add_action('login_head', 'custom_login_logo');
add_filter( 'show_admin_bar', '__return_false' );
if (function_exists('wp_nav_menu'))
{
  	function wp_moex_menus()
  	{
    	register_nav_menus(array('primary-menu' => __('Moex Menu'), 'second-menu' => __('Menu Top'), 'third-menu' => __('Menu Left'), 'admin-menu' => __('Admin Menu')));
  	}
  	add_action('init', 'wp_moex_menus');
}
function my_login_redirect($redirect_to, $request){
	return home_url(); 
}
add_filter("login_redirect", "my_login_redirect", 10, 3);

global $order_db_version;
$order_db_version = "1.0";


/* install db */
order_install();
function order_install() {
	global $wpdb;
	global $order_db_version;

	$table_name = $wpdb->prefix . "orders";

	$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		user_id bigint(20) NOT NULL,
		order_from varchar(250) NOT NULL,
		order_to varchar(250) NOT NULL,
		order_info text NOT NULL,
		phone varchar(250) NOT NULL,
		price varchar(250) NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		UNIQUE KEY id (id)
	);";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	add_option("order_db_version", $order_db_version);
}
/* end install db */
// function to display number of posts.
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}

// function to count views.
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
function posts_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
}
function posts_custom_column_views($column_name, $id){
	if($column_name === 'post_views'){
        echo getPostViews(get_the_ID());
    }
}

// Redefine user notification function
if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

        @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);

        if ( empty($plaintext_pass) )
            return;

        $message  = __('Hi there,') . "\r\n\r\n";
        $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
        $message .= wp_login_url() . "\r\n";
        $message .= sprintf(__('Username: %s'), $user_email) . "\r\n";
        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";
        $message .= sprintf(__('If you have any problems, please contact me at %s.'), get_option('admin_email')) . "\r\n\r\n";
        $message .= __('Adios!');

        wp_mail($user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message);

    }
}
function tml_new_user_registered( $user_id ) {
    wp_set_auth_cookie( $user_id, false, is_ssl() );
    wp_redirect( admin_url( 'profile.php' ) );
    exit;
}
add_action( 'tml_new_user_registered', 'tml_new_user_registered' );
