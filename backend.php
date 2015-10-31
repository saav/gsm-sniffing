<?php
  $path = $_SERVER['DOCUMENT_ROOT'] . "/";

  include($path.'config.php');

  mysqli_select_db($conn, 'gsm');

  if( isset($_GET["towers"])) {
    $sql = "select * from cell_tower";
    $result = $conn->query($sql);
    $arrayOfTowers = array();
    while ($row = mysqli_fetch_row($result)) {
        // echo "{$row[0]} {$row[1]} {$row[2]} {$row[3]} {$row[4]}";
        $arr = array ('cellId'=>$row[3],'locationAreaCode'=>$row[2],'mobileCountryCode'=>$row[1],'mobileNetworkCode'=>$row[0]);
        array_push($arrayOfTowers, $arr);
    }
    echo json_encode($arrayOfTowers, JSON_NUMERIC_CHECK);
  }
  // backend.php?lac='somenumber'&cellId='somenumber' should return an array[]
  if( isset($_GET["ci"]) && isset($_GET["lac"])) {
    $sql = "select * from cell_connection where lac=" . $_GET["lac"] . " AND ci=" . $_GET["ci"];
    $result = $conn->query($sql);
    $arrayOfConnections = array();
    while ($row = mysqli_fetch_row($result)) {
        $arr = array ('time'=>$row[2],'new'=>$row[3],'repeated'=>$row[4]);
        array_push($arrayOfConnections, $arr);
    }
    echo json_encode($arrayOfConnections, JSON_NUMERIC_CHECK);
  }

 ?>
