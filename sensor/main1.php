<?php require_once('Connections/sensor.php'); ?>

<?php ini_set("max_execution_time", "0"); ?>

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
	if (isset($_GET['sensor_id'])) 
	{
  		$colname_Recordset1 = $_GET['sensor_id'];
	}
	mysql_select_db($database_sensor, $sensor);
	
	$query_Recordset1 = sprintf("SELECT * FROM sensing WHERE sensor_id = %s ORDER BY sensing_id DESC", 
		GetSQLValueString($colname_Recordset1, "int"));
		
	$Recordset1 = mysql_query($query_Recordset1, $sensor) or die(mysql_error());
	
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);

	$colname_Recordset2 = "-1";
	if (isset($_GET['sensor_id'])) 
	{
  		$colname_Recordset2 = $_GET['sensor_id'];
	}
	mysql_select_db($database_sensor, $sensor);
	
	$query_Recordset2 = sprintf("SELECT * FROM sensor WHERE sensor_id = %s", 
		GetSQLValueString($colname_Recordset2, "int"));
	$Recordset2 = mysql_query($query_Recordset2, $sensor) or die(mysql_error());
	
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	
	$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" href="css/body.css"/>
</head>

<body style= "font-size:22px">
	<?php if ($totalRows_Recordset1 > 0) 
	{ // Show if recordset not empty 
	?>
  		<p align="left">日期:<?php echo $row_Recordset1['date'];?> <?php echo $row_Recordset1['time'];?></p>
  		<p align="left">溫度:<?php echo $row_Recordset1['temp']; ?></p>
  		<p align="left">濕度:<?php echo $row_Recordset1['humidity']; ?></p>
  	<?php 
	} // Show if recordset not empty 
	?>
	<?php 
	//if ($row_Recordset2['check']!=0)

	if ($row_Recordset2['check']!=0 and $row_Recordset1['temp']>$row_Recordset2['temp2'] 
		or $row_Recordset2['check']!=0 and $row_Recordset1['temp']<$row_Recordset2['temp1'] 
		or $row_Recordset2['check']!=0 and $row_Recordset1['humidity']>$row_Recordset2['humidity2'] 
		or $row_Recordset2['check']!=0 and $row_Recordset1['humidity']<$row_Recordset2['humidity1'])
	{
	?>		
    	<audio src="ring.wav" autoplay="ture" hidden="true" loop="loop"</audio>
        		
	<?php 
	} 
	else header ("Refresh: 5;main1.php?sensor_id=$colname_Recordset1")
	?>


</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
<?php //header("Refresh: 5; main1.php?sensor_id=$colname_Recordset1");?>
