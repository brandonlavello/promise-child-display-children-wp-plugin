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
  <br><br><br><br>
  </p>
  <?php 
    //check if child is random generated - AKA Null child 
    if ($child->get_flag()){
      echo "<h2>Oops! The child you are looking for cannot be found.</h2>";
      echo "<h4>But this child cannot afford to be forgetten.</h4>";
      echo "<h4>Consider sponsoring this child.</h4><br><br>";
    }
  ?>
  <h3>
    Child Details
  </h3>
  <div class="elementor-element elementor-element-1394703 aleft elementor-widget elementor-widget-wgl-give-forms"> 
  <section class="wgl-donation">
    <div class="wgl-donation__grid grid-col--1 horizontal-layout"> 
      <article class="wgl-donation__card">
        <div class="card__container">
          <div class="card__media"> 
              <img class="donation-img" src= "<?php echo $child->get_image_path();?>" alt="Child Image" width="500vw">
            </div> <!-- End card__media -->

          <div class="card__content"> 
            <h4>
              <?php echo $child->get_name();?>
            </h4>
            <p class="card__excerpt">
                <strong>Location: </strong> <?php echo $child->get_public_location();?> <br>
                <strong>Gender: </strong> <?php echo $child->get_gender();?> <br>
                <strong>Age & Grade: </strong> <?php echo $child->get_formatted_age() . " in " . $child->get_grade();;?> <br>
                <strong>Caretaker: </strong> <?php echo $child->get_caretaker();?> <br>
                <strong>School Status: </strong> <?php echo $child->get_school_status();?> <br>
                <strong>Religious Beliefs: </strong> <?php echo $child->get_religious_beliefs();?> <br>
                <strong>Health Issues: </strong> <?php echo $child->get_health_issues();?> <br>
                <strong>Interests: </strong> <?php echo $child->get_interests();?> <br>
                <strong>Prayer Requests: </strong> <?php echo $child->get_prayer_requests();?> <br>
              </p> 
              <a class="wgl-button btn-size-sm" target="_blank" role="button" href="<?php echo $child->get_donation_link();?>">
                <div class="button-content-wrapper">
                  <span class="wgl-button-text">Sponsor</span>
                </div>
              </a>
          </div>
        </div> <!-- End card__container -->
      </article> <!-- end article wgl-donation__card -->
    </div> <!-- End Dive wgl-donation__grid grid-col--1 horizontal-layout -->
  </section> <!-- End class="wgl-donation" --> 
</div>

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