<?php
// Enqueue Parent and Child Theme Styles
function jobmonster_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}
add_action('wp_enqueue_scripts', 'jobmonster_child_enqueue_styles');

// Register Custom Post Type: Job
if ( ! function_exists( 'jm_register_job_post_type' ) ) :
    function jm_register_job_post_type() {
        // Register the custom post type 'noo_job'
        register_post_type(
            'noo_job',
            array(
                'labels' => array(
                    'name'               => __( 'Jobs', 'noo' ),
                    'singular_name'      => __( 'Job', 'noo' ),
                    'add_new'            => __( 'Add New Job', 'noo' ),
                    'add_new_item'       => __( 'Add Job', 'noo' ),
                    'edit'               => __( 'Edit', 'noo' ),
                    'edit_item'          => __( 'Edit Job', 'noo' ),
                    'new_item'           => __( 'New Job', 'noo' ),
                    'view'               => __( 'View', 'noo' ),
                    'view_item'          => __( 'View Job', 'noo' ),
                    'search_items'       => __( 'Search Job', 'noo' ),
                    'not_found'          => __( 'No Jobs found', 'noo' ),
                    'not_found_in_trash' => __( 'No Jobs found in Trash', 'noo' ),
                    'parent'             => __( 'Parent Job', 'noo' ),
                    'all_items'          => __( 'All Jobs', 'noo' ),
                ),
                'description'         => __( 'This is a place where you can add new job.', 'noo' ),
                'public'              => true,
                'menu_icon'           => 'dashicons-portfolio',
                'show_ui'             => true,
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'publicly_queryable'  => true,
                'exclude_from_search' => false,
                'hierarchical'        => false,
                'rewrite'             => array( 'slug' => 'jobs' ),
                'query_var'           => true,
                'supports'            => array( 'title', 'editor', 'thumbnail', 'comments' ),
                'has_archive'         => true,
                'show_in_nav_menus'   => true,
                'delete_with_user'    => true,
                'can_export'          => true
            )
        );

        // Register the taxonomy 'job_category'
        register_taxonomy(
            'job_category',
            array('noo_job'),
            array(
                'labels'       => array(
                    'name'          => __( 'Job Category', 'noo' ),
                    'add_new_item'  => __( 'Add New Job Category', 'noo' ),
                    'new_item_name' => __( 'New Job Category', 'noo' )
                ),
                'hierarchical' => true,
                'query_var'    => true,
                'rewrite'      => array( 'slug' => 'job-category' )
            )
        );

        // Register the taxonomy 'job_type'
        register_taxonomy(
            'job_type',
            'noo_job',
            array(
                'labels'       => array(
                    'name'          => __( 'Job Type', 'noo' ),
                    'add_new_item'  => __( 'Add New Job Type', 'noo' ),
                    'new_item_name' => __( 'New Job Type', 'noo' )
                ),
                'hierarchical' => true,
                'query_var'    => true,
                'rewrite'      => array( 'slug' => 'job-type' )
            )
        );

        // Register the taxonomy 'job_tag'
        register_taxonomy(
            'job_tag',
            'noo_job',
            array(
                'labels'       => array(
                    'name'          => __( 'Job Tag', 'noo' ),
                    'add_new_item'  => __( 'Add New Job Tag', 'noo' ),
                    'new_item_name' => __( 'New Job Tag', 'noo' )
                ),
                'hierarchical' => false,
                'query_var'    => true,
                'rewrite'      => array( 'slug' => 'job-tag' )
            )
        );

        // Register the taxonomy 'job_location'
        register_taxonomy(
            'job_location',
            'noo_job',
            array(
                'labels'       => array(
                    'name'          => __( 'Job Location', 'noo' ),
                    'add_new_item'  => __( 'Add New Job Location', 'noo' ),
                    'new_item_name' => __( 'New Job Location', 'noo' )
                ),
                'hierarchical' => true,
                'query_var'    => true,
                'rewrite'      => array( 'slug' => 'job-location' )
            )
        );
    }
    add_action( 'init', 'jm_register_job_post_type' );
endif;



// Register Custom Post Type: Courses
function create_course_post_type() {
    $labels = array(
        'name'               => _x('Courses', 'post type general name'),
        'singular_name'      => _x('Course', 'post type singular name'),
        'menu_name'          => _x('Courses', 'admin menu'),
        'name_admin_bar'     => _x('Course', 'add new on admin bar'),
        'add_new'            => _x('Add New', 'course'),
        'add_new_item'       => __('Add New Course'),
        'new_item'           => __('New Course'),
        'edit_item'          => __('Edit Course'),
        'view_item'          => __('View Course'),
        'all_items'          => __('All Courses'),
        'search_items'       => __('Search Courses'),
        'parent_item_colon'  => __('Parent Courses:'),
        'not_found'          => __('No courses found.'),
        'not_found_in_trash' => __('No courses found in Trash.')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'courses'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5, // Adjust menu position if needed
        'supports'           => array('title', 'editor', 'thumbnail')
    );

    register_post_type('course', $args);
}
add_action('init', 'create_course_post_type');

// Flush rewrite rules manually after theme activation
function my_rewrite_flush() {
    create_course_post_type(); // Ensure this is called after the custom post type is registered.
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'my_rewrite_flush');

// Fetch courses with job category taxonomy
function get_related_courses($job_id) {
    $categories = wp_get_post_terms($job_id, 'job_category', array('fields' => 'ids'));

    $args = array(
        'post_type' => 'course',
        'tax_query' => array(
            array(
                'taxonomy' => 'job_category',
                'field'    => 'term_id',
                'terms'    => $categories,
            ),
        ),
        'posts_per_page' => -1,
    );

    return new WP_Query($args);
}

function jm_add_custom_columns( $columns ) {
    // Insert the Job ID column at the beginning
    $new_columns = array(
        'job_id' => __( 'Job ID', 'noo' )
    );

    return array_merge($new_columns, $columns);
}
add_filter( 'manage_noo_job_posts_columns', 'jm_add_custom_columns' );

// Display the Job ID in the custom column
function jm_custom_column_content( $column, $post_id ) {
    if ( 'job_id' === $column ) {
        echo $post_id; // Display the Job ID
    }
}
add_action( 'manage_noo_job_posts_custom_column', 'jm_custom_column_content', 10, 2 );

// Make the Job ID column sortable (Optional)
function jm_sortable_custom_columns( $columns ) {
    $columns['job_id'] = 'ID';
    return $columns;
}
add_filter( 'manage_edit-noo_job_sortable_columns', 'jm_sortable_custom_columns' );
