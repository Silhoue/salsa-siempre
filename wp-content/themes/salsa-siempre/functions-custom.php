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
	if ( $vars['post_type'] == 'class' ) {
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
	if (strpos($_GET['orderby'], 'related.') === 0) {
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
		'label'               => __( 'class', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => false,
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 7,
		'menu_icon'           => 'dashicons-calendar'
	);
	register_post_type( 'class', $args );

	$labels = array(
		'name'                => _x( 'Rodzaje kursów', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Rodzaj', 'Post Type Singular Name', 'text_domain' ),
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
		'label'               => __( 'type', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title' ),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 7,
		'menu_icon'           => 'dashicons-screenoptions'
	);
	register_post_type( 'type', $args );

	$labels = array(
		'name'                => _x( 'Poziomy kursów', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Poziom', 'Post Type Singular Name', 'text_domain' ),
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
		'label'               => __( 'level', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title' ),
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 8,
		'menu_icon'           => 'dashicons-chart-bar'
	);
	register_post_type( 'level', $args );
}

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_class',
		'title' => 'Class',
		'fields' => array (
			array (
				'key' => 'field_5416269207a8c',
				'label' => 'Nowy',
				'name' => 'is_new',
				'type' => 'true_false',
				'instructions' => 'nowe kursy będą widoczne w kolumnie "Nowe kursy" na stronie głównej',
				'message' => 'Nowy kurs',
				'default_value' => 0,
			),
			array (
				'key' => 'field_54160205abd6b',
				'label' => 'Rodzaj kursu',
				'name' => 'type',
				'type' => 'post_object',
				'required' => 1,
				'post_type' => array (
					0 => 'type',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_54160385bb82c',
				'label' => 'Poziom kursu',
				'name' => 'level',
				'type' => 'post_object',
				'required' => 1,
				'post_type' => array (
					0 => 'level',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_541603a6bb82d',
				'label' => 'Instruktor 1',
				'name' => 'teacher_1',
				'type' => 'post_object',
				'required' => 1,
				'post_type' => array (
					0 => 'teacher',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_5416040a5a6be',
				'label' => 'Instruktor 2',
				'name' => 'teacher_2',
				'type' => 'post_object',
				'post_type' => array (
					0 => 'teacher',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'allow_null' => 1,
				'multiple' => 0,
			),
			array (
				'key' => 'field_541605b29f616',
				'label' => 'Dzień tygodnia',
				'name' => 'day_of_week',
				'type' => 'select',
				'required' => 1,
				'choices' => array (
					0 => 'poniedziałek',
					1 => 'wtorek',
					2 => 'środa',
					3 => 'czwartek',
					4 => 'piątek',
					5 => 'sobota',
					6 => 'niedziela',
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 0,
			),
			array (
				'key' => 'field_5416041a5a6bf',
				'label' => 'Godzina rozpoczęcia',
				'name' => 'start_hour',
				'type' => 'date_time_picker',
				'required' => 1,
				'show_date' => 'false',
				'date_format' => 'm/d/y',
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
				'type' => 'date_time_picker',
				'required' => 1,
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
				'type' => 'date_picker',
				'date_format' => 'dd.mm',
				'display_format' => 'dd/mm/yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_543031fb6cf81',
				'label' => 'Sala',
				'name' => 'studio',
				'type' => 'select',
				'required' => 1,
				'choices' => array (
					0 => 'Sala 1',
					1 => 'Sala 2',
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'class',
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
	register_field_group(array (
		'id' => 'acf_level',
		'title' => 'Level',
		'fields' => array (
			array (
				'key' => 'field_5415fb1ec3b71',
				'label' => 'Kolor',
				'name' => 'color',
				'type' => 'color_picker',
				'required' => 1,
				'default_value' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'level',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'permalink',
				1 => 'slug',
			),
		),
		'menu_order' => 0,
	));
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
		'id' => 'acf_static-pages-hide-only',
		'title' => 'Static pages (hide only)',
		'fields' => array (
		),
		'location' => array (
			array (
				array (
					'param' => 'page',
					'operator' => '==',
					'value' => '19',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'the_content',
				1 => 'featured_image',
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
	register_field_group(array (
		'id' => 'acf_type-hide-only',
		'title' => 'Type (hide only)',
		'fields' => array (
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'type',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				0 => 'permalink',
				1 => 'slug',
			),
		),
		'menu_order' => 0,
	));
}

// add_action( 'admin_menu', 'remove_menus', 999 );
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

?>
