<?php


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


?>