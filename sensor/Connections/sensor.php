<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_sensor = "localhost";
$database_sensor = "sensor";
$username_sensor = "root";
$password_sensor = "1234";
$sensor = mysql_pconnect($hostname_sensor, $username_sensor, $password_sensor) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMEs utf8");
?>