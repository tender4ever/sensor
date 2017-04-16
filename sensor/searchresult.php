<?php require_once('Connections/sensor.php'); ?>
<?php
	if (!function_exists("GetSQLValueString")) 
	{
		function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
		{
  			if (PHP_VERSION < 6) 
			{
    			$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  			}

  			$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  			switch ($theType) 
			{
    			case "text":
      				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      			break;    
    			case "long":
    			case "int":
      				$theValue = ($theValue != "") ? intval($theValue) : "NULL";
      			break;
    			case "double":
      				$theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      			break;
    			case "date":
      				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      			break;
    			case "defined":
      				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      			break;
  			}
  			return $theValue;
		}
	}

	$colname_Recordset1 = "-1";
	
	if (isset($_POST['date'])) 
	{
  		$colname_Recordset1 = $_POST['date'];
	}
	
	$colname_Recordset2 = "-1";
	if (isset($_POST['time']))
	{
		$colname_Recordset2 = $_POST['time'];
	
	}
	
	mysql_select_db($database_sensor, $sensor);
	
	$query_Recordset1 = sprintf("SELECT * FROM sensing WHERE time LIKE '$colname_Recordset2:__' AND date='$colname_Recordset1'"  , 
		GetSQLValueString($colname_Recordset1, "date"),GetSQLValueString($colname_Recordset2, "time"));
	
	$Recordset1 = mysql_query($query_Recordset1, $sensor) or die(mysql_error());
	
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" href="css/body.css"/>
<title>歷史紀錄</title>
</head>

<body>

 	<fieldset style="width:310px; height:85px; margin:auto; ">
    
    <legend>
    
    	<a href="main.php"><img src="app-Logo.png" width="35" height="30" /></a>
        單位 : <a href="nameset.php"><?php echo $row_Recordset1['name']; ?></a>
        
    </legend>
    
  	<p><a href="sensor.php">感測器</a> <a href="search.php">歷史查詢</a></p>
    
    </fieldset>
    
    <hr />
    
	<p>日期 <?php echo $row_Recordset1['date']; ?>
	</p>
	<?php 
		do 
		{ 
	?>
  		<p style="font-size:18px">
        時間:<?php echo $row_Recordset1['time']; ?> 
        溫度:<?php echo $row_Recordset1['temp']; ?>
        濕度:<?php echo $row_Recordset1['humidity']; ?>
        </p>
  
  <?php 
  		} 
		while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); 
	?>

</body>
</html>
<?php
	mysql_free_result($Recordset1);
?>
