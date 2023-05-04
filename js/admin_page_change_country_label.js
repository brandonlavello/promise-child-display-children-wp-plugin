function changeCountryLabel() {
  var selectField = document.getElementById("countrySelect");
  var inputField = document.getElementById("countryLabel");

  var selectedCountry = selectField.options[selectField.selectedIndex].value;

  if (selectedCountry != ""){
    selectedCountry = ' country=\"' + selectedCountry + '\"';
  }

  inputField.value = '[promise_child_children_plugin' + selectedCountry +']';

}
