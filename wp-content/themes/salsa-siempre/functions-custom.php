<?php set_post_thumbnail_size(370, 240, true);

add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10);
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10);
function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}

if (function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_news',
		'title' => 'News',
		'fields' => array (
			array (
				'key' => 'field_540237ad0917b',
				'label' => 'Data rozpoczęcia',
				'name' => 'data_od',
				'type' => 'date_picker',
				'date_format' => 'dd.mm',
				'display_format' => 'dd.mm.yyyy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_5402384d0917e',
				'label' => 'Data zakończenia',
				'name' => 'data_do',
				'type' => 'date_picker',
				'date_format' => 'dd.mm',
				'display_format' => 'dd.mm.yyyy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_5402384b0917d',
				'label' => 'Miejsce',
				'name' => 'miejsce',
				'type' => 'text',
				'default_value' => 'Studio Tańca Salsa Siempre',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'comments',
				1 => 'format',
				2 => 'categories',
				3 => 'tags',
			),
		),
		'menu_order' => 0,
	));
}

function remove_menus() {
	remove_menu_page('edit-comments.php');
	remove_menu_page('themes.php');
	remove_menu_page('plugins.php');
	remove_menu_page('tools.php');
	remove_menu_page('edit.php?post_type=acf');
	remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
    remove_submenu_page('edit.php', 'post-new.php');
    remove_submenu_page('edit.php', 'edit.php');
    remove_submenu_page('upload.php', 'upload.php');
    remove_submenu_page('upload.php', 'media-new.php');
    remove_submenu_page('edit.php?post_type=page', 'edit.php?post_type=page');
    remove_submenu_page('edit.php?post_type=page', 'post-new.php?post_type=page');
    remove_submenu_page('users.php', 'user-new.php');
}

// add_action( 'admin_menu', 'remove_menus', 999 );

?>
