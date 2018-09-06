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
	<script language="javascript">
		function showlocations()
		{
			var ajaxpost = $.post( 'ajax-handler.php?act=getalllocations', { dist: $('#district').val() } );
			  ajaxpost.done(function( data ) {
				$( "#locations" ).html( data );
					<?php 
						if(isset($_POST["show"]))
							echo "$('#location').val('{$_POST['location']}');";
					?>

				});
		}
	</script>
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

		<div class="offset-md-2 col-md-8 offset-md-2">
			<div id='locations'>  </div>	
		</div>

		<div class="offset-md-2 col-md-8 offset-md-2" id="sandbox-container">
				 
				 <br/><label>Select Period</label>
				<div class="input-daterange input-group" id="datepicker">
					
					<input type="text" class="input-lg form-control" id="start" name="start" />
					<span class="input-group-addon">to</span>
					<input type="text" class="input-lg form-control" id="end" name="end" />
				</div>
			
		</div>
		<div class="offset-md-3 col-md-6 offset-md-3 text-center" id="sandbox-container">
				<br/>
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
							$images = $db->query("SELECT * FROM pictures WHERE pic_location = {$_POST['location']} and pic_date between '{$_POST['start']}' and '{$_POST['end']}' ") or die('Query failed');
							$i=1;
							while ($img = $images->fetchArray())
							{
								?>	
									<div class="row">
										<div class="offset-md-2 col-md-8 offset-md-2">
											<div class="thumbnail">
												<p><?php echo "<b>Date: </b>{$img['pic_date']} &nbsp; &nbsp; {$img['pic_time']}";?></p>
												<img class="img-thumbnail" src="<?php echo "uploadfiles/{$img['pic_id']}.webp";?>" alt="Lights" style="width:100%">
												<div class="caption">
												  <p><?php echo "<b>Remarks: </b>{$img['pic_remarks']}";?></p>
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


<script type="text/javascript">


//$("#district").prop('disabled', true);
$( "#district" ).change(function() {
  $("#district").change(showlocations());
});
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
		echo "showlocations();";
		echo "$('#div_district').hide();"; 
		//echo "$('#district').prop('disabled', true);";
	}
	if(isset($_POST["show"]))
	{
		echo "$('#district').val('{$_POST['district']}');";
		echo "showlocations();";
		echo "$('#start').val('{$_POST['start']}');";
		echo "$('#end').val('{$_POST['end']}');";
	}
	
	
?>
</script>
</body>

</html>

