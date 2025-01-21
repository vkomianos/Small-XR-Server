<?php



/* Addition for localhost served apps -> */
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: PUT, GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept");
/* <- Addition for localhost served apps */


require_once "./SleekDB-master/src/Store.php";

//echo "OK";

$storePositions = new \SleekDB\Store("positions", "DB");


//$position = ["userID" => "123", "position" => "x y z"];
$position = ["userID" => $_POST['userID'], "position" => $_POST['position']];


$result = $storePositions->insert($position);

?>