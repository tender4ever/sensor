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
  		$updateSQL = sprintf("UPDATE customer SET account=%s, password=%s, name=%s, phone=%s, email=%s WHERE customer_id=%s",
                       GetSQLValueString($_POST['text1'], "text"),
                       GetSQLValueString($_POST['text2'], "text"),
                       GetSQLValueString($_POST['text3'], "text"),
                       GetSQLValueString($_POST['text4'], "text"),
                       GetSQLValueString($_POST['text5'], "text"),
                       GetSQLValueString($_POST['hiddenField'], "int"));

  		mysql_select_db($database_sensor, $sensor);
		
  		$Result1 = mysql_query($updateSQL, $sensor) or die(mysql_error());

  		$updateGoTo = "main.php";
  		if (isset($_SERVER['QUERY_STRING'])) 
		{
    		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    		$updateGoTo .= $_SERVER['QUERY_STRING'];
  		}
  		header(sprintf("Location: %s", $updateGoTo));
	}

	$colname_Recordset1 = "-1";
	if (isset($_SESSION['customer_id'])) 
	{
  		$colname_Recordset1 = $_SESSION['customer_id'];
	}
	mysql_select_db($database_sensor, $sensor);
	
	$query_Recordset1 = sprintf("SELECT * FROM customer WHERE customer_id = %s", 
		GetSQLValueString($colname_Recordset1, "int"));
		
	$Recordset1 = mysql_query($query_Recordset1, $sensor) or die(mysql_error());
	
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改個人資料</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" href="css/body.css"/>
</head>

<body>
	<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  	<p>帳號 :
    <label for="text1"></label>
    <input name="text1" type="text" id="text1" value="<?php echo $row_Recordset1['account']; ?>" />
    <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_Recordset1['customer_id']; ?>" />
  	</p>
  	<p>密碼 :
    <label for="text2"></label>
    <input name="text2" type="text" id="text2" value="<?php echo $row_Recordset1['password']; ?>" />
  	</p>
  	<p>姓名 :
    <label for="text3"></label>
    <input name="text3" type="text" id="text3" value="<?php echo $row_Recordset1['name']; ?>" />
  	</p>
  	<p>電話 :
    <label for="text4"></label>
    <input name="text4" type="text" id="text4" value="<?php echo $row_Recordset1['phone']; ?>" />
  	</p>
  	<p>email :
    <label for="text5"></label>
    <input name="text5" type="text" id="text5" value="<?php echo $row_Recordset1['email']; ?>" />
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
