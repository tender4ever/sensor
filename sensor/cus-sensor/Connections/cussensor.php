<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_cussensor = "localhost";
$database_cussensor = "sensor";
$username_cussensor = "root";
$password_cussensor = "1234";
$cussensor = mysql_pconnect($hostname_cussensor, $username_cussensor, $password_cussensor) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMEs utf8");
?>