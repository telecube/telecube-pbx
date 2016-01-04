<?php
require("init.php");
$msg = false;
if(isset($_POST["pass_change_form"])){
	// make sure the user/pass1 are not empty
	if(empty($_POST["username"]) || empty($_POST["password_1"]) || empty($_POST["password_2"])){
		$err = "Username and Password cannot be empty!";
	}else{
		// check the passwords match
		if($_POST["password_1"] == $_POST["password_2"]){

			$q = "update preferences set value = ? where name = 'pbx_login_username';";
			$data = array($_POST["username"]);
			$Db->pdo_query($q,$data,$dbPDO);

			$q = "update preferences set value = ? where name = 'pbx_login_password';";
			$data = array($_POST["password_1"]);
			$Db->pdo_query($q,$data,$dbPDO);

			unset($_SESSION["force_password_change"]);

			$msg = "Password update successful. Redirecting to dashboard . <span class=\"glyphicon glyphicon-refresh glyphicon-spin\"></span>";
			
			header( "refresh:5;url=/" );

			$err = false;
		}else{
			$err = "Passwords don't match!";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/meta.php");?>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/title.php");?>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/css.php");?>

  </head>
  <body>
    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

    <div class="container">

      	<h1>Password Change Required!</h1>
		<p>Please enter a new username and password</p>
		<p>&nbsp;</p>


		<div class="row">
        <div class="col-lg-6">

		    <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
		        <div class="form-group">
		            <label for="username">Username</label>
		            <input type="text" class="form-control" id="username" name="username" value="<?php echo $_SESSION['user'];?>">
		            <input type="hidden" name="pass_change_form" value="1">
		        </div>
		        <div class="form-group">
		            <label for="password_1">Password</label>
		            <input type="password" class="form-control" id="password_1" name="password_1" placeholder="Password">
		        </div>
		        <div class="form-group">
		            <label for="password_2">Confirm Password</label>
		            <input type="password" class="form-control" id="password_2" name="password_2" placeholder="Password">
		        </div>
		        <button type="submit" class="btn btn-primary">Update User/Pass</button>
		    </form>

		</div>
		</div>

   		<p>&nbsp;</p>

		<div class="alert alert-warning" role="alert" style="display:<?php echo $err ? "" : "none";?>">
			<strong>Error:</strong> <?php echo $err ? $err : "";?>
		</div>

		<div class="alert alert-success" role="alert" style="display:<?php echo $msg ? "" : "none";?>">
			<strong>Message:</strong> <?php echo $msg ? $msg : "";?>
		</div>

    </div>

    <?php include($_SERVER["DOCUMENT_ROOT"]."/includes/js.php");?>
  
  </body>
</html>