<?php
// Start the session
session_start();
$hostname_conn = "localhost";
$database_conn = "trucks";
$username_conn = "root";
$password_conn = "root1234";
$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR);

mysql_select_db($database_conn, $conn);