<?php require_once('Connections/cussensor.php'); ?>

<?php session_start();?>

<?php
	if (!function_exists("GetSQLValueString")) 
	{
		function GetSQLValueString($theValue, $theType, $theDefinedValue = "",$theNotDefinedValue = "") 
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

	mysql_select_db($database_cussensor, $cussensor);
	
	$query_Recordset1 = sprintf("SELECT * FROM customer WHERE account = %s", 
		GetSQLValueString($colname_Recordset1, "text"));

	$Recordset1 = mysql_query($query_Recordset1, $cussensor) or die(mysql_error());

	$row_Recordset1 = mysql_fetch_assoc($Recordset1);

	$totalRows_Recordset1 = mysql_num_rows($Recordset1);


	$column_Recordset2 = "-1";
	if (isset($row_Recordset1['customer_id'])) 
	{
  		$column_Recordset2 = $row_Recordset1['customer_id'];
	}

	mysql_select_db($database_cussensor, $cussensor);
	
	$query_Recordset2 = sprintf("SELECT * FROM sensor WHERE customer_id = %s", 
	GetSQLValueString($column_Recordset2, "int"));
	
	$Recordset2 = mysql_query($query_Recordset2, $cussensor) or die(mysql_error());

	$row_Recordset2 = mysql_fetch_assoc($Recordset2);

	$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>選擇感測器</title>
</head>

<body>
	<form id="form1" name="form1" method="post" action="cus-main.php">
    
	<p>姓名 :<?php echo $row_Recordset1['name']; ?>
    
    <input name="customer_id" type="hidden" id="customer_id" value="<?php echo $row_Recordset1['customer_id']; ?>" />
  	
	<?php
  		session_start();
  		$_SESSION['customer_id']= $row_Recordset1['customer_id'];
  	?>
	</p>
	
    <p>感測器 :  
    <select name="sensor_id" id="sensor_id" style="width: auto">
     <?php
		do 
		{  
	?>
      		<option value="<?php echo $row_Recordset2['sensor_id']?>"
			<?php 
				if (!(strcmp($row_Recordset2['sensor_id'], $row_Recordset2['sensor_id']))) 
				{
					echo "selected=\"selected\"";
				} 
			?>
            >
			<?php echo $row_Recordset2['site']?>
            </option>
     
	 <?php
		} 
		while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  				$rows = mysql_num_rows($Recordset2);
  		
		if($rows > 0) 
		{
      		mysql_data_seek($Recordset2, 0);
	  		$row_Recordset2 = mysql_fetch_assoc($Recordset2);
  		}
		?>
        
    </select>
	</p>

	<p>
    <input type="submit" name="yes" id="yes" value="送出" />
	</p>
    
	</form>

</body>
</html>
<?php
mysql_free_result($Recordset1);
mysql_free_result($Recordset2);
?>
