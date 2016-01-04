<?php
require("classes/_autoloader.php");
use Telecube\Config;
use Telecube\Db;

$err = false;

if(isset($_POST['login_form'])){
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

			session_name($Config->get("session_name"));
			session_start();

			$_SESSION["user"] = $pbx_login_username;
			// check for default user/pass
			if($pbx_login_username == "admin" && $pbx_login_password == "admin"){
				$_SESSION["force_password_change"] = 1;
			}

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

		<div class="row">
        
        <div class="col-lg-3">
        </div>
        <div class="col-lg-6">


		    <h1>Hello, Telecube PBX!</h1>

		    <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
		        <div class="form-group">
		            <label for="user">Username</label>
		            <input type="text" class="form-control" id="user" name="user" placeholder="username">
		            <input type="hidden" name="login_form" value="1">
		        </div>
		        <div class="form-group">
		            <label for="pass">Password</label>
		            <input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
		        </div>

		        <button type="submit" class="btn btn-primary">Login</button>
		    </form>

			<p>&nbsp;</p>
			<div class="alert alert-warning" role="alert" style="display:<?php echo $err ? "" : "none";?>">
				<strong>Error:</strong> <?php echo $err ? $err : "";?>
			</div>


		</div>
		</div>


    </div> <!-- /container -->




    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>
