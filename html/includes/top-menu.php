<?php
$rq_uri = $_SERVER['REQUEST_URI'];

$dashboard_active = $rq_uri == "/" ? 'class="active"' : "";
$update_active = strpos($rq_uri, "/update/") !== false ? 'class="active"' : "";

$server_settings_active = strpos($rq_uri, "/firewall/") !== false ? ' active' : ""; 
$firewall_active = strpos($rq_uri, "/firewall/") !== false ? 'class="active"' : ""; 

$pbx_settings_active = strpos($rq_uri, "/extensions/") !== false 
	|| strpos($rq_uri, "/trunks/") !== false ? ' active' : ""; 
$extensions_active = strpos($rq_uri, "/extensions/") !== false ? 'class="active"' : ""; 
$trunks_active = strpos($rq_uri, "/trunks/") !== false ? 'class="active"' : ""; 


?>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Telecube PBX</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li <?php echo $dashboard_active;?>><a href="/">Dashboard</a></li>
				<li class="dropdown  <?php echo $pbx_settings_active;?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">PBX Settings <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li <?php echo $extensions_active;?>><a href="/extensions/">Extensions</a></li>
						<li <?php echo $trunks_active;?>><a href="/trunks">Trunks</a></li>
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">PBX Features</li>
						<li><a href="#">Auto Attendant (IVR)</a></li>
						<li><a href="#">Queues (Ring Groups)</a></li>
						<li><a href="#">Music On Hold</a></li>
						<li><a href="#">Busy Lamp Field</a></li>
						<li><a href="#">Address Book</a></li>
					</ul>
				</li>
				<li class="dropdown <?php echo $server_settings_active;?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Server Settings <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li <?php echo $firewall_active;?>><a href="/firewall/">Firewall</a></li>
					</ul>
				</li>
				<li <?php echo $update_active;?>><a href="/update/">Update <span class="badge"><?php echo $update_wait_count == 0 ? "" : $update_wait_count;?></span></a></li>
				<li><a href="/logout.php">Logout</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>
