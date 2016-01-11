<?php
require("init.php");

function get_server_cpu_usage(){

    $load = sys_getloadavg();
    return $load[0];

}

function get_server_memory_usage(){

    $free = shell_exec('free');
    $free = (string)trim($free);
    $free_arr = explode("\n", $free);
    $mem = explode(" ", $free_arr[1]);
    $mem = array_filter($mem);
    $mem = array_merge($mem);
    $memory_usage = $mem[2]/$mem[1]*100;

    return $memory_usage;
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

		<h1>Telecube Cloud PBX</h1>
		
		<?php echo "<p>".date("H:i:s")."</p>";?>

		<?php echo "<p>".get_server_cpu_usage()."</p>";?>

		<?php echo "<p>".round(get_server_memory_usage(),2)."</p>";?>

		</div>

		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/js.php");?>

	</body>
</html>