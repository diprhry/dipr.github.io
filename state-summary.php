<?php
	session_start();
	if(!isset($_SESSION["pg_id"]) or $_SESSION['pg_level']!='H')
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
		td{
			padding: 0px;
			text-align: center;
		}
		tfoot > td{
			text-weight: bold;
		}
	</style>
</head>
<body>

	<?php require_once('navigation.php'); ?>

	<div class="container">
	<form method="post">
	 <div class="row">
		<div class="offset-md-2 col-md-8 offset-md-2" id="sandbox-container">
				 
				 <br/><label>Select Period</label>
				<div class="input-daterange input-group" id="datepicker">
					
					<input type="text" class="input-lg form-control" id="start"  name="start" />
					<span class="input-group-addon">to</span>
					<input type="text" class="input-lg form-control" id="end"  name="end" />
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
							
							
							$state_summary = $db->query("SELECT dist_id,dist_name, count(distinct loc_id) as total_locations, count(distinct pic_location) as locations_captured, count(pic_location) as photos_clicked FROM districts left join locations on dist_id=loc_district left join (select * from pictures WHERE pic_date between '{$_POST['start']}' and '{$_POST['end']}') pictures on pic_location=loc_id group by dist_id order by dist_name");
 							
							if(isset($state_summary))
							{
								echo "<table class='table table-bordered table-condensed table-responsive'><thead><tr><th>District</th><th>Total Locs</th><th>Locs Captured</th><th>Photos</th></tr></thead><tbody>";
								$i=1;
								$totals = $captured = $photos = 0;
								while ($row = $state_summary->fetchArray(SQLITE3_ASSOC))
								{
									echo "<tr><td style='text-align:left;'>{$row['dist_name']}</td> <td>{$row['total_locations']}</td> <td>{$row['locations_captured']}</td> <td>{$row['photos_clicked']}</td></tr>";
									$i++;
									$totals += $row['total_locations']	;
									$captured += $row['locations_captured'];
									$photos += $row['photos_clicked']; 
								}	
								echo "</tbody><tfoot><td><strong>Total</strong></td><td><strong>{$totals}</strong></td><td><strong>{$captured}</strong></td><td><strong>{$photos}</strong></td></tfoot></table><br/>";
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
		echo "$('#district').prop('disabled', true);";
	}
	if(isset($_POST["show"]))
	{
		echo "$('#start').val('{$_POST['start']}');";
		echo "$('#end').val('{$_POST['end']}');";
	}	
?>

</script>

</html>

