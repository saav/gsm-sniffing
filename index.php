<?php
  include 'apiKey.php';
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Finding A Cell Tower</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script>

var map;
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -34.397, lng: 150.644},
    zoom: 15,
    mapTypeId: google.maps.MapTypeId.HYBRID
  });

  var message;
  $.getJSON('data.json', function(msg) {
    message = JSON.stringify(msg);
    alert(message);
    $.ajax({
      type: "POST",
      contentType: "application/json",
      url: "https://www.googleapis.com/geolocation/v1/geolocate?key=<?php echo $geolocationApiKey; ?>",
      data: message,
      success: function(position) {
        alert(JSON.stringify(position));
        $("#cell").html(JSON.stringify(position));
        map.setCenter(position.location, 1);
        var marker = new google.maps.Marker({
          position: position.location,
          map: map,
          title: 'Hello World!'
        });
        var infowindow = new google.maps.InfoWindow({
          content: "This is the nearest cell tower"
        });
        infowindow.open(map, marker);

        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(youPosition) {
            var pos = {
              lat: youPosition.coords.latitude,
              lng: youPosition.coords.longitude
            };

            var youMarker = new google.maps.Marker({
              position: pos,
              map: map,
              title: 'You are here!'
            });
          });
        }
      },
      dataType: "json",
      error: function(data) {
        alert(JSON.stringify(data));
      }
    });

  });

}

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mapsApiKey; ?>&callback=initMap"
        async defer></script>
  </body>
</html>
