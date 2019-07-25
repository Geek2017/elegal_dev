 // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      var placeSearch, autocomplete;
      var componentForm = {
        "street_number": 'short_name',
        route: 'long_name',
        neighborhood: 'long_name',
        administrative_area_level_5: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        administrative_area_level_2: 'long_name',
        country: 'long_name',
        postal_code: 'short_name'
      };


      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.

        let acInputs = document.getElementsByClassName('autocomplete');

        for(i = 0; i < acInputs.length; i++){
          autocomplete = new google.maps.places.Autocomplete(
                              (acInputs[i]),
                              {types: ['geocode'],
                              componentRestrictions: {country: 'ph'}
                            });
          autocomplete.inputId = acInputs[i].id;

          google.maps.event.addListener(autocomplete, 'place_changed', function(){fillInAddress(this.getPlace(),this.inputId)});
        }
      }

      function fillInAddress(whichForm,acInput){
        // Get the place details from the autocomplete object.
        var place = whichForm;
        // clearInputs("#tab-2");
      for (var component in componentForm){
          document.getElementsByClassName(`${component}_${acInput}`)[0].value = "";
      }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementsByClassName(`${addressType}_${acInput}`)[0].value = val;
          }
          if (addressType === 'administrative_area_level_2') {i++;}
        }
      }