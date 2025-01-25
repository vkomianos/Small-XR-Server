<?php

error_reporting(E_ERROR | E_PARSE);

session_start();
//print_r($_SESSION);



/* Addition for localhost served apps -> */
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: PUT, GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept");
/* <- Addition for localhost served apps */


require_once "./SleekDB-master/src/Store.php";

if (!isset($_SESSION['user-id']))
{
	echo "No session.<br/>";
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

$_POST['command'] = "login";
if (isset($_POST['command']) || isset($_POST["userID"]))
{
	//echo "Good";
	
	$_POST['command'] = "login";
	$_POST['username'] = 'vkom';
	$_POST['password'] = '1234';
	
	if ($_POST['command'] == "login" && isset($_POST['username']) && isset($_POST['password']))
	{
		$storeUsers = new \SleekDB\Store("users", "DB");
		
		$userNameExists = $storeUsers->createQueryBuilder()
		->where([ "username", "=", $_POST['username'] ])
		->getQuery()->exists();
		
		if ($userNameExists)
		{
			// check password to login
			$user = $storeUsers->createQueryBuilder()
			->where(
			[
				["username", "=", $_POST['username']],
				["password", "=", $_POST['password']]
			])->getQuery()->exists();
			
			//echo $user;
			echo "Check login ";
			echo $user == "1" ? "true" : "false";
			echo ".<br/>";
			
			if ($user == 1)
			{
				$_SESSION['username'] = $_POST['username'];
				$_SESSION['session_token'] = bin2hex(random_bytes(32));
				
				// save session_token in DB and check on every request
				
				print_r($_SESSION);
			}
			
		}
		else
		{
			// create user and proceeed
			$createUser = ["username" => $_POST['username'], "password" => $_POST['password']];
			$result = $storeUsers->insert($createUser);
			
			echo "Create user ".print_r($result);
		}
	}
	
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
	InsertUpdatePositions($userID, $position, $rotation, $storePositions);

if ($command == "retrieve")
	retrieveUsersPositions($storePositions);


if ($command == "")
{
	echo "Nothing to see here...";
}


function InsertUpdatePositions($userID, $position, $rotation, $storePositions)
{

	$result = $storePositions->search(["userID"], $userID, ["_id" => "DESC"]);
	//print_r($result); 
	//echo "Found ".count($result)." records";

/*
	foreach ($result as $key => $value) 
	{
		//echo $key. " - ";
	}
*/
	//echo "Position of ".$userID." is found: ".$result[0]['position'];

	
	if ($result[0]['position'] != null) // can also use function exists() - check sleekDB query docs
	{
		//echo "Update position";
		// Update
		$result = $storePositions->createQueryBuilder()
			->where(["userID", "=", $userID])
			->orderBy(["_id" => "DESC"]) 
			->getQuery()
			//->update(["position" => $position]);
			->update(["position" => $position, "rotation" => $rotation]);
	}
	else
	{
		// Insert
		//echo "Insert user and position";
		$userAndPosition = ["userID" => $userID, "position" => $position, "rotation" => $rotation];
		$result = $storePositions->insert($userAndPosition);
	}
	//$position = ["userID" => "123", "position" => "x y z"];
	//$position = ["userID" => $_POST['userID'], "position" => $_POST['position']];
	//$result = $storePositions->insert($position);
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