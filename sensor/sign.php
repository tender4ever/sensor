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


// *** Redirect if username exists

	$MM_flag="MM_insert";
	
	if (isset($_POST[$MM_flag])) 
	{
  		$MM_dupKeyRedirect="signfail.php";
		
  		$loginUsername = $_POST['text1'];
		
  		$LoginRS__query = sprintf("SELECT account FROM customer WHERE account=%s", 
			GetSQLValueString($loginUsername, "text"));
			
  		mysql_select_db($database_sensor, $sensor);
		
  		$LoginRS=mysql_query($LoginRS__query, $sensor) or die(mysql_error());
		
  		$loginFoundUser = mysql_num_rows($LoginRS);

//if there is a row in the database, the username was found - can not add the requested username
  
  		if($loginFoundUser)
		{
    		$MM_qsChar = "?";
    
//append the username to the redirect page

    		if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
		
    		$MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
		
   			header ("Location: $MM_dupKeyRedirect");
		
    		exit;
  		}
	}

	$editFormAction = $_SERVER['PHP_SELF'];
	
	if (isset($_SERVER['QUERY_STRING'])) 
	{
  		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
	{
  		$insertSQL = sprintf("INSERT INTO customer (account, password, name, phone, email) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['text1'], "text"),
                       GetSQLValueString($_POST['text2'], "text"),
                       GetSQLValueString($_POST['text3'], "text"),
                       GetSQLValueString($_POST['text4'], "text"),
                       GetSQLValueString($_POST['text5'], "text"));

  		mysql_select_db($database_sensor, $sensor);
		
  		$Result1 = mysql_query($insertSQL, $sensor) or die(mysql_error());

  		$insertGoTo = "login.php";
		
  		if (isset($_SERVER['QUERY_STRING'])) 
		{
    		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    		$insertGoTo .= $_SERVER['QUERY_STRING'];
  		}
  		header(sprintf("Location: %s", $insertGoTo));
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>註冊</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" href="css/body.css"/>
</head>

<body>
	<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    
  	<p>帳號 :
    <input type="text" name="text1" id="text2" />
  	</p>
 	<p>密碼 :  
    <input type="text" name="text2" id="text3" />
  	</p>
  	<p>姓名 : 
    <label for="text3"></label>
    <input type="text" name="text3" id="text3" />
  	</p>
  	<p>電話 : 
    <label for="text4"></label>
    <input type="text" name="text4" id="text4" />
  	</p>
  	<p>email :  
    <label for="text5"></label>
    <input type="text" name="text5" id="text5" />
  	</p>
  	<p>
    <input type="submit" name="yes" id="yes" value="送出" style="font-size: 20px; font-weight: bold; font-family:DFKai-sb;"/>
    <input type="reset" name="no" id="no" value="重設" style="font-size: 20px; font-weight: bold; font-family:DFKai-sb;"/>
    <input type="hidden" name="MM_insert" value="form1" />
</p>
</form>
</body>
</html>