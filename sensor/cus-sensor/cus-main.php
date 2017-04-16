<?php ini_set("max_execution_time", "0"); ?>

<?php require_once('Connections/cussensor.php'); ?>

<?php header("Refresh: 5; cus-main.php?sensor_id=$colname_Recordset2");?>

<?php session_start();?>

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

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
	{
  		$insertSQL = sprintf("INSERT INTO sensing (sensor_id, customer_id, temp, humidity, `date`,time) VALUES (%s, %s, %s, %s, %s,%s)",
                       GetSQLValueString($_POST['sensor_id'], "int"),
                       GetSQLValueString($_POST['customer_id'], "int"),
                       GetSQLValueString($_POST['temp'], "text"),
                       GetSQLValueString($_POST['humidity'], "text"),
                       GetSQLValueString($_POST['date'], "text"),
					   GetSQLValueString($_POST['time'], "text"));

  		mysql_select_db($database_cussensor, $cussensor);
		
  		$Result1 = mysql_query($insertSQL, $cussensor) or die(mysql_error());
	}

	$colname_Recordset1 = "-1";
	
	if (isset($_SESSION['customer_id'])) 
	{
  		$colname_Recordset1 = $_SESSION['customer_id'];
	}
	mysql_select_db($database_cussensor, $cussensor);
	
	$query_Recordset1 = sprintf("SELECT * FROM customer WHERE customer_id = %s", 
		GetSQLValueString($colname_Recordset1, "int"));
		
	$Recordset1 = mysql_query($query_Recordset1, $cussensor) or die(mysql_error());
	
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);

	$totalRows_Recordset1 = mysql_num_rows($Recordset1);


	$colname_Recordset2 = "-1";
	if (isset($_POST['sensor_id'])) 
	{
  		$colname_Recordset2 = $_POST['sensor_id'];
	}
	mysql_select_db($database_cussensor, $cussensor);
	
	$query_Recordset2 = sprintf("SELECT * FROM sensor WHERE sensor_id = %s", 
		GetSQLValueString($colname_Recordset2, "int"));
		
	$Recordset2 = mysql_query($query_Recordset2, $cussensor) or die(mysql_error());
	
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	
	$totalRows_Recordset2 = mysql_num_rows($Recordset2);

?>

<?php
	$line = '';
	
	$f = fopen('data.txt', 'r');
	
	$cursor = -1;
	
	fseek($f, $cursor, SEEK_END);
	
	$char = fgetc($f);
	
	while ($char === "\n" || $char === "\r") 
	{
    	fseek($f, $cursor--, SEEK_END);
    	$char = fgetc($f);
	}
	while ($char !== false && $char !== "\n" && $char !== "\r") 
	{
		$line = $char . $line;
    	fseek($f, $cursor--, SEEK_END);
    	$char = fgetc($f);
	}
	
	$dafield= explode(";",$line);
	
	$array = array('time'=>$dafield[0],'temperature'=>$dafield[1],'rh'=>$dafield[2]);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>即時監測</title>
</head>

<body>
	<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  	<p>單位 : 
  	<?php echo $row_Recordset1['name']; ?>
  	<input name="customer_id" type="hidden" id="customer_id" value="<?php echo $row_Recordset1['customer_id']; ?>" />
  	</p>
  	<p>感測器 :<?php echo $row_Recordset2['site']; ?>
    <input name="sensor_id" type="hidden" id="sensor_id" value="<?php echo $row_Recordset2['sensor_id']; ?>" />
  	</p>
  	<p>日期 
    <input name="date" type="text" id="date" readonly="readonly" value=" <?php echo $dafield[0];?>"  size="10"/>
    <input type="text" name="time" id="time"  readonly="readonly" value="<?php echo $dafield[1];?>"/>
  	</p>
  	<p>溫度
    <input name="temp" type="text" id="temp" readonly="readonly" value=" <?php echo $dafield[2];?>"/>
  	</p>
  	<p>濕度
    <input name="humidity" type="text" id="humidity" readonly="readonly" value=" <?php echo $dafield[3];?>"/>
  	</p>
  	<input type="hidden" name="MM_insert" value="form1" />
	</form>

	<script>
		setTimeout('form1.submit()',4000);
	</script>
    
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>

