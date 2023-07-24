<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Gets child from database, returns child
function get_child_details($child_id) {

  $child_obj= new Pc_child($child_id);
  $child_obj->get_child_details();

  return $child_obj;

} //end get child details

// Build all HTML to Display Child
function build_child_HTML($child) {
  
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

} // end Build child HTML
?>