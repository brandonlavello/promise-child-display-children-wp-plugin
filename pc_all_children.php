<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//Get all children, process and return child object array
function get_all_children($page,$country) {
  //prepare query variables
  $query_type = 'children';
  $query_where = [
      'allowOnlineDonations' => '{eq:\"Yes\"}'
      ];
  
  // check if country is selected in dropdown
  // Page count changes based on country selected or not selected:
  // - pageNumberPubLocation vs pageNumberAll
  if ($country != "All") {
    $query_where['publicLocation'] = '{eq:\"' . $country . '\"}';
    $query_where['pageNumberPubLocation'] = '{eq:' . $page . '}';
    $query_order = [
      'rowNumberPubLocation' => 'ASC',
    ];
  } else {
    $query_where['pageNumberAll'] = '{eq:' . $page . '}';
    $query_order = [
      'rowNumberAll' => 'ASC',
    ];
  }
  // rowNumberAll for sorting
  // TODO: Implement Row Number Sorting for Pub Location
  $query_response_attributes = 'childId name pageNumberAll publicLocation imagePath donationLink';

  $api = new Pc_API_Request('https://graphql.promisechild.org/graphql/');
  $response = $api->get_data($query_type,$query_where,$query_order,$query_response_attributes);
  
  $child_obj_array = array();

  //Process each child, store in Pc_child obj variable
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
  //return array of Pc_child objects
  return $child_obj_array;
}


function build_children_HTML($child_obj_array,$page,$countries) {
  $selected_country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : 'All';

  ?>
  
  <!-- Country dropdown menu -->
        <label for="graphql-api-country">Filter by Country:</label>
        <select id="graphql-api-country">
          <option value="All">All</option>
            <?php foreach ($countries as $country) : ?>
                <?php
                // Check if the current country is the selected country and set the "selected" attribute
                $selected_attr = ($selected_country === $country) ? 'selected' : '';
                ?>
                <option value="<?php echo $country; ?>" <?php echo $selected_attr; ?>><?php echo $country; ?></option>
            <?php endforeach; ?>
        </select>
        

    <!-- Render child data -->
    <?php foreach ($child_obj_array as $child_obj) { ?>
      <div class="graphql-api-item"><?php
        echo "\n" . $child_obj->get_name();
        echo "\n" . $child_obj->get_public_location();
        echo "\n" . $child_obj->get_image_path();
        echo "\n" . $child_obj->get_donation_link(); ?>
      </div>

    <?php } 
      //Get total page count
      //Pass in the selected country from the dropdown about (set from _POST)
      $total_pages = get_total_pages($selected_country);
    ?>

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
  <?php
}

//Gets total page count for pagination
function get_total_pages($country){
  $api = new Pc_API_Request('https://graphql.promisechild.org/graphql/');
  $query_type = "publicLocations";
  
  // Determine if All Countries or Single Country
  if ($country != "All") {
    $query_where = [
      'location' => '{eq:\"' . $country . '\"}',
    ];
  } else {
    $query_where = [
      'location' => '{neq:\"\"}',
    ];
  }
  $query_order = [ ];
  $query_response_attributes = 'location totalPagesAll totalPagesPubLocation';
  $response = $api->get_data($query_type,$query_where,$query_order,$query_response_attributes);

  if ($country != "All") {
    $total_pages = $response['data']['publicLocations'][0]['totalPagesPubLocation'];
  } else {
    $total_pages = $response['data']['publicLocations'][0]['totalPagesAll'];
  }

  // return [$total_pages, $countries];
    return $total_pages;
}

//Gets countries for filtering
function get_country_list(){
  $api = new Pc_API_Request('https://graphql.promisechild.org/graphql/');

  $query_type = "publicLocations";
  $query_where = [
    'location' => '{neq:\"\"}',
  ];

  $query_order = [ ];
  $query_response_attributes = 'location totalPagesAll totalPagesPubLocation';
  $response = $api->get_data($query_type,$query_where,$query_order,$query_response_attributes);

  // FUTURE TO DO:
  // Maybe add Number of children in country next to dropdown? not sure... 
  // There is some extra code in this function for that potentially.

  // if ($country != "All") {
  //   $total_pages = $response['data']['publicLocations'][0]['totalPagesPubLocation'];
  // } else {
  //   $total_pages = $response['data']['publicLocations'][0]['totalPagesAll'];
  // }

  $countries = array();

  foreach ($response['data']['publicLocations'] as $location) {
    array_push($countries,$location["location"]);
  }

  // return [$total_pages, $countries];
    return $countries;

}

?>