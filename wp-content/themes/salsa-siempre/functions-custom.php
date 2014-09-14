<?php
add_image_size("news-item-image", 370, 240, true);
add_image_size("news-image", 470, 305, true);
add_image_size("teachers-item-image", 370, 490, true);
add_image_size("teacher-image", 470, 490, true);

add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10);
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10);
function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}

function custom_post_types() {
	$labels = array(
		'name'                => _x( 'Instruktorzy', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Intruktor', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Instruktorzy', 'text_domain' ),
//		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'Wszyscy instruktorzy', 'text_domain' ),
		'view_item'           => __( 'Zobacz', 'text_domain' ),
		'add_new_item'        => __( 'Dodaj nowego instruktora', 'text_domain' ),
		'add_new'             => __( 'Dodaj nowego', 'text_domain' ),
		'edit_item'           => __( 'Edytuj instruktora', 'text_domain' ),
//		'update_item'         => __( 'Update Item', 'text_domain' ),
		'search_items'        => __( 'Szukaj instruktora', 'text_domain' ),
		'not_found'           => __( 'Nie znaleziono żadnych instruktorów.', 'text_domain' ),
		'not_found_in_trash'  => __( 'Nie znaleziono żadnych instruktorów w koszu.', 'text_domain' )
	);
	$args = array(
		'label'               => __( 'teacher', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-universal-access'
	);
	register_post_type( 'teacher', $args );
}

if(function_exists("register_field_group"))
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
				'display_format' => 'dd.mm.yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_5402384d0917e',
				'label' => 'Data zakończenia',
				'name' => 'data_do',
				'type' => 'date_picker',
				'date_format' => 'dd.mm',
				'display_format' => 'dd.mm.yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_5402795f28caf',
				'label' => 'Godzina rozpoczęcia',
				'name' => 'godzina_od',
				'type' => 'date_time_picker',
				'show_date' => 'false',
				'date_format' => 'm/d/y',
				'time_format' => 'H:mm',
				'show_week_number' => 'false',
				'picker' => 'slider',
				'save_as_timestamp' => 'true',
				'get_as_timestamp' => 'false',
			),
			array (
				'key' => 'field_54027a69e1e07',
				'label' => 'Godzina zakończenia',
				'name' => 'godzina_do',
				'type' => 'date_time_picker',
				'show_date' => 'false',
				'date_format' => 'm/d/y',
				'time_format' => 'H:mm',
				'show_week_number' => 'false',
				'picker' => 'slider',
				'save_as_timestamp' => 'true',
				'get_as_timestamp' => 'false',
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
	register_field_group(array (
		'id' => 'acf_teacher',
		'title' => 'Teacher',
		'fields' => array (
			array (
				'key' => 'field_5414aff457e9b',
				'label' => 'Imię',
				'name' => 'imię',
				'type' => 'text',
				'instructions' => 'Zostanie użyte w grafiku',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5414b06457e9c',
				'label' => 'Umiejętność 1',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_5414b16f57e9e',
				'label' => 'Nazwa',
				'name' => 'nazwa_umiejętności_1',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5414b5a9476c2',
				'label' => 'Liczba gwiazdek',
				'name' => 'liczba_gwiazdek_1',
				'type' => 'number',
				'instructions' => 'od 1 do 6',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 1,
				'max' => 6,
				'step' => 1,
			),
			array (
				'key' => 'field_5414b18957e9f',
				'label' => 'Umiejętność 2',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_5414b40ece33e',
				'label' => 'Nazwa',
				'name' => 'nazwa_umiejętności_2',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5414b59d476c1',
				'label' => 'Liczba gwiazdek',
				'name' => 'liczba_gwiazdek_2',
				'type' => 'number',
				'instructions' => 'od 1 do 6',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 1,
				'max' => 6,
				'step' => 1,
			),
			array (
				'key' => 'field_5414b458ce33f',
				'label' => 'Umiejętność 3',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_5414b468ce340',
				'label' => 'Nazwa',
				'name' => 'nazwa_umiejętności_3',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5414b58c476c0',
				'label' => 'Liczba gwiazdek',
				'name' => 'liczba_gwiazdek_3',
				'type' => 'number',
				'instructions' => 'od 1 do 6',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 1,
				'max' => 6,
				'step' => 1,
			),
			array (
				'key' => 'field_5414b474ce341',
				'label' => 'Umiejętność 4',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_5414b4a2ce343',
				'label' => 'Nazwa',
				'name' => 'nazwa_umiejętności_4',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5414b542ce347',
				'label' => 'Liczba gwiazdek',
				'name' => 'liczba_gwiazdek_4',
				'type' => 'number',
				'instructions' => 'od 1 do 6',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 1,
				'max' => 6,
				'step' => 1,
			),
			array (
				'key' => 'field_5414b48fce342',
				'label' => 'Umiejętność 5',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_5414b4fcce345',
				'label' => 'Nazwa',
				'name' => 'nazwa_umiejętności_5',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5414b509ce346',
				'label' => 'Liczba gwiazdek',
				'name' => 'liczba_gwiazdek_5',
				'type' => 'number',
				'instructions' => 'od 1 do 6',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 1,
				'max' => 6,
				'step' => 1,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'teacher',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'no_box',
			'hide_on_screen' => array (
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
add_action( 'init', 'custom_post_types', 0 );

?>
