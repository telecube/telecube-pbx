<?php
$rq_uri = trim($_SERVER['REQUEST_URI'],"/");

$dashboard_active = $rq_uri == "" ? 'class="active"' : "";
$update_active = strpos($rq_uri, "update") !== false ? 'class="active"' : "";

/* Server Settings Nav */
$server_settings_active_arr = array("firewall","preferences","manage-services","night-lock");
$server_settings_active 	= in_array($rq_uri, $server_settings_active_arr) !== false ? ' active' : ""; 
$firewall_active 			= strpos($rq_uri, "firewall") !== false ? 'class="active"' : ""; 
$preferences_active 		= strpos($rq_uri, "preferences") !== false ? 'class="active"' : ""; 
$manage_services_active 	= strpos($rq_uri, "manage-services") !== false ? 'class="active"' : ""; 
$debugging_active 			= strpos($rq_uri, "debugging") !== false ? 'class="active"' : ""; 
$nightlock_active 			= strpos($rq_uri, "night-lock") !== false ? 'class="active"' : ""; 

/* PBX Settings Nav */
$pbx_settings_active_arr 	= array("extensions","trunks","dids","line-hunt","line-hunt/edit.php");
$pbx_settings_active 		= in_array($rq_uri, $pbx_settings_active_arr) !== false ? ' active' : ""; 
$extensions_active 			= strpos($rq_uri, "extensions") !== false ? 'class="active"' : ""; 
$trunks_active 				= strpos($rq_uri, "trunks") !== false ? 'class="active"' : ""; 
$dids_active 				= strpos($rq_uri, "dids") !== false ? 'class="active"' : ""; 
$linehunt_active 			= strpos($rq_uri, "line-hunt") !== false ? 'class="active"' : ""; 


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
			<a class="navbar-brand" href="/"><span class="glyphicon glyphicon-home"></span> Telecube PBX</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li <?php echo $dashboard_active;?>><a href="/"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>
				<li class="dropdown  <?php echo $pbx_settings_active;?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-phone-alt"></span> PBX Settings <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li <?php echo $extensions_active;?>><a href="/extensions/"><span class="glyphicon glyphicon-earphone"></span> Extensions</a></li>
						<li <?php echo $trunks_active;?>><a href="/trunks/"><span class="glyphicon glyphicon-transfer"></span> Trunks</a></li>
						<li <?php echo $dids_active;?>><a href="/dids/"><span class="glyphicon glyphicon-random"></span> DIDs</a></li>
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">PBX Features</li>
						<li <?php echo $linehunt_active;?>><a href="/line-hunt/"><span class="glyphicon glyphicon-retweet"></span> Line Hunt Group</a></li>
						<li class="disabled"><a href="#"><span class="glyphicon glyphicon-list"></span> Ring Group (Queue)</a></li>
						<li class="disabled"><a href="#"><span class="glyphicon glyphicon-bullhorn"></span> Auto Attendant (IVR)</a></li>
						<li class="disabled"><a href="#"><span class="glyphicon glyphicon-time"></span> Time Based Rules</a></li>
						<li role="separator" class="divider"></li>
						<li class="disabled"><a href="#"><span class="glyphicon glyphicon-music"></span> Music On Hold</a></li>
						<li class="disabled"><a href="#"><span class="glyphicon glyphicon-envelope"></span> Voicemail</a></li>
						<li class="disabled"><a href="#"><span class="glyphicon glyphicon-unchecked"></span>Busy Lamp Field</a></li>
						<li class="disabled"><a href="#"><span class="glyphicon glyphicon-book"></span> Address Book</a></li>
					</ul>
				</li>
				<li class="dropdown <?php echo $server_settings_active;?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-hdd"></span> Server Settings <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li <?php echo $firewall_active;?>><a href="/firewall/"><span class="glyphicon glyphicon-fire text-danger"></span> Firewall</a></li>
						<li <?php echo $preferences_active;?>><a href="/preferences/"><span class="glyphicon glyphicon-compressed"></span>Preferences</a></li>
						<li <?php echo $manage_services_active;?>><a href="/manage-services/"><span class="glyphicon glyphicon-cog"></span> Manage Services</a></li>
						<li <?php echo $nightlock_active;?>><a href="/night-lock/"><span class="glyphicon glyphicon-lock"></span> Night Lock</a></li>
						<li <?php echo $debugging_active;?>><a href="/debugging/"><span class="glyphicon glyphicon-console"></span> Debugging</a></li>
					</ul>
				</li>
				<li <?php echo $update_active;?>><a href="/update/"><span class="glyphicon glyphicon-save"></span> Update <span class="badge"><?php echo $update_wait_count == 0 ? "" : $update_wait_count;?></span></a></li>
				<li><a href="/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>
