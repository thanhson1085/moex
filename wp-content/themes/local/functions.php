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

