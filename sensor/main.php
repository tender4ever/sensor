<?php require_once('Connections/sensor.php'); ?>

<?php ini_set("max_execution_time", "0"); ?>

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
	

	$colname_Recordset1 = "-1";
	
	if (isset($_SESSION['MM_Username'])) 
	{
  		$colname_Recordset1 = $_SESSION['MM_Username'];
	}
	mysql_select_db($database_sensor, $sensor);
	
	$query_Recordset1 = sprintf("SELECT * FROM customer WHERE account = %s", 
		GetSQLValueString($colname_Recordset1, "text"));
		
	$Recordset1 = mysql_query($query_Recordset1, $sensor) or die(mysql_error());
	
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);


	$colname_Recordset2 = "-1";
	if (isset($_SESSION['customer_id'])) 
	{
  		$colname_Recordset2 = $_SESSION['customer_id'];
	}
	mysql_select_db($database_sensor, $sensor);
	
	$query_Recordset2 = sprintf("SELECT * FROM sensor WHERE customer_id = %s ORDER BY sensor_id ASC", 
		GetSQLValueString($colname_Recordset2, "int"));
		
	$Recordset2 = mysql_query($query_Recordset2, $sensor) or die(mysql_error());
	
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	
	$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>即時監測</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" href="css/body.css"/>
</head>


<body>
	<form id="form1" name="form1" method="post" action="">
    
    <fieldset style="width:310px; height:85px; margin:auto; ">
    
    <legend>
    
    	<a href="main.php"><img src="app-Logo.png" width="35" height="30" /></a>
        單位 : <a href="nameset.php"><?php echo $row_Recordset1['name']; ?></a>
        
    </legend>
    
  	<p><a href="sensor.php">感測器</a> <a href="search.php">歷史查詢</a> <a href="logout.php" >登出</a></p>
    
    </fieldset>
 
    <input name="customer_id" type="hidden" id="customer_id" value="<?php echo $row_Recordset1['customer_id']; ?>" />
    
	<?php 
		session_start();
		$_SESSION['customer_id']=$row_Recordset1['customer_id'];
		$_SESSION['name']=$row_Recordset1['name'];
	?>
    
  	<hr />
    
  	<?php 
		do 
		{ 
	?>
  			<fieldset style="width:310px; height:230px; ">
            
    		<legend> 
    			位置:<?php echo $row_Recordset2['site']; ?>  
    		</legend>
            
    		<a href="setting.php?sensor_id=<?php echo $row_Recordset2['sensor_id']; ?>"> 警示設定</a>
    		<a href="delsetting.php?sensor_id=<?php echo $row_Recordset2['sensor_id']; ?>">關閉警示</a>
      		<iframe src="main1.php?sensor_id=<?php echo $row_Recordset2['sensor_id'];?>" width="300" height="180" scrolling="no" frameborder="0">
            </iframe>
      
     		</fieldset>
    		
    <?php 
		} 
		while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); 
	?>
</form>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
