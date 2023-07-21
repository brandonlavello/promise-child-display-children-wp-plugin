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

// Add Actions
add_action('admin_menu', 'pc_admin_menu_init');
add_action('wp_enqueue_scripts', 'pc_v2_plugin_enqueue_scripts');
add_action('wp_ajax_graphql_api_ajax_pagination', 'graphql_api_ajax_pagination');
add_action('wp_ajax_nopriv_graphql_api_ajax_pagination', 'graphql_api_ajax_pagination');

// All Shortcode adds
add_shortcode('pc_display_child_profile', 'pc_display_child_profile_init');
add_shortcode('pc_display_all_children', 'pc_display_all_children_init');


// Add admin page function
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
// All Enqueue scripts and styles
function pc_v2_plugin_enqueue_scripts() {
    wp_enqueue_script('graphql-api-plugin', plugin_dir_url(__FILE__) . 'js/graphql-api-plugin.js', array('jquery'), 'null', true);
    wp_localize_script('graphql-api-plugin', 'graphql_api_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
}




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

// Gets child from database, returns child
function get_child_details($child_id) {

  $child_obj= new Pc_child($child_id);
  $child_obj->get_child_details();

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
  $total_pages = get_total_pages();

  //prepare query variables
  $page = 1;
  $query_type = 'children';
  $query_where = [
      'allowOnlineDonations' => '{eq:\"Yes\"}',
      'pageNumberAll' => '{eq:' . $page . '}'
      ];
  $query_order = [
      'rowNumberAll' => 'ASC',
    ];
  $query_response_attributes = 'childId name pageNumberAll publicLocation imagePath donationLink';
  

  echo "<h1>Promise Child Children</h1>";

  $api = new Pc_API_Request('https://graphql.promisechild.org/graphql/');
  $response = $api->get_data($query_type,$query_where,$query_order,$query_response_attributes);
  
  $child_obj_array = array();

  foreach ($response['data']['children'] as $child) {
    $child_obj = new Pc_child($child["childId"]);
    $child_obj_array[$child["childId"]] = $child_obj;

    // $response_results[$child['childId']]['name'] = $child['name'];
    if (array_key_exists("name", $child)) {
      $child_obj->set_name($child["name"]);
    } else {$child_obj->set_name("");}
    
    // $response_results[$child['childId']]['publicLocation'] = $child['publicLocation'];
    if (array_key_exists("publicLocation", $child)) {
      $child_obj->set_public_location($child["publicLocation"]);
    } else {$child_obj->set_public_location("");}

    // $response_results[$child['childId']]['imagePath'] = $child['imagePath'];
    if (array_key_exists("imagePath", $child)) {
      $child_obj->set_image_path($child["imagePath"]);
    } else {$child_obj->set_image_path("");}

    // $response_results[$child['childId']]['donationLink'] = $child['donationLink'];
    if (array_key_exists("donationLink", $child)) {
      $child_obj->set_donation_link($child["donationLink"]);
    } else {$child_obj->set_donation_link("");}

  }
    
  ob_start();
  ?>
  <div id="graphql-api-container">
    <!-- Render your data here -->
    <?php foreach ($child_obj_array as $child_obj) { ?>
      <div class="graphql-api-item"><?php
        // if (array_key_exists("pageNumberAll", $child)) {
        //   echo "\nPage #: " . $child["pageNumberAll"];
        // }
        echo "\n" . $child_obj->get_name();
        echo "\n" . $child_obj->get_public_location();
        echo "\n" . $child_obj->get_image_path();
        echo "\n" . $child_obj->get_donation_link(); ?>
      </div>

    <?php } ?>

    <!-- Next/Previous page buttons -->
    <div class="graphql-api-pagination">
      <?php if($page > 1) { ?>
        <button class="graphql-api-button" data-page="<?php echo $page - 1; ?>">Previous</button>
      <?php } ?>
      <?php if($page < $total_pages) { ?>
        <button class="graphql-api-button" data-page="<?php echo $page + 1; ?>">Next</button>
      <?php } ?> 
      <?php echo "<p>" . $page . " of " . $total_pages . "</p>"; ?> 
    </div>
  </div>
  <?php

  return ob_get_clean();
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

function graphql_api_ajax_pagination() {
  $total_pages = get_total_pages();

  $api = new Pc_API_Request('https://graphql.promisechild.org/graphql/');

  if (isset($_POST['page'])) {
      $page = intval($_POST['page']);
      $query_type = 'children';
      $query_where = [
          'allowOnlineDonations' => '{eq:\"Yes\"}',
          'pageNumberAll' => '{eq:' . $page . '}'
          ];
      $query_order = [
          'rowNumberAll' => 'ASC',
        ];
      $query_response_attributes = 'childId name pageNumberAll publicLocation imagePath donationLink';
      $response = $api->get_data($query_type,$query_where,$query_order,$query_response_attributes);


      $child_obj_array = array();

    foreach ($response['data']['children'] as $child) {
      $child_obj = new Pc_child($child["childId"]);
      $child_obj_array[$child["childId"]] = $child_obj;

      // $response_results[$child['childId']]['name'] = $child['name'];
      if (array_key_exists("name", $child)) {
        $child_obj->set_name($child["name"]);
      } else {$child_obj->set_name("");}
      
      // $response_results[$child['childId']]['publicLocation'] = $child['publicLocation'];
      if (array_key_exists("publicLocation", $child)) {
        $child_obj->set_public_location($child["publicLocation"]);
      } else {$child_obj->set_public_location("");}

      // $response_results[$child['childId']]['imagePath'] = $child['imagePath'];
      if (array_key_exists("imagePath", $child)) {
        $child_obj->set_image_path($child["imagePath"]);
      } else {$child_obj->set_image_path("");}

      // $response_results[$child['childId']]['donationLink'] = $child['donationLink'];
      if (array_key_exists("donationLink", $child)) {
        $child_obj->set_donation_link($child["donationLink"]);
      } else {$child_obj->set_donation_link("");}
    } //end for each

      ob_start();
      ?>
      <!-- Render the new data and buttons similar to the shortcode function earlier-->

      <div id="graphql-api-container">
        <?php foreach ($child_obj_array as $child_obj) { ?>
          <div class="graphql-api-item"><?php
            // if (array_key_exists("pageNumberAll", $child)) {
            //   echo "\nPage #: " . $child["pageNumberAll"];
            // }
            echo "\n" . $child_obj->get_name();
            echo "\n" . $child_obj->get_public_location();
            echo "\n" . $child_obj->get_image_path();
            echo "\n" . $child_obj->get_donation_link(); ?>
          </div>

        <?php } ?>

        <!-- Next/Previous page buttons -->
        <div class="graphql-api-pagination">
          <?php if($page > 1) { ?>
            <button class="graphql-api-button" data-page="<?php echo $page - 1; ?>">Previous</button>
          <?php } ?>
          <?php if($page < $total_pages) { ?>
            <button class="graphql-api-button" data-page="<?php echo $page + 1; ?>">Next</button>
          <?php } ?> 
          <?php echo "<p>" . $page . " of " . $total_pages . "</p>"; ?> 
        </div>
      </div>
      
      <?php

      $output = ob_get_clean();
      echo $output;

  }
  wp_die();
}