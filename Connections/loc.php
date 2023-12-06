<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_loc = "localhost";
$database_loc = "trucks";
$username_loc = "root";
$password_loc = "root1234";
$loc = mysql_pconnect($hostname_loc, $username_loc, $password_loc) or trigger_error(mysql_error(),E_USER_ERROR); 
?>