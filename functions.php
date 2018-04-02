<?php 
/* VERSION CHANGE LOG
Version 1.0.8
Update [2.2] to include post types b2p_program and gf_workout
Removed [2.3] b2p_workout_shortcode since it is no longer needed
Removed b2p_workout_shortcode add_shortcode function from [2.1]
*/



/* TABLE OF CONTENTS */

/*
	
	1. HOOKS
		1.1 - Registers all our custom shortcodes on init
		1.2 - Registers action to remove admin bar
		1.3 - Registers Divi footer
		1.4 - Register custom admin column headers
		1.5 - Register custom admin column data
		1.6 - Add login/logout tab to secondary menu
		1.7 - Register Ajax Actions
		1.8 - Load external files to public website
		1.9 - Add filter by author to program admin page
		1.10 - Add filter by author to workout admin page

	2. SHORTCODES
		2.1 - Registers all our custom shortcodes
		2.2 - List active program posts
        2.3 - List active fitness tests

	3. FILTERS
        3.1 - Add program column headers
		3.1b - Add workout column headers
        3.1c - Add fit test column headers
		3.2 - Add filter to select programs by 'author'
		3.2b - Add filter to select workouts by 'author'
        3.2c - Add filter to select fit test by 'author'
		3.3 - Restrict the program posts by author filter
		3.3b - Restrict the workout posts by author filter
        3.3c - Restrict the fit test posts by author filter

	4. EXTERNAL SCRIPTS
        4.1 - Loads external files into PUBLIC website
	
	5. ACTIONS
        5.1 - Update weight value for each set in exercise
		5.1b - Update results value for each exercise
		5.2 - Update results for each fit-test form ** NEW
		5.3 - Removed admin toolbar for all users except administrator

	6. HELPERS
        6.1 - Display name of php template used
		6.2 - Add login/logout link to secondary menu
		6.3 - Add custom login
		6.4 - Change login logo destination URL
		6.5 - Change login error message
		6.6 - Add pagination for archive templates
		6.7 - Add parent template style sheet
		6.8 - Update footer content for Divi footer

	7. CUSTOM POST TYPES
		7.1 - TBD

	8. ADMIN PAGES
		8.1 - TBD

	9. SETTINGS
		9.1 - TBD

*/


/* 1. HOOKS */

// 1.1
// hint: registers all our custom shortcodes on init
add_action( 'init', 'b2p_register_shortcodes' );

// 1.2
// hint: registers action to remove admin bar
add_action( 'after_setup_theme', 'remove_admin_bar' );

// 1.3
// hint: registers Divi footer
add_action( 'customize_register', 'emp_customize_register', 11 );
add_filter( 'template_include', 'var_template_include', 1000 );

// 1.4
// hint: register custom admin column headers
add_filter( 'manage_edit-b2p_program_columns','b2p_program_column_headers' );
add_filter( 'manage_edit-gf_workout_columns','gf_workout_column_headers' );
add_filter( 'manage_edit-hfc_fit_test_columns','hfc_fit_test_column_headers' );

// 1.5
// hint: register custom admin column data
//add_filter( 'manage_b2p_program_posts_custom_column','b2p_program_column_data',1,2 );

// 1.6
// hint: add login/logout tab to secondary menu
add_action( 'login_head', 'my_custom_login' );
add_filter( 'login_errors', 'login_error_override' );
add_filter( 'login_headertitle', 'my_login_logo_url_title' );
add_filter( 'login_headerurl', 'my_login_logo_url' );
add_filter( 'wp_nav_menu_items', 'add_login_logout_register_menu', 199, 2 );

// 1.7
// hint: register Ajax actions
add_action( 'wp_ajax_nopriv_b2p_update_exercise','b2p_update_exercise' ); 	// Program updates for regular website visitor
add_action( 'wp_ajax_b2p_update_exercise','b2p_update_exercise' ); 			// Program updates for admin user
add_action( 'wp_ajax_nopriv_gf_exercise_results','gf_exercise_results' ); 	// Workout updates for regular website visitor
add_action( 'wp_ajax_gf_exercise_results','gf_exercise_results' ); 			// Workout updates for admin user
add_action( 'wp_ajax_nopriv_hfc_update_fit_test','hfc_update_fit_test' ); 	// Fit-Test updates for regular website visitor - NEW
add_action( 'wp_ajax_hfc_update_fit_test','hfc_update_fit_test' ); 			// Fit-Test updates for admin user - NEW

// 1.8
// hint: Load external files to public website
add_action( 'wp_enqueue_scripts', 'b2p_public_scripts' );
add_action( 'wp_enqueue_scripts', 'my_enqueue_assets' ); 

// 1.9
// hint: add filter by author to program admin page
add_action('restrict_manage_posts','add_author_filter_to_posts_administration');
add_action('pre_get_posts','add_author_filter_to_posts_query');

// 1.10
// hint: add filter by author to workout admin page
add_action('restrict_manage_posts','add_author_filter_to_workout_administration');
add_action('pre_get_posts','add_author_filter_to_workout_query');

// 1.11
// hint: add filter by author to fit test admin page
add_action('restrict_manage_posts','add_author_filter_to_fit_test_administration');  // added for fit test post type in version 1.1.3
add_action('pre_get_posts','add_author_filter_to_fit_test_query');  // added for fit test post type in version 1.1.3




/* 2. SHORTCODES */

// 2.1
// hint: registers all our custom shortcodes
function b2p_register_shortcodes() {
	
	add_shortcode( 'b2p_active_list', 'b2p_active_shortcode' );
    add_shortcode( 'b2p_active_tests', 'b2p_active_test_shortcode' );
	
}

// 2.2
// hint: list active program posts using shortcode [b2p_active_list]
function b2p_active_shortcode( $args, $content="") {

	// setup our output variable - the form html 
		
	global $post;
	$current_user = wp_get_current_user();
	$member_name = $current_user->first_name . " " . $current_user->last_name;
	
	$user_args = array(
		'posts_per_page'   => 15,
		'post_type' => array('b2p_program', 'gf_workout'),
		'author' => $current_user->ID,
		'orderby' => 'post_date',
		'order' => 'ASC',
		'post_status' => 'publish',
	);
	
	$output = '<div class="member_post_sidebar">';
		
		$myposts = get_posts( $user_args );
	
		if($myposts) {
            
            $output .= '<ul class="prog_active_list">';
            
		foreach ( $myposts as $post ) : setup_postdata( $post );
	
			$output .= '<li class="prog_active_item">&#8594; <a href="'.get_permalink().'">'.get_the_title().'</a></li>';
	
		endforeach;
            
            $output .= '</ul>';
            
		}
    
		else {
			$output = 'No workouts assigned';
            
		}
	
	wp_reset_postdata();
	
	$output .= '</div>';

	// return our results/html
	return $output;
	
}


// 2.3
// hint: list active fitness test posts using shortcode [b2p_active_tests]
function b2p_active_test_shortcode( $args, $content="") {

	// setup our output variable - the form html 
		
	global $post;
	$current_user = wp_get_current_user();
	$member_name = $current_user->first_name . " " . $current_user->last_name;
	
	$user_args = array(
		'posts_per_page'   => 15,
		'post_type' => array('fit_test'),
		'author' => $current_user->ID,
		'orderby' => 'post_date',
		'order' => 'ASC',
		'post_status' => 'publish',
	);
	
	$output = '<div class="member_post_sidebar">';
		
		$myposts = get_posts( $user_args );
	
		if($myposts) {
            
            $output .= '<ul class="prog_active_list">';
            
		foreach ( $myposts as $post ) : setup_postdata( $post );
	
			$output .= '<li class="prog_active_item">&#8594; <a href="'.get_permalink().'">'.get_the_title().'</a></li>';
	
		endforeach;
            
            $output .= '</ul>';
            
		}
    
		else {
			$output = 'No Fitness Test Assigned';
            
		}
	
	wp_reset_postdata();
	
	$output .= '</div>';

	// return our results/html
	return $output;
	
}




/* 3. FILTERS */

// 3.1
// hint: Add program column headers
function b2p_program_column_headers( $columns ) {
	
	// creating custom column header data
	$columns = array(
		'cb'=>'<input type="checkbox" />',
		'title'=>__('Program Name'),
		'author' =>__('Member'),
		'date'=>__('Date')
	);
	
	// returning new columns
	return $columns;
	
}

// 3.1b
// hint: Add workout column headers
function gf_workout_column_headers( $columns ) {
	
	// creating custom column header data
	$columns = array(
		'cb'=>'<input type="checkbox" />',
		'title'=>__('Workout Name'),
		'author' =>__('Member'),
		'date'=>__('Date')
	);
	
	// returning new columns
	return $columns;
	
}

// 3.1c
// hint: Add fit-test column headers
function hfc_fit_test_column_headers( $columns ) {
	
	// creating custom column header data
	$columns = array(
		'cb'=>'<input type="checkbox" />',
		'title'=>__('Fitness Test Name'),
		'author' =>__('Member'),
		'date'=>__('Date')
	);
	
	// returning new columns
	return $columns;
	
}

// 3.2
// hint: defining the filter that will be used so we can select posts by 'author'
function add_author_filter_to_posts_administration(){

    //execute only on the 'post' content type
    global $post_type;
    if($post_type == 'b2p_program'){

        //get a listing of all users that are 'author' or above
        $user_args = array(
            'show_option_all'   => 'All Members',
            'orderby'           => 'display_name',
            'order'             => 'ASC',
            'name'              => 'author_admin_filter',
            'who'               => 'authors',
            'include_selected'  => true
        );

        //determine if we have selected a user to be filtered by already
        if(isset($_GET['author_admin_filter'])){
            //set the selected value to the value of the author
            $user_args['selected'] = (int)sanitize_text_field($_GET['author_admin_filter']);
        }

        //display the users as a drop down
        wp_dropdown_users($user_args);
    }

}

// 3.2b
// hint: defining the filter that will be used so we can select posts by 'author'
function add_author_filter_to_workout_administration(){

    //execute only on the 'post' content type
    global $post_type;
    if($post_type == 'gf_workout'){

        //get a listing of all users that are 'author' or above
        $user_args = array(
            'show_option_all'   => 'All Members',
            'orderby'           => 'display_name',
            'order'             => 'ASC',
            'name'              => 'author_admin_filter',
            'who'               => 'authors',
            'include_selected'  => true
        );

        //determine if we have selected a user to be filtered by already
        if(isset($_GET['author_admin_filter'])){
            //set the selected value to the value of the author
            $user_args['selected'] = (int)sanitize_text_field($_GET['author_admin_filter']);
        }

        //display the users as a drop down
        wp_dropdown_users($user_args);
    }

}

// 3.2c
// hint: defining the filter that will be used so we can select posts by 'author'
function add_author_filter_to_fit_test_administration(){

    //execute only on the 'post' content type
    global $post_type;
    if($post_type == 'fit_test'){

        //get a listing of all users that are 'author' or above
        $user_args = array(
            'show_option_all'   => 'All Members',
            'orderby'           => 'display_name',
            'order'             => 'ASC',
            'name'              => 'author_admin_filter',
            'who'               => 'authors',
            'include_selected'  => true
        );

        //determine if we have selected a user to be filtered by already
        if(isset($_GET['author_admin_filter'])){
            //set the selected value to the value of the author
            $user_args['selected'] = (int)sanitize_text_field($_GET['author_admin_filter']);
        }

        //display the users as a drop down
        wp_dropdown_users($user_args);
    }

}

// 3.3
// hint: restrict the posts by an additional author filter
function add_author_filter_to_posts_query($query){

    global $post_type, $pagenow; 

    //if we are currently on the edit screen of the post type listings
    if($pagenow == 'edit.php' && $post_type == 'b2p_program'){

        if(isset($_GET['author_admin_filter'])){

            //set the query variable for 'author' to the desired value
            $author_id = sanitize_text_field($_GET['author_admin_filter']);

            //if the author is not 0 (meaning all)
            if($author_id != 0){
                $query->query_vars['author'] = $author_id;
            }

        }
    }
}

// 3.3b
// hint: restrict the posts by an additional author filter
function add_author_filter_to_workout_query($query){

    global $post_type, $pagenow; 

    //if we are currently on the edit screen of the post type listings
    if($pagenow == 'edit.php' && $post_type == 'gf_workout'){

        if(isset($_GET['author_admin_filter'])){

            //set the query variable for 'author' to the desired value
            $author_id = sanitize_text_field($_GET['author_admin_filter']);

            //if the author is not 0 (meaning all)
            if($author_id != 0){
                $query->query_vars['author'] = $author_id;
            }

        }
    }
}

// 3.3c
// hint: restrict the posts by an additional author filter
function add_author_filter_to_fit_test_query($query){

    global $post_type, $pagenow; 

    //if we are currently on the edit screen of the post type listings
    if($pagenow == 'edit.php' && $post_type == 'fit_test'){

        if(isset($_GET['author_admin_filter'])){

            //set the query variable for 'author' to the desired value
            $author_id = sanitize_text_field($_GET['author_admin_filter']);

            //if the author is not 0 (meaning all)
            if($author_id != 0){
                $query->query_vars['author'] = $author_id;
            }

        }
    }
}




/* 4. EXTERNAL SCRIPTS */

// 4.1 Loads external files into PUBLIC website
function b2p_public_scripts() {
    
    // register scripts with WordPress's internal library
    wp_register_script('b2p-js-public', 'https://hockeyfitclub.com/wp-content/themes/divi-child-hockeyfitclub/js/public/b2p_js.js', array('jquery'),'',true);
	wp_register_script('gf-js-public', 'https://hockeyfitclub.com/wp-content/themes/divi-child-hockeyfitclub/js/public/gf_js.js', array('jquery'),'',true);
    wp_register_script('hfc-js-public', 'https://hockeyfitclub.com/wp-content/themes/divi-child-hockeyfitclub/js/public/hfc_fit_test_js.js', array('jquery'),'',true);
//    wp_register_style('b2p-css-public', 'https://hockeyfitclub.com/wp-content/themes/divi-child-hockeyfitclub/css/public/b2p_style.css');
	
    // add to que of scripts that get loaded into every page
    wp_enqueue_script('hfc-js-public');
    wp_enqueue_script('b2p-js-public');
	wp_enqueue_script('gf-js-public');
//	wp_enqueue_style('b2p-css-public');
    
}




/* 5. ACTIONS */

// 5.1
// hint: Update weight value for each set in exercise from exercise_form.php

function b2p_update_exercise(){
	
	if(isset($_POST)) {
		$count = count($_POST) - 4;
		$post_id = $_POST['program_id']; //passing post_id from exercise form
		$week = $_POST['b2p_week_num'];  //passing week number from exercise form
		$day = $_POST['b2p_day_num'];    //passing day number from exercise form
		$exercise = $_POST['b2p_exercise_num'];

			$set = 1;
			while($set <= $count) {
				$weight = $_POST['exercise_weight_' . $set];
				update_sub_field(array('b2p_week', $week, 'b2p_day', $day, 'b2p_program_exercises', $exercise, 'b2p_sets', $set, 'b2p_weight'), (int)$weight, $post_id);
				$set++;
			}
	}
}

// 5.1b
// hint: Update results value for each exercise from single-gf_workout.php

function gf_exercise_results(){
	
	if(isset($_POST)) {
		$count = count($_POST) - 2;
		$post_id = $_POST['program_id']; //passing post_id from workout form
		$day = $_POST['gf_day_num'];     //passing day number from workout form

		//update_sub_field(array('gf_workout_programs', 1, 'gf_exercises', 4, 'gf_exercise_results'), 'Test Data', 2565);
		
			$exercise = 1;

			while($exercise <= $count) {
				$results = $_POST['exercise_results_' . $exercise];
				update_sub_field(array('gf_workout_program', $day, 'gf_exercises', $exercise, 'gf_exercise_results'), $results, $post_id);
				$exercise++;
			}
	}
}

// 5.2
// hint: Update results for each fit-test form from single-fit_test.php

//function hfc_update_fit_test(){
//	
//	if(isset($_POST)) {
//		$count = count($_POST) - 1;
//		$post_id = $_POST['program_id']; //passing post_id from fit-test form
//        
//			$max_test = 1;
//
//			while($max_test <= $count) {
//				$start_value_results = $_POST['fit_test_starting_value_' . $max_test];
//                $end_value_results = $_POST['fit_test_ending_value_' . $max_test];
//				update_sub_field(array('maximum_performance_tests', 'max_performance_test_exercises', 'starting_value'), $start_value_results, $post_id);
//                update_sub_field(array('maximum_performance_tests', 'max_performance_test_exercises', 'ending_value'), $end_value_results, $post_id);
//				$max_test++;
//			}
//	}
//}

function hfc_update_fit_test(){

    if ( ! empty( $_POST ) && ! empty( $_POST['program_id'] ) )
    {
        $post_id = $_POST['program_id'];

        if ( ! empty( $_POST['test_start_date'] ) )
        {
            update_field( 'test_start_date', $_POST['test_start_date'], $post_id );
        }

        if ( ! empty( $_POST['test_end_date'] ) )
        {
            update_field( 'test_end_date', $_POST['test_end_date'], $post_id );
        }
        
        if ( ! empty( $_POST['coach_notes'] ) )
        {
            update_field( 'coach_notes', $_POST['coach_notes'], $post_id );
        }

        $tests = get_field('movement_performance_tests', $post_id);
        if ( ! empty( $tests ) )
        {

            $checks = array();
            if ( ! empty( $_POST['can_you_perform'] ) )
            {
                $checks = $_POST['can_you_perform'];
            }

            foreach( $tests as $row_index => &$row )
            {
                if ( in_array( $row_index, $checks ) )
                {
                    $row['can_you_perform'] = 'yes';
                }
                else
                {
                    $row['can_you_perform'] = '';
                }
            }
            update_field('movement_performance_tests',$tests, $post_id);

        }

        $exercises = get_field('maximum_performance_tests', $post_id);
        if ( ! empty( $exercises ) )
        {
            $starting_values = array();
            if ( ! empty( $_POST['starting_value'] ) )
            {
                $starting_values = $_POST['starting_value'];
            }

            $ending_values = array();
            if ( ! empty( $_POST['ending_value'] ) )
            {
                $ending_values = $_POST['ending_value'];
            }

            foreach ( $exercises as $row_index => &$exercise )
            {
                if ( isset( $starting_values[$row_index] ) )
                {
                    $exercise['starting_value'] = $starting_values[$row_index];
                }
                if ( isset( $ending_values[$row_index] ) )
                {
                    $exercise['ending_value']   = $ending_values[$row_index];
                }
            }

            update_field('maximum_performance_tests',$exercises, $post_id);
        }

    }

}
add_action( 'init', 'hfc_update_fit_test', 0 );



// 5.3
// hint: Removed admin toolbar for all users except administrator
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
	  show_admin_bar(false);
	}
}




/* 6. HELPERS */

// 6.1
// hint: display name of php template used
function var_template_include( $t ){
    $GLOBALS['current_theme_template'] = basename($t);
    return $t;
}

function get_current_template( $echo = false ) {
    if( !isset( $GLOBALS['current_theme_template'] ) )
        return false;
    if( $echo )
        echo $GLOBALS['current_theme_template'];
    else
        return $GLOBALS['current_theme_template'];
}

// 6.2
// hint: add login/logout link to secondary menu
function add_login_logout_register_menu( $items, $args ) {
	if ( $args->theme_location != 'secondary-menu' ) {
	return $items;
	}

	if ( is_user_logged_in() ) {
		$items .= '<li><a href="' . wp_logout_url() . '">' . __( 'Log Out' ) . '</a></li>';
	} else {
		$items .= '<li><a href="' . wp_login_url() . '">' . __( 'Member Login' ) . '</a></li>';
	}

	return $items;
}

// 6.3
// hint: add custom login
function my_custom_login() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/login/custom-login-styles.css" />';
}

// 6.4
// hint: change login logo destination URL
function my_login_logo_url() {
	return get_bloginfo( 'url' );
}

function my_login_logo_url_title() {
	return 'Welcome to Hockey Fit Club';
}

// 6.5
// hint: change login error message
function login_error_override() {
    return 'Incorrect login details.';
}

// 6.6
// hint: add pagination for archive templates
function pagination_bar() {
    global $wp_query;
 
    $total_pages = $wp_query->max_num_pages;
 
    if ($total_pages > 1){
        $current_page = max(1, get_query_var('paged'));
 
        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '/page/%#%',
            'current' => $current_page,
            'total' => $total_pages,
        ));
    }
}

// 6.7
// hint: add parent template style sheet
function my_enqueue_assets() { 
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' ); 
} 

// 6.8
// hint: update footer content for Divi footer
function emp_customize_register($wp_customize) {
	$wp_customize->add_setting( 'et_divi[footer_copr_text]', array(
		'default'       => 'Designed by Lucid Wisdom <a href="https://lucidwisdom.com">Lucid Wisdom Digital Strategies</a>',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
		//'sanitize_callback' => 'wp_validate_boolean',
	) );

	$wp_customize->add_control( 'et_divi[footer_copr_text]', array(
		'label'		=> __( 'Enter Copyright Text', 'Divi' ),
		'section'	=> 'et_divi_footer_elements',
		'type'      => 'text',
	) );
}

?>