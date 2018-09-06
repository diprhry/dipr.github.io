<?php
session_start();


if(!isset($_SESSION["pg_id"])){

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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
		function initGeolocation()
		{
			//document.getElementById('imgReload').src = "images/loading.gif";
			if (navigator && navigator.geolocation)
				navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
			else
				alert('Geolocation is not supported on this device.');
		}
		function errorCallback() {}
		 
		function successCallback(position)
		{
			//document.getElementById('loc_lat').value= position.coords.latitude;
			//document.getElementById('loc_long').value= position.coords.longitude;
			//document.getElementById('imgReload').src = "images/refresh.png";
			//window.location.href = window.location.href + "?lat="+position.coords.latitude+"&long="+position.coords.longitude;
			
			var ajaxpost = $.post( 'ajax-handler.php?act=getlocations', { lat: position.coords.latitude, longti: position.coords.longitude } );
			  ajaxpost.done(function( data ) {
				$( "#locations" ).html( data );
					    $('input[type=radio][name=location]').change(function() {
						btnCapture.style.display = 'inline';
						cameraVideo.style.display = 'inline-block';
				});
			});

			  
			  
		}
		
		function bodyonload()
		{
			 //if(document.getElementById('loc_lat').value.length ==0)
				 initGeolocation();
		}
		function openGoogleMap(quality)
		{
			if(quality==0)
				window.open("https://maps.google.com/maps/api/staticmap?zoom=15&size=512x512&maptype=roadmap&sensor=false&center="+document.getElementById('dsr_location').value+"&markers="+document.getElementById('dsr_location').value);
			else
				window.open("https://maps.google.com/maps?z=12&t=m&q=loc:"+document.getElementById('dsr_location').value);
		}

	</script>
</head>
<body onLoad="javascript:bodyonload()">

	<?php require_once('navigation.php'); ?>

	<div class="container">
	<form method="post" enctype="multipart/form-data">
	 <div class="row">
		  <div class="col-md-12">
			<div id='locations'>  </div>	
		</div>
		<br/>
		
		<!--
			<div class="col-md-12">
			<input type="text" style="display:none;" id="loc_lat" name="loc_lat" value="" />
			<input type="text" style="display:none;" id="loc_long" name="loc_long" value="" />
			</div>
		<div class="col-md-9 offset-md-3" id="remarks_box" style="display:none;">
			<br/>
			<div class="form-group col-md-9 offset-md-3">
				<label for="remarks">Remarks:</label>
				<input type="text"  id="remarks" name="remarks" value="" />
			</div>
		</div>
		-->
		<p>&nbsp;<br/></p>
		<div class="col-md-12">      
			<span class="form-group col-md-4 offset-md-1" id="remarks_box" style="display:none;">
				<label for="remarks" style='display:inline-block;'>Remarks:</label>
				<input type="text"  id="remarks" name="remarks" value="" style='display: inline;' />
			</span>

		<button id="capture-button" class="btn btn-warning" type="button" style="width:150px;font-size:1.1em;display:none;">Take Picture</button>
			<button id="upload-button" class="btn btn-success" type="button" style="width:150px;font-size:1.1em;display:none;">Upload</button>
			<button id="restart-button" class="btn btn-danger" type="button" style="width:150px;font-size:1.1em;display:none;">Restart</button>
		</div>
		<br/>		
		<div class="col-md-9 offset-md-3">      
				<video id="camera-live" class="videostream" autoplay style="width:100%;display:none;"></video>
				<img id="screenshot-img" style="width:100%;">
		</div>	
        </form>
    </div>
    <br/>
    <div id="response-success" style="display:none;text-align:center;valign:middle;">
		<div class="alert alert-success alert-dismissible fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Successfully</strong>  Uploaded!.
		</div>
	</div>
	<div id="response-error" style="display:none;text-align:center;valign:middle;">
		<div class="alert alert-error alert-dismissible fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Error Occoured</strong><br>Please try again!
		</div>
	</div>
	
    <script type="text/javascript">
        const constraints = { video: true };
        const btnCapture = document.querySelector('#capture-button');
        const btnUpload = document.querySelector('#upload-button');
        const btnRestart = document.querySelector('#restart-button');        
        const capturedImage = document.querySelector('#screenshot-img');
        const cameraVideo = document.querySelector('#camera-live');
        const canvas = document.createElement('canvas');
		const txtRemarks = document.querySelector('#remarks_box');
		const divResponseSuccess = document.querySelector('#response-success');
		const divResponseError = document.querySelector('#response-error');
/*
        $('input[type=radio][name=location]').change(function() {
            btnCapture.style.display = 'inline';
            cameraVideo.style.display = 'inline-block';
        });*/
		$( ".district" ).change(function() {
		  alert( "Handler for .change() called." );
		});
        btnCapture.onclick = cameraVideo.onclick = function() {
            canvas.width = cameraVideo.videoWidth;
            canvas.height = cameraVideo.videoHeight;
            canvas.getContext('2d').drawImage(cameraVideo, 0, 0);
            // Other browsers will fall back to image/png
            capturedImage.src = canvas.toDataURL('image/webp');
            cameraVideo.remove();
            btnCapture.style.display = 'none';
            btnUpload.style.display = 'inline';
			btnRestart.style.display = 'inline';			
			txtRemarks.style.display ="inline";
			$("input[name=location]").attr('disabled', true);
        };
        btnUpload.onclick = function() {
            $("#upload-button").text("Working...");
            $.ajax({
                url:'save.php', 
                type:'POST', 
				data:{
					hoardinglocation:$('input[name=location]:checked').val(),
					remarks:$('#remarks').val(),
                    data:capturedImage.src
                },
                success: function(result) {
                    if(result == "OK")
					{
						$("#upload-button").text("Done");
						this.disabled = true;
						btnUpload.style.display = 'none';
						capturedImage.style.display = 'none';
						txtRemarks.style.display ="none";
						divResponseSuccess.style.display = 'block';					
					}
					else
					{
						$("#upload-button").text("Done");
						this.disabled = true;
						btnUpload.style.display = 'none';
						capturedImage.style.display = 'none';
						txtRemarks.style.display ="none";
						divResponseError.style.display = 'block';
						btnRestart.style.display = 'inline';				 						
					}
                    btnRestart.style.display = 'inline';
        		 },
				 error: function(){
                    $("#upload-button").text("Done");
                    this.disabled = true;
                    btnUpload.style.display = 'none';
					capturedImage.style.display = 'none';
					txtRemarks.style.display ="none";
					divResponseError.style.display = 'block';
                    btnRestart.style.display = 'inline';				 
				 }
            });
        };
        btnRestart.onclick = function() {
            location.reload();
        };
        
        function handleSuccess(stream) {
            cameraVideo.srcObject = stream;
        }
        function handleError(e) {
            alert(e);
        }
        navigator.mediaDevices.getUserMedia(constraints).
        then(handleSuccess).catch(handleError);
    </script>
</body>
</html>

