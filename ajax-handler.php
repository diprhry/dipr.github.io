<?php
session_start();
if($_GET['act']=='getlocations')
{
	$db = new SQLite3('sp.db');
	if (!$db) die ("Cannot Open Database");
	
	/* fixed for presentation to dg					
	if ($_SESSION['pg_level'] == 'D')
		$nearlocs = $db->query("SELECT * FROM locations WHERE loc_lat LIKE '30.7386%' AND loc_long LIKE '76.7853%'") or die('Query failed');
	else
		$nearlocs = $db->query("SELECT * FROM locations WHERE loc_lat LIKE '30.7386%' AND loc_long LIKE '76.7853%'") or die('Query failed');
	*/
	// Get 4 digit after dot in latitude & longitude : Prototype substr($str,0,strpos($str,'.')+5)
	
	$latitude  = substr($_POST['lat'],0,strpos($_POST['lat'],'.') + 4);
	$longitude = substr($_POST['longti'],0,strpos($_POST['longti'],'.') + 4);
	
	if ($_SESSION['pg_level'] == 'D')
		$nearlocs = $db->query("SELECT * FROM locations WHERE loc_district = '{$_SESSION['pg_district']}' AND loc_lat LIKE '{$latitude}%' AND loc_long LIKE '{$longitude}%'") or die('Query failed');
	else
		$nearlocs = $db->query("SELECT * FROM locations WHERE loc_lat LIKE '{$latitude}%' AND loc_long LIKE '{$longitude}%'") or die('Query failed');

	$i=1;
	while ($loc = $nearlocs->fetchArray())
	{
		
		   echo '<div class="radio" style="margin-top:10px;">
				<input id="radio-'.$i.'" name="location" type="radio" value="'.$loc['loc_id'] .'"></input><label for="radio-'.$i.'" class="radio-label">'. $loc['loc_name'].'</label>
				</div>';
				$i++;

	}
	
}
if($_GET['act']=='getalllocations')
{
	$db = new SQLite3('sp.db');
	if (!$db) die ("Cannot Open Database");
	
	if ($_SESSION['pg_level'] == 'D')
		$alllocs = $db->query("SELECT * FROM locations WHERE loc_district = '{$_SESSION['pg_district']}'") or die('Query failed');
	else
		$alllocs = $db->query("SELECT * FROM locations WHERE loc_district = '{$_POST['dist']}'") or die('Query failed');

	$i=1;
   echo "<br/><br/><select id='location' name='location'  class='input-lg form-control'>";
	while ($loc = $alllocs->fetchArray())
	{
		echo "<option value='{$loc['loc_id']}'>{$loc['loc_name']}</option>";
		$i++;
	}
	echo "</select>";
	
}
?>