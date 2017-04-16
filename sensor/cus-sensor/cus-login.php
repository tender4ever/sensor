<?php require_once('Connections/cussensor.php'); ?>

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
?>

<?php
// *** Validate request to login to this site.
	if (!isset($_SESSION)) 
	{
  		session_start();
	}

	$loginFormAction = $_SERVER['PHP_SELF'];
	
	if (isset($_GET['accesscheck'])) 
	{
  		$_SESSION['PrevUrl'] = $_GET['accesscheck'];
	}

	if (isset($_POST['text1'])) 
	{
  		$loginUsername=$_POST['text1'];
  		$password=$_POST['text2'];
  		$MM_fldUserAuthorization = "";
  		$MM_redirectLoginSuccess = "cus-choice.php";
  		$MM_redirectLoginFailed = "cus-loginfail.php";
  		$MM_redirecttoReferrer = false;
  		mysql_select_db($database_cussensor, $cussensor);
  
  		$LoginRS__query=sprintf("SELECT account, password FROM customer WHERE account=%s AND password=%s",
    		GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  		$LoginRS = mysql_query($LoginRS__query, $cussensor) or die(mysql_error());
  		$loginFoundUser = mysql_num_rows($LoginRS);
  		if ($loginFoundUser) 
		{
     		$loginStrGroup = "";
    
			if (PHP_VERSION >= 5.1) 
			{
				session_regenerate_id(true);
			} 
			else 
			{
				session_regenerate_id();
			}
	
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
</head>

<body>
	<form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
  	<p>帳號 : 
    <input type="text" name="text1" id="text1" />
  	</p>
  	<p>密碼 : 
    <input type="text" name="text2" id="text2" />
  	</p>
  	<p>
    <input type="submit" name="yes" id="yes" value="送出" />
    <input type="reset" name="no" id="no" value="重設" />
  	</p>
	</form>
</body>
</html>