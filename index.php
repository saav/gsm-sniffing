<?php
  $path = $_SERVER['DOCUMENT_ROOT'] . "/";
  include($path."lib.php");
  include($path.'apiKey.php');
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
    <?php  include($path."header.php");  ?>
    <div id="map"></div>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script>
var map;
var networkNames = ["Singtel", "StarHub", "M1"];
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -1.3, lng: 103.8},
    zoom: 15,
    mapTypeId: google.maps.MapTypeId.HYBRID
  });

var heatMapData = [];

  var message;
  $.getJSON('/backend.php?towers', function(msg) {
    message = JSON.stringify(msg);
    for (var x = 0; x < msg.length; x++) {
      var dataToSend = {
        "cellTowers": [
          msg[x]
        ]
      };
      $.ajax({
        type: "POST",
        contentType: "application/json",
        url: "https://www.googleapis.com/geolocation/v1/geolocate?key=<?php echo $geolocationApiKey; ?>",
        data: JSON.stringify(dataToSend),
        dataToSend: dataToSend,
        success: function(position) {
          map.setCenter(position.location, 1);
          var marker = new google.maps.Marker({
            position: position.location,
            map: map,
            title: 'LAC:' + this.dataToSend["cellTowers"][0]["locationAreaCode"] +
            ", CellID: " + this.dataToSend["cellTowers"][0]["cellId"]
          });
          var infowindow = new google.maps.InfoWindow({
            content: 'LAC:' + this.dataToSend["cellTowers"][0]["locationAreaCode"] +
            ", CellID: " + this.dataToSend["cellTowers"][0]["cellId"] + ", Network: " +
            networkNames[this.dataToSend["cellTowers"][0]["mobileNetworkCode"] - 1]
          });

          google.maps.event.addListener(marker, 'mouseover', function() {
              infowindow.open(map, this);
          });

          // assuming you also want to hide the infowindow when user mouses-out
          google.maps.event.addListener(marker, 'mouseout', function() {
              infowindow.close();
          });

          // infowindow.open(map, marker);

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

          $.getJSON('/backend.php?ci=' + this.dataToSend["cellTowers"][0]["cellId"] + '&lac='
            + this.dataToSend["cellTowers"][0]["locationAreaCode"], function(result) {

            heatMapData.push({location: new google.maps.LatLng( position.location.lat, position.location.lng ),
              weight: result[result.length - 1].new + result[result.length - 1].repeated });
            var heatmap = new google.maps.visualization.HeatmapLayer({
              data: heatMapData
            });
            heatmap.setOptions({radius: result[result.length - 1].new + result[result.length - 1].repeated});
            heatmap.setMap(map);
          });

        },
        dataType: "json",
        error: function(data) {
          console.log(JSON.stringify(data));
        }
      });
    }

  });

}

    </script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mapsApiKey; ?>&callback=initMap"
        async defer></script> -->
    <!-- <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mapsApiKey;
      ?>&libraries=visualization&sensor=true_or_false&callback=initMap">
    </script> -->
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mapsApiKey;
        ?>&libraries=visualization&callback=initMap">
    </script>
  </body>
</html>
