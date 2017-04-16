<?php session_start();?>

<?php 
	if($_SESSION['name']== null)
	{
		header("Location:login.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" href="css/body.css"/>
<title>歷史查詢</title>
</head>

<body>
	<form id="form1" name="form1" method="post" action="searchresult.php">
    
    <fieldset style="width:310px; height:85px; margin:auto; ">
    
    <legend>
    
    	<a href="main.php"><img src="app-Logo.png" width="35" height="30" /></a>
        單位 : <a href="nameset.php"><?php echo $_SESSION['name'];?></a>
        
    </legend>
    
  	<p><a href="sensor.php">感測器</a> <a href="search.php">歷史查詢</a> <a href="login.php" >登出</a></p>
    
    </fieldset>
    
    <hr />
    
  	<p>日期 :
  	<input type="date" name="date" id="date" size="15"/>
    <input type="time" name="time" id="time" size="15"/>
  	<input type="submit" name="button" id="button" value="查詢" style="font-size: 20px; font-weight: bold; font-family:DFKai-sb;"/>
  	</p>
	</form>
</body>
</html>