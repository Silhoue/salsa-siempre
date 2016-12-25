<?php
add_image_size("classes-item-image", 370, 185, true);
add_image_size("teachers-item-image", 370, 490, true);
add_image_size("slide-image", 770, 380, true);

if (false === get_option("medium_crop")) {
    add_option("medium_crop", "1");
} else {
    update_option("medium_crop", "1");
}

if (false === get_option("large_crop")) {
    add_option("large_crop", "1");
} else {
    update_option("large_crop", "1");
}

add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10);
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10);
function remove_thumbnail_dimensions( $html ) {
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
	return $html;
}

add_filter('wp_insert_post_data', 'set_default_class_post_title', 10, 2 );
function set_default_class_post_title( $data, $postarr ) {
  if ( $data['post_type'] == 'class' ) {
	  $data['post_title'] = "Kurs";
  }
  return $data;
}

add_filter('manage_class_posts_columns', 'add_class_posts_custom_columns');
function add_class_posts_custom_columns( $columns ) {
	$custom_columns = array(
		'type' => 'Rodzaj',
		'level' => 'Poziom',
		'day_of_week' => 'Dzień tygodnia',
		'start_hour' => 'Godzina',
		'teachers' => 'Instruktorzy'
	);
	$columns = array_slice( $columns, 0, 2, true ) + $custom_columns + array_slice( $columns, 2, NULL, true );
	return $columns;
}

add_action( 'manage_class_posts_custom_column', 'define_class_posts_custom_columns', 10, 2 );
function define_class_posts_custom_columns( $column_name, $post_id ) {
	switch ($column_name) {
	case 'day_of_week':
		$days_of_week = array('poniedziałek','wtorek','środa','czwartek','piątek','sobota','niedziela');
		echo $days_of_week[get_field('day_of_week', $post_id)];
		break;
	case 'start_hour':
		echo get_field('start_hour', $post_id);
		break;
	case 'type':
		$type = get_field('type', $post_id);
		echo $type->post_title;
		break;
	case 'level':
		$level = get_field('level', $post_id);
		echo $level->post_title;
		break;
	case 'teachers':
		$teacher1 = get_field('teacher_1', $post_id);
		$teachers = $teacher1->post_title;
		$teacher2 = get_field('teacher_2', $post_id);
		if ($teacher2) {
			$teachers .= " & ".$teacher2->post_title;
		}
		echo $teachers;
		break;
	}
}

add_filter( 'manage_edit-class_sortable_columns', 'add_class_posts_sortable_columns' );
function add_class_posts_sortable_columns( $columns ) {
	$columns['day_of_week'] = 'day_of_week';
	$columns['start_hour'] = 'start_hour';
	$columns['type'] = 'related.type';
	$columns['level'] = 'related.level';
	$columns['teachers'] = 'related.teachers';
	return $columns;
}

add_filter( 'request', 'define_class_posts_sortable_columns' );
function define_class_posts_sortable_columns( $vars ) {
	if ( array_key_exists('orderby', $vars) && $vars['post_type'] == 'class' ) {
		if ('day_of_week' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'day_of_week',
				'orderby' => 'meta_value'
			));
		} elseif ('start_hour' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'start_hour',
				'orderby' => 'meta_value'
			) );
		} elseif ('type' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'orderby' => 'wp_posts.type'
			) );
		}
	}
	return $vars;
}

add_filter( 'posts_orderby', 'orderby_related_post_title' );
function orderby_related_post_title( $orderby_statement ) {
	if (array_key_exists('orderby', $_GET) && strpos($_GET['orderby'], 'related.') === 0) {
		$meta_key = substr($_GET['orderby'], 8);
		if ( ctype_alnum($meta_key) ) {
			$query = '(
				SELECT post_title
				FROM wp_posts AS related
				JOIN wp_postmeta AS meta
				ON related.id = meta.meta_value
				WHERE meta.meta_key = "%s"
				AND wp_posts.id = meta.post_id
			)';
			if ($meta_key === 'teachers') {
				$orderby_statement = 'CONCAT_WS(" & ", '
					.sprintf($query, "teacher_1")
					.', '
					.sprintf($query, "teacher_2")
					.')';
			} else {
				$orderby_statement = sprintf($query, $meta_key);
			}

			$orderby_statement .= ($_GET['order'] === 'desc') ? ' desc' : ' asc';
		}
	}

	return $orderby_statement;
}

add_action( 'init', 'register_custom_post_types', 0 );
function register_custom_post_types() {
	$labels = array(
		'name'                => _x( 'Kursy', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Kurs', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Kursy', 'text_domain' ),
		'all_items'           => __( 'Wszystkie kursy', 'text_domain' ),
		'view_item'           => __( 'Zobacz', 'text_domain' ),
		'add_new_item'        => __( 'Dodaj nowy kurs', 'text_domain' ),
		'add_new'             => __( 'Dodaj nowy', 'text_domain' ),
		'edit_item'           => __( 'Edytuj kurs', 'text_domain' ),
		'search_items'        => __( 'Szukaj kursu', 'text_domain' ),
		'not_found'           => __( 'Nie znaleziono żadnych kursów.', 'text_domain' ),
		'not_found_in_trash'  => __( 'Nie znaleziono żadnych kursów w koszu.', 'text_domain' )
	);
	$args = array(
		'labels'              => $labels,
		'rewrite'			  => array( 'slug' => 'kursy' ),
		'supports'            => array( 'editor', 'thumbnail', 'revisions' ),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-calendar'
	);
	register_post_type( 'class', $args );

	$labels = array(
		'name'                => _x( 'Rodzaje kursów', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Rodzaj kursu', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Rodzaje kursów', 'text_domain' ),
		'all_items'           => __( 'Wszystkie rodzaje', 'text_domain' ),
		'view_item'           => __( 'Zobacz', 'text_domain' ),
		'add_new_item'        => __( 'Dodaj nowy rodzaj', 'text_domain' ),
		'add_new'             => __( 'Dodaj nowy', 'text_domain' ),
		'edit_item'           => __( 'Edytuj rodzaj', 'text_domain' ),
		'search_items'        => __( 'Szukaj rodzaju', 'text_domain' ),
		'not_found'           => __( 'Nie znaleziono żadnych rodzajów.', 'text_domain' ),
		'not_found_in_trash'  => __( 'Nie znaleziono żadnych rodzajów w koszu.', 'text_domain' )
	);
	$args = array(
		'labels'              => $labels,
		'rewrite'			  => array( 'slug' => 'rodzaje-kursów' ),
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 7,
		'menu_icon'           => 'dashicons-screenoptions'
	);
	register_post_type( 'type', $args );

	$labels = array(
		'name'                => _x( 'Poziomy kursów', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Poziom kursu', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Poziomy kursów', 'text_domain' ),
		'all_items'           => __( 'Wszystkie poziomy', 'text_domain' ),
		'view_item'           => __( 'Zobacz', 'text_domain' ),
		'add_new_item'        => __( 'Dodaj nowy poziom', 'text_domain' ),
		'add_new'             => __( 'Dodaj nowy', 'text_domain' ),
		'edit_item'           => __( 'Edytuj poziom', 'text_domain' ),
		'search_items'        => __( 'Szukaj poziomu', 'text_domain' ),
		'not_found'           => __( 'Nie znaleziono żadnych poziomów.', 'text_domain' ),
		'not_found_in_trash'  => __( 'Nie znaleziono żadnych poziomów w koszu.', 'text_domain' )
	);
	$args = array(
		'labels'              => $labels,
		'rewrite'			  => array( 'slug' => 'poziomy-kursów' ),
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 8,
		'menu_icon'           => 'dashicons-chart-bar'
	);
	register_post_type( 'level', $args );

	$labels = array(
		'name'                => _x( 'Karnety', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Karnet', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Karnety', 'text_domain' ),
		'all_items'           => __( 'Wszystkie karnety', 'text_domain' ),
		'view_item'           => __( 'Zobacz', 'text_domain' ),
		'add_new_item'        => __( 'Dodaj nowy karnet', 'text_domain' ),
		'add_new'             => __( 'Dodaj nowy', 'text_domain' ),
		'edit_item'           => __( 'Edytuj karnet', 'text_domain' ),
		'search_items'        => __( 'Szukaj karnetu', 'text_domain' ),
		'not_found'           => __( 'Nie znaleziono żadnych karnetów.', 'text_domain' ),
		'not_found_in_trash'  => __( 'Nie znaleziono żadnych karnetów w koszu.', 'text_domain' )
	);
	$args = array(
		'labels'              => $labels,
		'rewrite'			  => array( 'slug' => 'karnety' ),
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions'),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 9,
		'menu_icon'           => 'dashicons-images-alt'
	);
	register_post_type( 'package', $args );

	$labels = array(
		'name'                => _x( 'Oferty specjalne', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Oferta specjalna', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Oferty specjalne', 'text_domain' ),
		'all_items'           => __( 'Wszystkie oferty', 'text_domain' ),
		'view_item'           => __( 'Zobacz', 'text_domain' ),
		'add_new_item'        => __( 'Dodaj nową ofertę', 'text_domain' ),
		'add_new'             => __( 'Dodaj nową', 'text_domain' ),
		'edit_item'           => __( 'Edytuj ofertę', 'text_domain' ),
		'search_items'        => __( 'Szukaj oferty', 'text_domain' ),
		'not_found'           => __( 'Nie znaleziono żadnych ofert.', 'text_domain' ),
		'not_found_in_trash'  => __( 'Nie znaleziono żadnych ofert w koszu.', 'text_domain' )
	);
	$args = array(
		'labels'              => $labels,
		'rewrite'			  => array( 'slug' => 'oferty-specjalne' ),
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions'),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 11,
		'menu_icon'           => 'dashicons-star-filled'
	);
	register_post_type( 'special-offer', $args );

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
		'labels'              => $labels,
		'rewrite'			  => array( 'slug' => 'instruktorzy' ),
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 12,
		'menu_icon'           => 'dashicons-universal-access'
	);
	register_post_type( 'teacher', $args );

	$labels = array(
		'name'                => _x( 'Partnerzy', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Partner', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Partnerzy', 'text_domain' ),
		'all_items'           => __( 'Wszyscy partnerzy', 'text_domain' ),
		'view_item'           => __( 'Zobacz', 'text_domain' ),
		'add_new_item'        => __( 'Dodaj nowego partnera', 'text_domain' ),
		'add_new'             => __( 'Dodaj nowego', 'text_domain' ),
		'edit_item'           => __( 'Edytuj partnera', 'text_domain' ),
		'search_items'        => __( 'Szukaj partnera', 'text_domain' ),
		'not_found'           => __( 'Nie znaleziono żadnych partnerów.', 'text_domain' ),
		'not_found_in_trash'  => __( 'Nie znaleziono żadnych partnerów w koszu.', 'text_domain' )
	);
	$args = array(
		'labels'              => $labels,
		'rewrite'			  => array( 'slug' => 'partnerzy' ),
		'supports'            => array( 'title', 'thumbnail', 'revisions'),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 13,
		'menu_icon'           => 'dashicons-groups'
	);
	register_post_type( 'partner', $args );

	$labels = array(
		'name'                => _x( 'Albumy', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Album', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Albumy', 'text_domain' ),
		'all_items'           => __( 'Wszystkie albumy', 'text_domain' ),
		'view_item'           => __( 'Zobacz', 'text_domain' ),
		'add_new_item'        => __( 'Dodaj nowy album', 'text_domain' ),
		'add_new'             => __( 'Dodaj nowy', 'text_domain' ),
		'edit_item'           => __( 'Edytuj album', 'text_domain' ),
		'search_items'        => __( 'Szukaj albumu', 'text_domain' ),
		'not_found'           => __( 'Nie znaleziono żadnych albumów.', 'text_domain' ),
		'not_found_in_trash'  => __( 'Nie znaleziono żadnych albumów w koszu.', 'text_domain' )
	);
	$args = array(
		'labels'              => $labels,
		'rewrite'			  => array( 'slug' => 'albumy' ),
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 14,
		'menu_icon'           => 'dashicons-format-gallery'
	);
	register_post_type( 'album', $args );

	$labels = array(
		'name'                => _x( 'Elementy stopki', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Element', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Stopka', 'text_domain' ),
		'all_items'           => __( 'Wszystkie elementy', 'text_domain' ),
		'view_item'           => __( 'Zobacz', 'text_domain' ),
		'add_new_item'        => __( 'Dodaj nowy element', 'text_domain' ),
		'add_new'             => __( 'Dodaj nowy', 'text_domain' ),
		'edit_item'           => __( 'Edytuj element', 'text_domain' ),
		'search_items'        => __( 'Szukaj elementu', 'text_domain' ),
		'not_found'           => __( 'Nie znaleziono żadnych elementów.', 'text_domain' ),
		'not_found_in_trash'  => __( 'Nie znaleziono żadnych elementów w koszu.', 'text_domain' )
	);
	$args = array(
		'labels'              => $labels,
		'rewrite'			  => array( 'slug' => 'stopka' ),
		'supports'            => array( 'title', 'editor', 'revisions'),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 16,
		'menu_icon'           => 'dashicons-media-text'
	);
	register_post_type( 'footer-item', $args );
}

if( function_exists('register_field_group') ):

	register_field_group(array (
		'key' => 'group_5591b34e9220e',
		'title' => 'Album',
		'fields' => array (
			array (
				'key' => 'field_55a19596af70e',
				'label' => 'Data rozpoczęcia',
				'name' => 'start_date',
				'prefix' => '',
				'type' => 'date_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'display_format' => 'd.m.Y',
				'return_format' => 'd.m.Y',
				'first_day' => 1,
			),
			array (
				'key' => 'field_55a1959caf70f',
				'label' => 'Data zakończenia',
				'name' => 'end_date',
				'prefix' => '',
				'type' => 'date_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'display_format' => 'd.m.Y',
				'return_format' => 'd.m.Y',
				'first_day' => 1,
			),
			array (
				'key' => 'field_55a195a8af710',
				'label' => 'Miejsce',
				'name' => 'place',
				'prefix' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'Studio Tańca Salsa Siempre',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_5591b45556dee',
				'label' => 'Zdjęcia',
				'name' => 'photos',
				'prefix' => '',
				'type' => 'gallery',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'min' => '',
				'max' => '',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'album',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
	));

	register_field_group(array (
		'key' => 'group_54c04aae008be',
		'title' => 'Class',
		'fields' => array (
			array (
				'key' => 'field_5416269207a8c',
				'label' => 'Nowy',
				'name' => 'is_new',
				'prefix' => '',
				'type' => 'true_false',
				'instructions' => 'nowe kursy będą widoczne w kolumnie "Nowe kursy" na stronie głównej, zostaną także odpowiednio oznaczone w grafiku i na stronie kursu',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => 'Nowy kurs',
				'default_value' => 0,
			),
			array (
				'key' => 'field_54160205abd6b',
				'label' => 'Rodzaj kursu',
				'name' => 'type',
				'prefix' => '',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array (
					0 => 'type',
				),
				'taxonomy' => array (
				),
				'allow_null' => 0,
				'multiple' => 0,
				'return_format' => 'object',
				'ui' => 1,
			),
			array (
				'key' => 'field_54160385bb82c',
				'label' => 'Poziom kursu',
				'name' => 'level',
				'prefix' => '',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array (
					0 => 'level',
				),
				'taxonomy' => array (
				),
				'allow_null' => 0,
				'multiple' => 0,
				'return_format' => 'object',
				'ui' => 1,
			),
			array (
				'key' => 'field_541603a6bb82d',
				'label' => 'Instruktor 1',
				'name' => 'teacher_1',
				'prefix' => '',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array (
					0 => 'teacher',
				),
				'taxonomy' => array (
				),
				'allow_null' => 0,
				'multiple' => 0,
				'return_format' => 'object',
				'ui' => 1,
			),
			array (
				'key' => 'field_5416040a5a6be',
				'label' => 'Instruktor 2',
				'name' => 'teacher_2',
				'prefix' => '',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array (
					0 => 'teacher',
				),
				'taxonomy' => array (
				),
				'allow_null' => 1,
				'multiple' => 0,
				'return_format' => 'object',
				'ui' => 1,
			),
			array (
				'key' => 'field_541605b29f616',
				'label' => 'Dzień tygodnia',
				'name' => 'day_of_week',
				'prefix' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array (
					0 => 'poniedziałek',
					1 => 'wtorek',
					2 => 'środa',
					3 => 'czwartek',
					4 => 'piątek',
					5 => 'sobota',
					6 => 'niedziela',
				),
				'default_value' => array (
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
				'disabled' => 0,
				'readonly' => 0,
			),
			array (
				'key' => 'field_5416041a5a6bf',
				'label' => 'Godzina rozpoczęcia',
				'name' => 'start_hour',
				'prefix' => '',
				'type' => 'date_time_picker',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'show_date' => 'false',
				'date_format' => '',
				'time_format' => 'H:mm',
				'show_week_number' => 'false',
				'picker' => 'slider',
				'save_as_timestamp' => 'true',
				'get_as_timestamp' => 'false',
			),
			array (
				'key' => 'field_541604c722744',
				'label' => 'Godzina zakończenia',
				'name' => 'end_hour',
				'prefix' => '',
				'type' => 'date_time_picker',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'show_date' => 'false',
				'date_format' => 'm/d/y',
				'time_format' => 'H:mm',
				'show_week_number' => 'false',
				'picker' => 'slider',
				'save_as_timestamp' => 'true',
				'get_as_timestamp' => 'false',
			),
			array (
				'key' => 'field_541604e122745',
				'label' => 'Data rozpoczęcia',
				'name' => 'start_date',
				'prefix' => '',
				'type' => 'date_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'first_day' => 1,
				'return_format' => 'd.m.Y',
				'display_format' => 'd.m.Y',
			),
			array (
				'key' => 'field_543031fb6cf81',
				'label' => 'Sala',
				'name' => 'studio',
				'prefix' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array (
					0 => 'Sala 1',
					1 => 'Sala 2',
					2 => 'Sala 3',
				),
				'default_value' => array (
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
				'disabled' => 0,
				'readonly' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'class',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
		),
	));

	register_field_group(array (
		'key' => 'group_54c04aae1e955',
		'title' => 'Hide page editor',
		'fields' => array (
		),
		'location' => array (
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'teachers.php',
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'types.php',
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'levels.php',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
			0 => 'the_content',
		),
	));

	register_field_group(array (
		'key' => 'group_54c04aae212f7',
		'title' => 'Hide page icon',
		'fields' => array (
		),
		'location' => array (
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'contact.php',
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'teachers.php',
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'timetable.php',
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'types.php',
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'levels.php',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
			0 => 'featured_image',
		),
	));

	register_field_group(array (
		'key' => 'group_54c04aae24f78',
		'title' => 'Hide permalink',
		'fields' => array (
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'type',
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'level',
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'package',
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'partner',
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'footer-item',
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'special-offer',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
			0 => 'permalink',
		),
	));

	register_field_group(array (
		'key' => 'group_54c04aae28374',
		'title' => 'Level',
		'fields' => array (
			array (
				'key' => 'field_5415fb1ec3b71',
				'label' => 'Kolor',
				'name' => 'color',
				'prefix' => '',
				'type' => 'color_picker',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'level',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
			0 => 'permalink',
			1 => 'slug',
		),
	));

	register_field_group(array (
		'key' => 'group_54c04aae2d75e',
		'title' => 'News',
		'fields' => array (
			array (
				'key' => 'field_540237ad0917b',
				'label' => 'Data rozpoczęcia',
				'name' => 'start_date',
				'prefix' => '',
				'type' => 'date_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'first_day' => 1,
				'return_format' => 'd.m',
				'display_format' => 'd.m.Y',
			),
			array (
				'key' => 'field_5402384d0917e',
				'label' => 'Data zakończenia',
				'name' => 'end_date',
				'prefix' => '',
				'type' => 'date_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'first_day' => 1,
				'return_format' => 'd.m',
				'display_format' => 'd.m.Y',
			),
			array (
				'key' => 'field_5402795f28caf',
				'label' => 'Godzina rozpoczęcia',
				'name' => 'start_time',
				'prefix' => '',
				'type' => 'date_time_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
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
				'name' => 'end_time',
				'prefix' => '',
				'type' => 'date_time_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
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
				'name' => 'place',
				'prefix' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'Studio Tańca Salsa Siempre',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
			0 => 'comments',
			1 => 'format',
			2 => 'categories',
			3 => 'tags',
		),
	));

	register_field_group(array (
		'key' => 'group_54c04aae3dba0',
		'title' => 'Package',
		'fields' => array (
			array (
				'key' => 'field_543acde78938f',
				'label' => 'Cena',
				'name' => 'price',
				'prefix' => '',
				'type' => 'number',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 0,
				'max' => '',
				'step' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_543ad40e521ca',
				'label' => 'Rodzaj',
				'name' => 'type',
				'prefix' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array (
					0 => 'Karnet podstawowy',
					1 => 'Oferta specjalna',
				),
				'default_value' => array (
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
				'disabled' => 0,
				'readonly' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'package',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
		),
	));

	register_field_group(array (
		'key' => 'group_54c04aae45e63',
		'title' => 'Partner',
		'fields' => array (
			array (
				'key' => 'field_543c24a55e257',
				'label' => 'Link do strony partnera',
				'name' => 'link',
				'prefix' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'partner',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
		),
	));

	register_field_group(array (
		'key' => 'group_54c04aae4ba18',
		'title' => 'Splash',
		'fields' => array (
			array (
				'key' => 'field_54550be95f17c',
				'label' => 'Tekst przycisku',
				'name' => 'dismiss',
				'prefix' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page',
					'operator' => '==',
					'value' => '340',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
		),
	));

	register_field_group(array (
		'key' => 'group_54c04aae50e79',
		'title' => 'Teacher',
		'fields' => array (
			array (
				'key' => 'field_56ec3d85465a5',
				'label' => 'Imię',
				'name' => 'name',
				'prefix' => '',
				'type' => 'text',
				'instructions' => 'Zostanie użyte w grafiku',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_56ec41b8fd8ff',
				'label' => 'Umiejętność 1',
				'name' => '',
				'prefix' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
			),
			array (
				'key' => 'field_56ec4217fd900',
				'label' => 'Nazwa',
				'name' => 'skill_1',
				'prefix' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_56ec4261fd901',
				'label' => 'Liczba gwiazdek',
				'name' => 'rating_1',
				'prefix' => '',
				'type' => 'number',
				'instructions' => 'od 1 do 6',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 1,
				'max' => 6,
				'step' => 1,
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_56ec4314fd905',
				'label' => 'Umiejętność 2',
				'name' => '',
				'prefix' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
			),
			array (
				'key' => 'field_56ec42b1fd902',
				'label' => 'Nazwa',
				'name' => 'skill_2',
				'prefix' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_56ec437afd908',
				'label' => 'Liczba gwiazdek',
				'name' => 'rating_2',
				'prefix' => '',
				'type' => 'number',
				'instructions' => 'od 1 do 6',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 1,
				'max' => 6,
				'step' => 1,
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_56ec431cfd906',
				'label' => 'Umiejętność 3',
				'name' => '',
				'prefix' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
			),
			array (
				'key' => 'field_56ec42fafd903',
				'label' => 'Nazwa',
				'name' => 'skill_3',
				'prefix' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_56ec438bfd909',
				'label' => 'Liczba gwiazdek',
				'name' => 'rating_3',
				'prefix' => '',
				'type' => 'number',
				'instructions' => 'od 1 do 6',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 1,
				'max' => 6,
				'step' => 1,
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_56ec4366fd907',
				'label' => 'Umiejętność 4',
				'name' => '',
				'prefix' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
			),
			array (
				'key' => 'field_56ec4307fd904',
				'label' => 'Nazwa',
				'name' => 'skill_4',
				'prefix' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_56ec439dfd90a',
				'label' => 'Liczba gwiazdek',
				'name' => 'rating_4',
				'prefix' => '',
				'type' => 'number',
				'instructions' => 'od 1 do 6',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 1,
				'max' => 6,
				'step' => 1,
				'readonly' => 0,
				'disabled' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'teacher',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
	));

endif;

add_action('admin_menu', 'remove_menus', 999);
function remove_menus () {
	global $menu;
	$menu[65] = $menu[10]; // Media
	unset($menu[10]);
	remove_menu_page('edit-comments.php');
	remove_menu_page('themes.php');
	remove_menu_page('plugins.php');
	remove_menu_page('edit.php?post_type=acf-field-group');
	remove_menu_page('bws_plugins');
	remove_menu_page('tools.php');
	add_menu_page('Menu', 'Menu', 'manage_options', 'nav-menus.php', '', 'dashicons-menu', 15);
	add_menu_page('Eksport', 'Eksport', 'manage_options', 'export.php', '', 'dashicons-download', 90);
	remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
	remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
	remove_submenu_page('edit.php?post_type=class', 'order-post-types-class');
	remove_submenu_page('options-general.php', 'options-writing.php');
	remove_submenu_page('options-general.php', 'options-discussion.php');
	remove_submenu_page('options-general.php', 'options-media.php');
	remove_submenu_page('options-general.php', 'options-permalink.php');
	remove_submenu_page('options-general.php', 'cpto-options');
}

add_action('wp_before_admin_bar_render', 'remove_admin_bar_links');
function remove_admin_bar_links () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('dashboard');
    $wp_admin_bar->remove_menu('appearance');
    $wp_admin_bar->remove_menu('customize');
    $wp_admin_bar->remove_menu('comments');
}

?>
