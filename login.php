<?php
 session_start();
 if(isset($_SESSION['pg_id']))
	session_destroy();
 
 $errors="";
 function clean_data($data)
 {
		$data = trim ($data);
		$data = htmlspecialchars($data);
		$data = stripslashes($data);
		return $data;
 } 

 if (isset($_POST['btnSubmit']))
 {
	class MyDB extends SQLite3
	{
		function __construct()
		{
			$this->open('sp.db');
		}
	}
	$db = new MyDB();
	if(!$db)
	  echo $db->lastErrorMsg();

	$sql ="SELECT * from photographers where pg_id='".clean_data($_POST['user_id'])."' and pg_password='".clean_data($_POST['user_password'])."'";
	$result = $db->query($sql);
	$row = $result->fetchArray(SQLITE3_ASSOC);
	if(strlen($row['pg_id'])>0)
	{
		$_SESSION["pg_id"]=$row['pg_id'];
		$_SESSION["pg_name"]=$row['pg_name'];
		$_SESSION["pg_district"]=$row['pg_district'];
		$_SESSION["pg_level"]=$row['pg_level'];
		header('Location: index.php');
	}else
	  $errors ="Invalid Username / Password Entered. Please Try Again.";
	
	$db->close();
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Log in</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <style type="text/css">
	#loginform {
	 
	min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
	min-height: 100vh; /* These two lines are counted as one :-)       */

	padding: 20px;
  display: flex;
  align-items: center;
	 
	}

.modal-content{
			background-color: #8f7631a6;
		}
		.btn-link{
			color:white;
		}
		.modal-heading h2{
			color:#ffffff;
		}

	


  </style>
</head>
<body>
<form class="form-signin" action="login.php" method="post">  
<div class="modal-dialog" id="loginform">
		<div class="modal-content">
			<div class="modal-heading">
				<h2 class="text-center">LOGIN</h2>
			</div>
			<hr />
			<div class="modal-body">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">
							<span class="glyphicon glyphicon-user"></span>
							</span>
							<input type="text" class="form-control" placeholder="User Name"  required name="user_id"/>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">
							<span class="glyphicon glyphicon-lock"></span>
							</span>
							<input type="password" class="form-control" placeholder="Password"  required name="user_password"/>

						</div>

					</div>
					<?php if(strlen($errors)>0){?>
					<div id="response" style="display:block;text-align:center;valign:bottom;background-color:#faa;font-weight:bold;" >
						<div class="alert alert-error alert-dismissible fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<?php echo $errors;?>
						</div>
					</div>
					<?php }?>

					<div class="form-group text-center">
						<button type="submit" class="btn btn-success btn-lg" type="submit" name="btnSubmit">Login</button>
						<!-- <a href="#" class="btn btn-link">forget Password</a> -->
					</div>
			</div>			
		</div>
	</div>
</form>


  
</body>
</html>