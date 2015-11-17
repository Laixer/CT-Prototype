<?php
session_start();

$conn_db_host = 'localhost';
$conn_db_user = 'root';
$conn_db_pass = 'YWRtaW4=';
$conn_db_tool_name = 'db_tool';
$conn_db_material_name = 'db_material';

mysql_connect($conn_db_host, $conn_db_user, base64_decode($conn_db_pass)) or die("Could not connect: " . mysql_error());
mysql_select_db($conn_db_tool_name);

# Set timezone, optional
date_default_timezone_set('Europe/Amsterdam');
?>

