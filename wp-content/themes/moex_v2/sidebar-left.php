<p class="main-header">Main Menu</p>
<?php wp_nav_menu(array('theme_location' => 'third-menu'));?>
<?php
if(current_user_can('edit_post')):
?>
	<p class="main-header">Admin Menu</p>
	<?php wp_nav_menu(array('theme_location' => 'admin-menu'));?>
<?php
endif;
?>
