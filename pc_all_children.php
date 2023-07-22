<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//Get all children, process and return child object array
function get_all_children($page) {
  //prepare query variables
  $query_type = 'children';
  $query_where = [
      'allowOnlineDonations' => '{eq:\"Yes\"}',
      'pageNumberAll' => '{eq:' . $page . '}',
      ];
  $query_order = [
      'rowNumberAll' => 'ASC',
    ];
  $query_response_attributes = 'childId name pageNumberAll publicLocation imagePath donationLink';

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
  return $child_obj_array;
}

function write_children_HTML($child_obj_array,$page,$total_pages,$countries) {

  ?>
  <div id="graphql-api-container">

  <!-- Country dropdown menu -->
        <label for="graphql-api-country">Filter by Country:</label>
        <select id="graphql-api-country">
          <option value="">All</option>
            <?php foreach ($countries as $country) : ?>
                <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
            <?php endforeach; ?>
        </select>

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
}

//Gets total page count and countries for pagination and filtering
function get_total_pages_and_countries(){
  $api = new Pc_API_Request('https://graphql.promisechild.org/graphql/');

  $query_type = "publicLocations";
  $query_where = [
    'location' => '{neq:\"\"}',
  ];
  $query_order = [ ];
  $query_response_attributes = 'location totalPagesAll';
  $response = $api->get_data($query_type,$query_where,$query_order,$query_response_attributes);

  $total_pages = $response['data']['publicLocations'][0]['totalPagesAll'];
  
  $countries = array();

  foreach ($response['data']['publicLocations'] as $location) {
    array_push($countries,$location["location"]);
  }

  // return [$total_pages, $countries];
    return array('total_pages' =>  $total_pages,'countries' => $countries);

}



?>