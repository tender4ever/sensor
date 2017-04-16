<?php session_start();?>

<?php require_once('Connections/sensor.php'); ?>

<?php
	if (!function_exists("GetSQLValueString")) 

	{
		function GetSQLValueString($theValue, $theType, $theDefinedValue = "", 							$theNotDefinedValue = "") 
		{
  			if (PHP_VERSION < 6) 
			{
    			$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  			}

  				$theValue = function_exists("mysql_real_escape_string") ?mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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
?>

<?php
// *** Validate request to login to this site.

	$loginFormAction = $_SERVER['PHP_SELF'];
	if (isset($_GET['accesscheck'])) 
	{
  		$_SESSION['PrevUrl'] = $_GET['accesscheck'];
	}

	if (isset($_POST['account'])) 
	{
  		$loginUsername=$_POST['account'];
  		$password=$_POST['password'];
  		$MM_fldUserAuthorization = "";
  		$MM_redirectLoginSuccess = "main.php";
  		$MM_redirectLoginFailed = "loginfail.php";
  		$MM_redirecttoReferrer = false;
  		mysql_select_db($database_sensor, $sensor);
  
  		$LoginRS__query=sprintf("SELECT account, password FROM customer WHERE account=%s AND password=%s",
    	GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  		$LoginRS = mysql_query($LoginRS__query, $sensor) or die(mysql_error());
  		$loginFoundUser = mysql_num_rows($LoginRS);
  		if ($loginFoundUser) 
		{
     		$loginStrGroup = "";
    
		if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    	//declare two session variables and assign them
    	$_SESSION['MM_Username'] = $loginUsername;
    	$_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    	if (isset($_SESSION['PrevUrl']) && false) 
		{
      		$MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    	}
    	header("Location: " . $MM_redirectLoginSuccess );
  		}
  		else 
		{
    		header("Location: ". $MM_redirectLoginFailed );
  		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登入</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" href="css/body.css"/>
</head>

<body>
	<form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
    
    <fieldset style="width:310px; height:230px; margin:auto">
    
    <legend style="font-family:DFKai-sb">
    	登入
     </legend>
     
  	<P>帳號 
 	<label for="account"></label>
 	<input type="text" name="account" id="account" width="200"/>
  	</P>
  	<p>密碼
 	<label for="password"></label>
 	<input type="text" name="password" id="password" width="200"/>
  	</p>
  	<p> 
 	<input type="submit" name="yes" id="yes" value="送出" style="font-size: 20px; font-weight: bold; font-family:DFKai-sb;"/>
 	<input type="reset" name="no" id="no" value="重設" style="font-size: 20px; font-weight: bold ; font-family:DFKai-sb;" />
  	</p>
    </fieldset>
	</form>
</body>
</html>