<?php
  $path = $_SERVER['DOCUMENT_ROOT'] . "/";
  include($path."lib.php");
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Footfall in Cell Towers</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
  </head>
  <body>
    <?php  include($path."header.php");  ?>
    <!-- <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div> -->

  </body>
  <script>
  $.getJSON('/backend.php?towers', function(msg) {
    message = JSON.stringify(msg);
    for (var y = 0; y < msg.length; y++) {
      $(function () {
        var a = y;
        $.getJSON('/backend.php?ci=' + msg[a]['cellId'] + '&lac=' + msg[a]['locationAreaCode'], function(result) {
          // alert('ci=' + msg[a]['cellId'] + '&lac=' + msg[a]['locationAreaCode']);
          var series1 = [];
          var series2 = [];
          for (var x = 0; x < result.length; x++) {
            var d = new Date(result[x].time);
            series1.push([Date.UTC(d.getFullYear(),  d.getMonth(),
              d.getDate(), d.getHours(), d.getMinutes()), result[x].new]);
            series2.push([Date.UTC(d.getFullYear(),  d.getMonth(),
              d.getDate(), d.getHours(), d.getMinutes()), result[x].repeated]);
          }

          var $div = $("<div style='min-width: 90%; height: 400px; margin: 0 auto'></div>");



          // $('#container' + a).highcharts({
          $div.highcharts({
              chart: {
                  type: 'areaspline'
              },
              title: {
                  text: 'Number of Users for Cell Tower (LAC: ' + msg[a]['locationAreaCode'] + ', CellID: ' +
                   msg[a]['cellId'] + ')'
              },
              legend: {
                  layout: 'vertical',
                  align: 'left',
                  verticalAlign: 'top',
                  floating: true,
                  borderWidth: 1,
                  backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
              },
              xAxis: {
                  type: 'datetime',
                  // categories: []
                  title: {
                          text: 'Time'
                      },
                  labels: {
                          overflow: 'justify'
                      }
              },
              yAxis: {
                  title: {
                      text: 'Number of Connections'
                  }
              },
              tooltip: {
                  shared: true,
                  valueSuffix: ' units'
              },
              credits: {
                  enabled: false
              },
              plotOptions: {
                  areaspline: {
                      fillOpacity: 0.5
                  }
              },
              series: [{
                  name: 'new',
                  data: series1
              }, {
                  name: 'repeated',
                  data: series2
              }]
          });

          $("body").append($div);

      });
    });
  }
});

  </script>
</html>
