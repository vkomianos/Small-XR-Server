<?php

//error_reporting(E_ERROR | E_PARSE);

/* Addition for localhost served apps -> */
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: PUT, GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept");
/* <- Addition for localhost served apps */


require_once "./SleekDB-master/src/Store.php";

//echo "OK";
$userID = "";
$position = "";
$command = "update";

$testUID = "123";

if (isset($_POST['command']) && isset($_POST["userID"]))
{
	if ($_POST['command'] == "update")
	{
		$userID = $_POST["userID"];
		$position = $_POST["position"];
		$command = $_POST['command'];
	}
}
else
{
	$userID = $testUID;
	$position = "x y z";
}
$storePositions = new \SleekDB\Store("positions", "DB");


if ($command == "update")
	InsertUpdatePositions($userID, $position, $storePositions);


function InsertUpdatePositions($userID, $position, $storePositions)
{

	$result = $storePositions->search(["userID"], $userID, ["_id" => "DESC"]);
	//print_r($result); 
	//echo "Found ".count($result)." records";

	foreach ($result as $key => $value) 
	{
		//echo $key. " - ";
	}
	//echo "Position of ".$userID." is found: ".$result[0]['position'];

	$position = ["userID" => $userID, "position" => $position];
	if ($result[0]['position'] != null)
	{
		// Update
		$result = $storePositions->createQueryBuilder()
			->where(["userID", "=", $userID])
			->orderBy(["_id" => "DESC"])
			->getQuery()
			->update(["position" => $position]);
	}
	else
	{
		// Insert
		$result = $storePositions->insert($position);
	}
	//$position = ["userID" => "123", "position" => "x y z"];
	//$position = ["userID" => $_POST['userID'], "position" => $_POST['position']];
	//$result = $storePositions->insert($position);
}

?>