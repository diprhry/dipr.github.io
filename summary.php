<?php
	session_start();
	if(!isset($_SESSION["pg_id"]))
	{
		header("location:login.php");
		die("Access denied! Please Re-login.");
	}
?>


<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link id="bsdp-css" href="css/bootstrap-datepicker3.min.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<!-- <script src="js/bootstrap.min.js"></script> -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.min.js"></script>
	<title>Satat Parchar</title>
	<link rel="manifest" href="manifest.json">
	<link rel="stylesheet" href="https://fonts.google.com/specimen/Open+Sans?selection.family=Open+Sans|Roboto">
	<link rel="stylesheet" href="style.css">
	
	<style type="text/css">
		body{ 
			font-size:1.5em;
			line-height:1.5em;
		}
	</style>
</head>
<body>

	<?php require_once('navigation.php'); ?>

	<div class="container">
	<form method="post">
	 <div class="row">
		<div id='div_district' class="offset-md-2 col-md-8 offset-md-2">
			<label for='district' >Select District </label>
			<select id='district' name='district' class='input-lg form-control'>
				<?php
					$db = new SQLite3('sp.db');
					if (!$db) die ("Cannot Open Database");
					if ($_SESSION['pg_level'] == 'D')
						$districts = $db->query("SELECT * FROM districts WHERE dist_id = '{$_SESSION['pg_district']}'") or die('Query failed');
					else
						$districts = $db->query("SELECT * FROM districts") or die('Query failed');						
					$i=1;
					while ($dist = $districts->fetchArray())
					{
							echo "<option value='{$dist['dist_id']}'>{$dist['dist_name']}</option>";
							$i++;			  
					}
				?>			
			</select>
		</div>		
		<div class="offset-md-2 col-md-8 offset-md-2" id="sandbox-container">
				 
				 <br/><label>Select Period</label>
				<div class="input-daterange input-group" id="datepicker">
					
					<input type="text" class="input-lg form-control" id="start" name="start" />
					<span class="input-group-addon">to</span>
					<input type="text" class="input-lg form-control" id="end" name="end" />
				</div>
			
		</div>
		<br/>
		<div class="offset-md-3 col-md-6 offset-md-3 text-center" id="sandbox-container">
				 
				 <button name="show" class="btn btn-success btn-md" type="submit" style="width:150px;font-size:1.1em;"> &nbsp; Show Report &nbsp; </button>
			<hr>
		</div>
		</form>
		<div class="col-md-12 ">
					<?php
						if(isset($_POST['show']))
						{
							
								
							$db = new SQLite3('sp.db');
							if (!$db) die ("Cannot Open Database");
							
							//echo "SELECT * FROM pictures WHERE pic_location = {$_POST['location']} and pic_date between '{$_POST['start']}' and '{$_POST['end']}' ";
							$locations = $db->query("SELECT count(loc_id) as loc_count FROM locations WHERE loc_district = '{$_POST['district']}'") or die('Query failed');
							$locations = $locations -> fetchArray();
							$locations = $locations['loc_count'];

							$unique_captured = $db->query("SELECT count(distinct pic_location) as unique_count FROM pictures left join locations on pic_location=loc_id WHERE loc_district = {$_POST['district']}  and pic_date between '{$_POST['start']}' and '{$_POST['end']}'") or die('Query failed');
							$unique_captured = $unique_captured -> fetchArray();
							$unique_captured = $unique_captured['unique_count'];
							
							$images_captured = $db->query("SELECT count(pic_id) as pic_count FROM pictures left join locations on pic_location=loc_id WHERE loc_district = {$_POST['district']}  and pic_date between '{$_POST['start']}' and '{$_POST['end']}' ") or die('Query failed');
							$images_captured = $images_captured -> fetchArray();
							$images_captured = $images_captured['pic_count'];
							
				if(isset($locations)): ?>
				<table class="table table-bordered">
					<thead>
					  <tr>
						<th>Total Locations</th>
						<td><?php echo $locations; ?></td>						
					  </tr>
					</thead>
					<tbody>
					  <tr>
					  <th>Locations Captured</th>
						<td><?php echo $unique_captured; ?></td>
					  </tr>
					  <tr>
					  <th>Images Captured</th>
						<td><?php echo $images_captured; ?></td>
					  </tr>
					  
					</tbody>
				  </table>		
				
				<?php endif;

					/////////// Show Table Location wise captured images ////////////////////////////////////
						$sql ="SELECT loc_id,loc_name, count(pic_id) as count FROM locations left join pictures on loc_id=pic_location WHERE loc_district = {$_POST['district']} and pic_date between '{$_POST['start']}' and '{$_POST['end']}' group by loc_id order by loc_name"; 
						//echo $sql;
						
						$result= $db->query($sql);
						//print_r($result);
						
						$i=1;							
								echo "<table class='table table-bordered'><thead><tr><th>Sr</th><th>Location</th><th>Captures</th></tr></thead><tbody>";
								while ($row = $result->fetchArray(SQLITE3_ASSOC))
								{
									echo "<tr><td>$i</td><td>{$row['loc_name']}</td>
										 <td>  <a href='#{$row['loc_id']}' class='btn btn-info' role='button'>{$row['count']}</a> </td>
										 </tr>";									
									$i++;
								}	
								echo "</tbody></table><br/>";
									




					//////////////////////////////////////////////////////////////////////////////////////////

				
							
					$sql ="SELECT pic_id,pic_date,pic_time,pic_remarks,loc_id, loc_name FROM pictures left join locations on pic_location=loc_id WHERE loc_district = {$_POST['district']}  and pic_date between '{$_POST['start']}' and '{$_POST['end']}' order by loc_name,pic_date,pic_time"; 
					//echo $sql;
					$result= $db->query($sql);
					//print_r($result);
					
					$i=1;
					$current_loc = 0;
						
							
							while ($row = $result->fetchArray(SQLITE3_ASSOC))
							{
								if($row['loc_id'] != $current_loc)
								{
									echo "<a name='{$row['loc_id']}'></a>";
									$current_loc = $row['loc_id'];
								}
								
								
								
								?>
									
									
									
									
									<div class="row">
										<div class="offset-md-2 col-md-8 offset-md-2">
											<div class="thumbnail">
												<p><span class='badge'><?php echo $i;?></span>
												
												<span><?php echo "{$row['loc_name']}";?></span> <br>
												 &nbsp; &nbsp; &nbsp; <span class='glyphicon glyphicon-time'> <?php echo "{$row['pic_date']},{$row['pic_time']}";?></span>
												</p>
												<img class="img-thumbnail" src="<?php echo "uploadfiles/{$row['pic_id']}.webp";?>" alt="Lights" style="width:100%">
												<div class="caption">
												  <p><?php echo "<span class='label label-success'>Remarks :</span> {$row['pic_remarks']}";?></p>
												</div>
											 
											</div>
										</div>
									</div>
								<?php
								$i++;			  
							
							}

						}
					?>		

		</div>
		
     </div>
    </div> <!-- end .container -->
	<br/>


</body>
<script type="text/javascript">
$('#sandbox-container .input-daterange').datepicker({
    format: "yyyy-mm-dd",
    todayBtn: true,
    clearBtn: true,
    calendarWeeks: true,
    autoclose: true,
    todayHighlight: true,
    beforeShowYear: function(date){
                  if (date.getFullYear() == 2007) {
                    return false;
                  }
                },
    toggleActive: true
});
<?php
	if ($_SESSION['pg_level'] == 'D')
	{
		echo "$('#div_district').hide();"; 
		//echo "$('#district').prop('disabled', true);";
	}
	if(isset($_POST["show"]))
	{
		echo "$('#district').val('{$_POST['district']}');";
		echo "$('#start').val('{$_POST['start']}');";
		echo "$('#end').val('{$_POST['end']}');";
	}

?>

</script>

</html>

