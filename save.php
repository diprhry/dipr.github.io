<?php
	session_start();
	date_default_timezone_set('Asia/Kolkata');
	function clean_data($data)
	{
			$data = trim ($data);
			$data = htmlspecialchars($data);
			$data = stripslashes($data);
			return $data;
	}
	
	if(!isset($_SESSION['pg_id']))
	{
		header("location:login.php");
		die("Access denied!");
	}
		
	
	$db = new SQLite3('sp.db');
	if (!$db) die ("Cannot Open Database");
	
	$date = date('Y-m-d');
	$time = date('H:i');
	$pg = $_SESSION['pg_id'];
	$location = $_POST['hoardinglocation'];;
	$remarks = $_POST['remarks'];;
	
	$query="insert into pictures values(null,{$location},{$pg},'{$date}','{$time}','{$remarks}')";
	//echo $query;
	$result = $db->query($query) or die('Query failed');
	$thisrowid = $db->lastInsertRowid();
	
	$img = $_POST['data'];
	$img = str_replace('data:image/webp;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);

	$fileUrl="uploadfiles/{$thisrowid}.webp";
	$success = file_put_contents($fileUrl, $data);
					
	if($thisrowid>0 and $success){ echo "OK"; } else { echo 'not ok';}
		


?>