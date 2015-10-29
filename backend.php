<?php
  $path = $_SERVER['DOCUMENT_ROOT'] . "/";

  include($path.'config.php');

  mysqli_select_db($conn, 'gsm');

  if( $_GET["towers"] == 1) {
    $sql = "select * from cell_tower";
    $result = $conn->query($sql);
    $arrayOfTowers = array();
    while ($row = mysqli_fetch_row($result)) {
        // echo "{$row[0]} {$row[1]} {$row[2]} {$row[3]} {$row[4]}";
        $arr = array ('cellId'=>$row[4],'locationAreaCode'=>$row[3],'mobileCountryCode'=>$row[2],'mobileNetworkCode'=>$row[1]);
        array_push($arrayOfTowers, $arr);
    }
    echo json_encode($arrayOfTowers, JSON_NUMERIC_CHECK);
  }

 ?>
