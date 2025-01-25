<?php
function login()
{
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
				/*
				echo "Check login ";
				echo $user == "1" ? "true" : "false";
				echo ".<br/>";
				*/
				if ($user == 1)
				{
					$_SESSION['username'] = $_POST['username'];
					$_SESSION['session_token'] = bin2hex(random_bytes(32));
					
					// save session_token in DB and check on every request
					
					//print_r($_SESSION);
				}
				
			}
			else
			{
				// create user and proceeed
				$createUser = ["username" => $_POST['username'], "password" => $_POST['password']];
				$result = $storeUsers->insert($createUser);
				
				//echo "Create user ".print_r($result);
			}
		}
}
?>