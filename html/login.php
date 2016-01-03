<?php
require("classes/_autoloader.php");
use Telecube\Config;
use Telecube\Db;

$err = false;

if(isset($_POST['user'])){
	$Config = new Config;
	$Db     = new Db;

	try{
		$dbPDO = new PDO('mysql:dbname='.$Config->get("db_name").';host='.$Config->get("db_host").';port='.$Config->get("db_port"), $Config->get("db_user"), $Config->get("db_pass"));
	} catch(PDOException $ex){
		exit( 'Connection failed: ' . $ex->getMessage() );
	}
	$dbPDO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	$q = "select * from preferences where name = 'pbx_login_username' || name = 'pbx_login_password'";
	$data = array();
	$res = $Db->pdo_query($q,$data,$dbPDO);
	
	$pbx_login_username = false;
	$pbx_login_password = false;
	
	$j = count($res);
	for($i=0;$i<$j;$i++) { 
		if($res[$i]['name'] == "pbx_login_username"){
			$pbx_login_username = $res[$i]['value'];
		}
		if($res[$i]['name'] == "pbx_login_password"){
			$pbx_login_password = $res[$i]['value'];
		}
	}
	if($pbx_login_username && $pbx_login_password){
		if($_POST['user'] == $pbx_login_username && $_POST['pass'] == $pbx_login_password){

			session_name($Config->session_name);
			session_start();

			$_SESSION["user"] = $pbx_login_username;
			header("Location: /");
		}else{
			$err = "Login credentials incorrect.";
		}

	}else{
		$err = "PBX user/pass not configured.";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Telecube PBX</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

  </head>
  <body>

    <div class="container">

    <h1>Hello, Telecube PBX!</h1>

	<div class="alert alert-warning" role="alert" style="display:<?php echo $err ? "" : "none";?>">
		<strong>Error:</strong> <?php echo $err ? $err : "";?>
	</div>

    <form class="form-signin" method="post">
      <h2 class="form-signin-heading">Log in</h2>
      <label for="user" class="sr-only">Username</label>
      <input type="text" id="user" name="user" class="form-control" placeholder="Username" required autofocus>
      <label for="password" class="sr-only">Password</label>
      <input type="password" id="pass" name="pass" class="form-control" placeholder="Password" required>
      <div class="checkbox">
        <label>
        <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
    </form>

    </div> <!-- /container -->



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>
