<?php
/*
    Plugin Name: Promise Child Plugin V2
    Plugin URI:
    Description: Display all PC Children in need of sponsors of front end of site.
    *            In addition, displays a detailed profile card of child.
    * Version: 0.3
    * Author URI:
    * Author: Brandon Lavello
    * Author URI: https://brandonlavello.com/
    * License: GNU GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Includes
include_once( plugin_dir_path( __FILE__ ) . 'pc_child.php');
include_once( plugin_dir_path( __FILE__ ) . 'pc_api_request.php');
include_once( plugin_dir_path( __FILE__ ) . 'pc_profile_page.php');
include_once( plugin_dir_path( __FILE__ ) . 'pc_all_children.php');

// Add Actions
add_action('admin_menu', 'pc_admin_menu_init');
add_action('wp_enqueue_scripts', 'pc_v2_plugin_enqueue_scripts');
add_action('wp_ajax_graphql_api_ajax_pagination', 'graphql_api_ajax_pagination');
add_action('wp_ajax_nopriv_graphql_api_ajax_pagination', 'graphql_api_ajax_pagination');

// All Shortcode adds
add_shortcode('pc_display_child_profile', 'pc_display_child_profile_init');
add_shortcode('pc_display_all_children', 'pc_display_all_children_init');

//----------------------------------------
// All Enqueue scripts and styles
function pc_v2_plugin_enqueue_scripts() {
    wp_enqueue_script('graphql-api-plugin', plugin_dir_url(__FILE__) . 'js/graphql-api-plugin.js', array('jquery'), 'null', true);
    wp_localize_script('graphql-api-plugin', 'graphql_api_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
}

// Add admin page function
function pc_admin_menu_init(){
    add_menu_page( 'Promise Child Plugin Page', 'PC Children', 'manage_options', 'pc_plugin', 'pc_admin_page');
} // end initialization of PC Admin Page

//----------------------------------------
// Write Content to PC admin page
function pc_admin_page(){
  ?>
      <h1>Promise Child Children</h1>
      <h2>Select a country from the dropdown to generate a shortcode.</h2>
      <p>Once a shortcode has been generated, copy and paste it to the page you would like.
      <br />
      <br />      
  <?php
} //end pc admin page


//----------------------------------------
// Functions for Displaying Promise Child Profile Card Page

// PC Display Child Profile Card function
function pc_display_child_profile_init($atts) {

  # get child_id from URL parameter -- Sanitize and assign to child_id
  $child_id = ( isset( $_GET['child_id'] ) ) ? sanitize_text_field( $_GET['child_id'] ) : '0';

  # get child
  $child = get_child_details($child_id);

  $HTML_string = write_child_HTML($child);

  echo $HTML_string;
}



//----------------------------------------
// Functions for Displaying All Promise Children

// First time page loads
function pc_display_all_children_init(){
  echo "<h1>Promise Child Children</h1>";
  
  //First time we are here, so it set page to 1
  $page = 1;
  $total_pages = get_total_pages();
  $child_obj_array = get_all_children($page);
  
  ob_start();

  write_children_HTML($child_obj_array,$page,$total_pages);
  
  return ob_get_clean();
}

// All consecutive page loads
function graphql_api_ajax_pagination() {
  $total_pages = get_total_pages();

  if (isset($_POST['page'])) {
      $page = intval($_POST['page']);
      $child_obj_array = get_all_children($page);

      ob_start();
      
      write_children_HTML($child_obj_array,$page,$total_pages);

      $output = ob_get_clean();
      echo $output;
  }
  wp_die();
}

//Gets total page count for pagination
function get_total_pages(){
  $api = new Pc_API_Request('https://graphql.promisechild.org/graphql/');

  $query_type = "publicLocations";
  $query_where = [
    'location' => '{neq:\"\"}',
  ];
  $query_order = [ ];
  $query_response_attributes = 'location totalPagesAll';
  $response = $api->get_data($query_type,$query_where,$query_order,$query_response_attributes);
  $total_pages = $response['data']['publicLocations'][0]['totalPagesAll'];
  return $total_pages;
}