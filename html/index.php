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
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/js.php");?>


		<script type="text/javascript" src="/js/d3/d3.min.js"></script>
		<script type="text/javascript" src="/js/d3/gauge.js"></script>

		<script>
						
				
			var gauges = [];
			
			function createGauge(name, label, min, max)
			{
				var config = 
				{
					size: 120,
					label: label,
					min: undefined != min ? min : 0,
					max: undefined != max ? max : 100,
					minorTicks: 5
				}
				
				var range = config.max - config.min;
				config.yellowZones = [{ from: config.min + range*0.75, to: config.min + range*0.9 }];
				config.redZones = [{ from: config.min + range*0.9, to: config.max }];
				
				gauges[name] = new Gauge(name + "GaugeContainer", config);
				gauges[name].render();
			}
			
			function createGauges()
			{
				createGauge("memory", "Memory");
				createGauge("cpu", "CPU");
				createGauge("network", "Network");
				//createGauge("test", "Test", -50, 50 );
			}
			
			function updateGauges()
			{
				for (var key in gauges)
				{
					var value = getRandomValue(gauges[key])
					gauges[key].redraw(value);
				}
			}
			
			function getRandomValue(gauge)
			{
				var overflow = 0; //10;
				return gauge.config.min - overflow + (gauge.config.max - gauge.config.min + overflow*2) *  Math.random();
			}
			
			function initialize()
			{
				createGauges();
				setInterval(updateGauges, 5000);
			}
			
		</script>


	</head>
	<body onload="initialize();">


		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1><span class="glyphicon glyphicon-dashboard"></span> Telecube Cloud PBX</h1>
			
			<?php echo "<p>".date("H:i:s")."</p>";?>

			<?php echo "<p>".get_server_cpu_usage()."</p>";?>

			<?php echo "<p>".round(get_server_memory_usage(),2)."</p>";?>


			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Dummy CPU Usage (Not Real Usage!)</h3>
			  </div>
			  <div class="panel-body">


		<span id="memoryGaugeContainer"></span>
		<span id="cpuGaugeContainer"></span>
		<span id="networkGaugeContainer"></span>
		<span id="testGaugeContainer"></span>


			  </div>
			</div>


		</div>


	</body>
</html>