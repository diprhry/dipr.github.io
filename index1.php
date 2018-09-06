<?php
session_start();


if(isset($_SESSION["login"])){
echo "Logged In<br>";
echo "Welcome". $_SESSION["login"] ."<a href='logout.php'>Logout</a>";

}
else{
echo "Please login first";
header("refresh:5;url=login.php");
}
?>