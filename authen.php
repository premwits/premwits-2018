<?php
require_once "dbhelper.php";
$user = $_POST['username'];
$pass = $_POST['password'];
if(strlen($user) < 4){
	
}else if(strlen($pass) < 4){

}
$user = md5($user);
$pass = md5($pass);
$conn = connect_db();
if(!$conn){
	echo "<p> Connection Error </p>";
	close_db($conn);
	die();
}
$res = $conn->query("SELECT * FROM user_info WHERE user = '$user' AND pass = '$pass'");
if(!$res){
	echo "<p> Query Error </p>";
	close_db($conn);
	die();
}
$row = $res->fetch_row();
if(!$row){
	echo "<p> Wrong Username or Password </p>";
	die();
}else{
	echo "<p> Login Successful </p>";
	$_SESSION["user_id"] = $row[0];
	header("Location: app.php");
}
?>