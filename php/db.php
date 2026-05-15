<?php
// InfinityFree online details - Screenshot nundi teesukunna details
$hostname = "sql111.infinityfree.com"; 
$username = "if0_41375345";
$password = "brahmani987";
$database = "if0_41375345_InfinityCart";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>