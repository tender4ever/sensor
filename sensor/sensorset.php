<?php require_once('Connections/sensor.php'); ?>

<?php session_start();?>

<?php 
	if($_SESSION['name']== null)
	{
		header("Location:login.php");
	}
?>
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

	$editFormAction = $_SERVER['PHP_SELF'];
	
	if (isset($_SERVER['QUERY_STRING'])) 
	{
  		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) 
	{
 		$updateSQL = sprintf("UPDATE sensor SET site=%s WHERE sensor_id=%s",
                       GetSQLValueString($_POST['site'], "text"),
                       GetSQLValueString($_POST['sensor_id'], "int"));

  		mysql_select_db($database_sensor, $sensor);
	
  		$Result1 = mysql_query($updateSQL, $sensor) or die(mysql_error());

  		$updateGoTo = "sensor.php";
	
  		if (isset($_SERVER['QUERY_STRING'])) 
		{
    		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    		$updateGoTo .= $_SERVER['QUERY_STRING'];
  		}
  		header(sprintf("Location: %s", $updateGoTo));
	}

	$colname_Recordset1 = "-1";
	
	if (isset($_GET['sensor_id'])) 
	{
  		$colname_Recordset1 = $_GET['sensor_id'];
	}

	mysql_select_db($database_sensor, $sensor);
	
	$query_Recordset1 = sprintf("SELECT * FROM sensor WHERE sensor_id = %s", 
		GetSQLValueString($colname_Recordset1, "int"));
		
	$Recordset1 = mysql_query($query_Recordset1, $sensor) or die(mysql_error());
	
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改感測器</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" href="css/body.css"/>
</head>

<body>
	<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  	<p>位置 : 
    <input name="site" type="text" id="site" value="<?php echo $row_Recordset1['site']; ?>" />
    <input name="sensor_id" type="hidden" id="sensor_id" value="<?php echo $row_Recordset1['sensor_id']; ?>" />
  	</p>
  	<p>
    <input type="submit" name="yes" id="yes" value="確定" style="font-size: 20px; font-weight: bold; font-family:DFKai-sb;"/>
    <input type="reset" name="no" id="no" value="重設" style="font-size: 20px; font-weight: bold; font-family:DFKai-sb;"/>
  	</p>
  	<input type="hidden" name="MM_update" value="form1" />
	</form>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
