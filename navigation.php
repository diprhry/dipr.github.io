	<nav class="navbar navbar-default">
	  <div class="container">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>                        
		  </button>
		  
		    <a class="navbar-brand" href="#">
				<!-- <img src="images/logo.png" alt="Satat Parchar"> -->
			</a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
		  <ul class="nav navbar-nav">
			<li class="active"><a href="index.php">Home</a></li>
			<li class="dropdown">
			  <a class="dropdown-toggle" data-toggle="dropdown" href="#">Reports <span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li><a href="location_history.php">Location History</a></li>
				<li><a href="summary.php">Summary</a></li>
				<?php
					if ($_SESSION['pg_level'] == 'H')
					{
						echo "<li><a href='state-summary.php'>State Summary</a></li>";
					}
				?>				
			  </ul>
			</li>
			<!-- <li><a href="#">Page 3</a></li>
			<li><a href="#">Page 3</a></li> -->
		  </ul>
		  <ul class="nav navbar-nav navbar-right">
		   <?php if(isset($_SESSION["pg_id"])) { ?>
			<li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION["pg_name"];?></a></li>
			<li><a href="login.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
		   <?php }else {?>
		   <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
		   <?php }?>
		  </ul>
		</div>
	  </div>
	</nav>