function loadGoogleMapScript() {
  var script = document.createElement("script");
  script.type = "text/javascript";
  script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize&language=en";
  document.body.appendChild(script);
}

  window.onload = loadGoogleMapScript;
  //var geocoder;
    var map;

  function initialize() {
    var latlng = new google.maps.LatLng(40.713956,-100.019531);
    var myOptions = {
      zoom: 12,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    var  address = document.getElementById("addressString").value;
    codeAddress(address) ;
    $(document).ready(function(e){
        var addressSource = document.getElementById("addressString").value;
        var href = 'http://maps.google.com/maps?q='+addressSource+'&z=12';
        $(".GoogleMapLink").attr("href",href);
        var addressDestination = document.getElementById("addressStringUser").value;
        calculateDistances(addressSource,addressDestination);

    })
  }
  function codeAddress(address) {
    var geocoder = new google.maps.Geocoder();
    var addressDest;

    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            addressDest = results[0].geometry.location;
            //alert(addressDest);
            //alert(document.getElementById("addressString").value);
            // deprecated
            // var href = 'http://maps.google.com/maps?q='+addressDest.toUrlValue(7)+'&ll='+addressDest.toUrlValue(7)+'&z=12';

            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            }   );
        }

    });
  }

    function calculateDistances(addressSource,addressDestination) {
       console.log(addressDestination);
        console.log(addressSource);
       var service = new google.maps.DistanceMatrixService();
       service.getDistanceMatrix(
         {
           origins: [addressSource],
           destinations: [addressDestination],
           travelMode: google.maps.TravelMode.DRIVING,
           unitSystem: google.maps.UnitSystem.METRIC,
           avoidHighways: false,
           avoidTolls: false
         }, callback);
     }

     function callback(response, status) {
       if (status != google.maps.DistanceMatrixStatus.OK) {
         //alert('Error was: ' + status);
       } else {
         var origins = response.originAddresses;
         var destinations = response.destinationAddresses;
         var outputDiv = document.getElementById('outputDiv');
         outputDiv.innerHTML = '';
         //deleteOverlays();

         for (var i = 0; i < origins.length; i++) {
           var results = response.rows[i].elements;

           //addMarker(origins[i], false);
           for (var j = 0; j < results.length; j++) {
               console.log  (results[j]);
             ///addMarker(destinations[j], true);
               //results[j].duration.text - time to pass distance
               if (results[j].status != 'OK'){
                   document.getElementById('distanceContainer').innerHTML = '';
               }
               outputDiv.innerHTML =  results[j].distance.text ;
           }
         }
       }
     }