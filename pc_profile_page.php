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

  // H1 Header
  ?>
  </p>
  <h1> <?php echo $child->get_name(); ?> </h1>
  <p>
    <strong>ID: </strong> <?php echo $child->get_child_id(); ?> <br>
    <strong>Name: </strong>  <?php echo $child->get_name(); ?> <br>
    <strong>Website Status: </strong> <?php echo $child->get_website_status();?> <br>
    <strong>Image Path: </strong> <?php echo $child->get_image_path();?> <br>
    <strong>Donation Link: </strong> <?php echo $child->get_donation_link();?> <br>
    <strong>Public Location: </strong> <?php echo $child->get_public_location();?> <br>
    <strong>Gender: </strong> <?php echo $child->get_gender();?> <br>
    <strong>Formatted Age: </strong> <?php echo $child->get_formatted_age();?> <br>
    <strong>Grade: </strong> <?php echo $child->get_grade();?> <br>
    <strong>Caretaker: </strong> <?php echo $child->get_caretaker();?> <br>
    <strong>School Status: </strong> <?php echo $child->get_school_status();?> <br>
    <strong>Religious Beliefs: </strong> <?php echo $child->get_religious_beliefs();?> <br>
    <strong>Health Issues: </strong> <?php echo $child->get_health_issues();?> <br>
    <strong>Interests: </strong> <?php echo $child->get_interests();?> <br>
    <strong>Prayer Requests: </strong> <?php echo $child->get_interests();?> <br>
  </p>
  <img src= "<?php echo $child->get_image_path();?>" alt="Child Image" width="128" height="128">

  <?php

  // get all buffered output, store it to string
  ob_start();
  $output_string = ob_get_contents();

  // clean buffer
  ob_end_clean();

  // return output
  return $output_string;

} // end Build child HTML
?>