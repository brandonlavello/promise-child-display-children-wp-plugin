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

// Includes
include_once( plugin_dir_path( __FILE__ ) . 'pc_child.php');

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// ADD Actions
add_action('admin_menu', 'pc_admin_menu_init');

// Add admin page
function pc_admin_menu_init(){
    add_menu_page( 'Promise Child Plugin Page', 'PC Children', 'manage_options', 'pc_plugin', 'pc_admin_page');
} // end initialization of PC Admin Page

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

// gets child from database, returns child
function get_child_details($child_id) {

  $child_obj= new Pc_child($child_id);

  return $child_obj;

} //end get child details

// Write all HTML to Display Child
function write_child_HTML($child) {
  
  $output_string = "";
  // open buffer to store output
  // all echo output goes through buffer
	ob_start();

  // H1 Header
  echo "<h1>Promise Child Display Child</h1>";
  echo "<p>";
  echo "<strong>ID: </strong>" . $child->get_child_id();
  echo nl2br ("\n<strong>Name: </strong>" . $child->get_name());
  echo nl2br ("\n<strong>Website Status: </strong>" . $child->get_website_status());
  echo nl2br ("\n<strong>Image Path: </strong>" . $child->get_image_path());
  echo '<img src= "'. $child->get_image_path() . '" alt="Child Image" width="128" height="128">';
  echo nl2br ("\n<strong>Donation Link: </strong>" . $child->get_donation_link());
  echo nl2br ("\n<strong>Public Location: </strong>" . $child->get_public_location());
  echo nl2br ("\n<strong>Gender: </strong>" . $child->get_gender());
  echo nl2br ("\n<strong>Formatted Age: </strong>" . $child->get_formatted_age());
  echo nl2br ("\n<strong>Grade: </strong>" . $child->get_grade());
  echo nl2br ("\n<strong>Caretaker: </strong>" . $child->get_caretaker());
  echo nl2br ("\n<strong>School Status: </strong>" . $child->get_school_status());
  echo nl2br ("\n<strong>Religious Beliefs: </strong>" . $child->get_religious_beliefs());
  echo nl2br ("\n<strong>Health Issues: </strong>" . $child->get_health_issues());
  echo nl2br ("\n<strong>Interests: </strong>" . $child->get_interests());
  echo nl2br ("\n<strong>Prayer Requests: </strong>" . $child->get_prayer_requests());

  echo "</p>";
  // get all buffered output, store it to string
  $output_string = ob_get_contents();

  // clean buffer
  ob_end_clean();

  // return output
  return $output_string;

} // end write child HTML





//----------------------------------------
// Functions for Displaying All Promise Children

function pc_display_all_children_init(){

  echo "<h1>Promise Child Children</h1>";

  $args = array(
    'headers' => array(
        'content-type' => 'application/json'
    ),
    'httpversion' => '1.1',
    'method' => 'POST',
    'body' => '{"query": "query { children(where:{allowStewardshipDonations:{eq:\"Yes\"}},order:{childId:ASC}){childId name } }"}'
);
   //TODO:
   $request = wp_remote_post( 'https://graphql.promisechild.org/graphql/', $args);

   if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
      return false;
   }

   $response = json_decode(wp_remote_retrieve_body($request),true);

   print_r($response);

    // foreach ($response['data']['children'] as $child) {
    //   $child_obj= new Pc_child($child_id);
    //   $response_results[$child['childId']]['name'] = $child['name'];

    // }


  //

}



//----------------------------------------
// All Shortcode adds
add_shortcode('pc_display_child_profile', 'pc_display_child_profile_init');
add_shortcode('pc_display_all_children', 'pc_display_all_children_init');

