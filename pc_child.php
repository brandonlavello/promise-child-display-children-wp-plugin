<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Pc_child {

  //Declare Child Variables
  private $child_id;
  private $name;
  private $website_status;
  private $image_path;
  private $donation_link;
  private $public_location;
  private $gender;
  private $formatted_age;
  private $grade;
  private $caretaker;
  private $school_status;
  private $religious_beleifs;
  private $health_status;
  private $interests;
  private $prayer_requests;

  // Constructor - Initialize child, request data from API to set variables
  public function __construct($child_id){
    // echo nl2br ("In Constructor\n");
    // This is initializing the class properties
    
    $this->child_id=$child_id;
    //request_child_data requests data and sets all variables appropriately
  } // End Constructor

  function __destruct() {
    // echo nl2br ("In Destructor\n");
  }

  // Getters 
  public function get_child_id() {
      return ($this->child_id);
  }
  public function get_name() {
    return ($this->name);
  }
  public function get_image_path() {
    return ($this->image_path);
  }
  public function get_website_status() {
    return ($this->website_status);
  }
  public function get_donation_link() {
    return ($this->donation_link);
  }
  public function get_public_location() {
    return ($this->public_location);
  }
  public function get_gender() {
    return ($this->gender);
  }
  public function get_formatted_age() {
    return ($this->formatted_age);
  }
  public function get_grade() {
    return ($this->grade);
  }
  public function get_caretaker() {
    return ($this->caretaker);
  }
  public function get_school_status() {
    return ($this->school_status);
  }
  public function get_religious_beliefs() {
    return ($this->religious_beliefs);
  }
  public function get_health_issues() {
    return ($this->health_issues);
  }
  public function get_interests() {
    return ($this->interests);
  }
  public function get_prayer_requests() {
    return ($this->prayer_requests);
  }
  public function get_child_details() {
    $this->request_child_data();
  }  


  // Setters
  public function set_name($name) {
    $this->name=$name;
  }
  public function set_website_status($website_status) {
    $this->website_status=$website_status;
  }
  public function set_image_path($image_path) {
    $this->image_path=$image_path;
  }
  public function set_donation_link($donation_link) {
    $this->donation_link=$donation_link;
  }
  public function set_public_location($public_location) {
    $this->public_location=$public_location;
  }
  public function set_gender($gender) {
    $this->gender=$gender;
  }
  public function set_formatted_age($formatted_age) {
    $this->formatted_age=$formatted_age;
  }
  public function set_grade($grade) {
    $this->grade=$grade;
  }
  public function set_caretaker($caretaker) {
    $this->caretaker=$caretaker;
  }
  public function set_school_status($school_status) {
    $this->school_status=$school_status;
  }
  public function set_religious_beliefs($religious_beliefs) {
    $this->religious_beliefs=$religious_beliefs;
  }
  public function set_health_issues($health_issues) {
    $this->health_issues=$health_issues;
  }
  public function set_interests($interests) {
    $this->interests=$interests;
  }
  public function set_prayer_requests($prayer_requests) {
    $this->prayer_requests=$prayer_requests;
  }


  // Request Child Data from PC API
  private function request_child_data() {
    // echo nl2br ("request child data\n"); 
    
    //Prepare query variables
    $query_type = 'children';
    $query_where = [
        'childId' => '{eq:'. $this->child_id .'}',
        ];
    $query_order = [
      ];
    $query_response_attributes = 'childId imagePath donationLink publicLocation gender formatedAge websiteStatus grade caretaker schoolStatus religiousBeliefs healthIssues interests prayerRequests';

    $api = new Pc_API_Request('https://graphql.promisechild.org/graphql/');
    $response = $api->get_data($query_type,$query_where,$query_order,$query_response_attributes);

    $extracted_child = $response['data']['children'][0];

    // Set object variables to values Requested from PC API
    //websiteStatus
    if (array_key_exists("websiteStatus", $extracted_child)) {
      $this->set_website_status($extracted_child["websiteStatus"]);
      // echo $this->get_name();
    } else {$this->set_website_status("");}
    
    //name
    if (array_key_exists("name", $extracted_child)) {
      $this->set_name($extracted_child["name"]);
      // echo $this->get_name();
    } else {$this->set_name("");}
    
    //imagePath
    if (array_key_exists("imagePath", $extracted_child)) {
      $this->set_image_path($extracted_child["imagePath"]);
    } else {$this->set_image_path("");}
    
    //donationLink
    if (array_key_exists("donationLink", $extracted_child)) {
      $this->set_donation_link($extracted_child["donationLink"]);
    } else {$this->set_donation_link("");}

    //publicLocation
    if (array_key_exists("publicLocation", $extracted_child)) {
      $this->set_public_location($extracted_child["publicLocation"]);
    } else {$this->set_public_location("");}
    
    //gender
    if (array_key_exists("gender", $extracted_child)) {
      $this->set_gender($extracted_child["gender"]);
    } else {$this->set_gender("");}
    
    //formatedAge
    if (array_key_exists("formatedAge", $extracted_child)) {
      $this->set_formatted_age($extracted_child["formatedAge"]);
    } else {$this->set_formatted_age("");}
    
    //grade
    if (array_key_exists("grade", $extracted_child)) {
      $this->set_grade($extracted_child["grade"]);
    } else {$this->set_grade("");}
    
    //caretaker
    if (array_key_exists("caretaker", $extracted_child)) {
      $this->set_caretaker($extracted_child["caretaker"]);
    } else {$this->set_caretaker("");}
    
    //schoolStatus
    if (array_key_exists("schoolStatus", $extracted_child)) {
      $this->set_school_status($extracted_child["schoolStatus"]);
    } else {$this->set_school_status("");}
    
    //religiousBeliefs
    if (array_key_exists("religiousBeliefs", $extracted_child)) {
      $this->set_religious_beliefs($extracted_child["religiousBeliefs"]);
    } else {$this->set_religious_beliefs("");}
    
    //healthIssues
    if (array_key_exists("healthIssues", $extracted_child)) {
      $this->set_health_issues($extracted_child["healthIssues"]);
    } else {$this->set_health_issues("");}
    
    //interests
    if (array_key_exists("interests", $extracted_child)) {
      $this->set_interests($extracted_child["interests"]);
    } else {$this->set_interests("");}
    
    //prayerRequests
    if (array_key_exists("prayerRequests", $extracted_child)) {
      $this->set_prayer_requests($extracted_child["prayerRequests"]);
    } else {$this->set_prayer_requests("");}

      // echo nl2br ("exit requested child data\n"); 
  }
}
?>
