<?php

error_reporting(E_ERROR | E_PARSE);

session_start();
//print_r($_SESSION);

//require_once('config.php');
require_once('login.php');

/* Addition for localhost served apps -> */
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: PUT, GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept");
/* <- Addition for localhost served apps */


require_once "./SleekDB-master/src/Store.php";

if (!isset($_SESSION['user-id']))
{
	//echo "No session.<br/>";
}


//echo "OK";
$userID = "";
$position = "";
$rotation = "";
$command = "";
//$command = "update";
//$command = "retrieve";
//$testUID = "1234";

//print_r($_POST);

//$_POST['command'] = "login";
if (isset($_POST['command']) || isset($_POST["userID"]))
{
	//echo "Good";
	
	/*
	$_POST['command'] = "login";
	$_POST['username'] = 'vkom';
	$_POST['password'] = '1234';
	*/
	
	login();
	
	if ($_POST['command'] == "update")
	{
		$command = $_POST['command'];
		$userID = $_POST["userID"];
		$position = $_POST["position"];
		$rotation = $_POST["rotation"];
	}
	
	if ($_POST['command'] == "retrieve")
	{
		$command = $_POST['command'];
	}
}
else
{
	/*
	$userID = $testUID;
	$position = "x y z";
	$command = "update";
	*/
}
$storePositions = new \SleekDB\Store("positions", "DB");

if ($command == "update")
{
	require_once('playerUpdates.php');
	InsertUpdatePositions($userID, $position, $rotation, $storePositions);
}
if ($command == "retrieve")
	retrieveUsersPositions($storePositions);


if ($command == "")
{
	echo "Nothing to see here...";
}



function retrieveUsersPositions($storePositions)
{
	$users = $storePositions->findAll();
	//print_r($users);
	
	echo json_encode($users);
	
	/*
	foreach ($users as $key => $value) 
	{
		//echo $key. " - ". $value;
		
		//print_r( $users[$key]);
		//echo $users[$key]["userID"]."-".$users[$key]["position"];
	}
	*/
	
}

?>