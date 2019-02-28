<?php
$conn = mysqli_connect('localhost','root','');
$database = mysqli_select_db($conn, 'amsw');				

if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}
?>