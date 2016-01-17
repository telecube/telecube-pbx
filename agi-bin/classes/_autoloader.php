<?php

$mapping = array(
	'Telecube\Agi' 					=> __DIR__ . '/phpagi.php',
	'Telecube\AGI_AsteriskManager' 	=> __DIR__ . '/phpagi-asmanager.php',
	'Telecube\Channel' 				=> __DIR__ . '/channel.php',
	'Telecube\Common' 				=> __DIR__ . '/common.php',
	'Telecube\Config' 				=> __DIR__ . '/config.php',
	'Telecube\CubeAgi' 				=> __DIR__ . '/phpagi-telecube.php',
	'Telecube\Db' 					=> __DIR__ . '/db.php',
	'Telecube\Exten' 				=> __DIR__ . '/extension.php',
	'Telecube\Linehunt' 			=> __DIR__ . '/linehunt.php',
	'Telecube\Trunk' 				=> __DIR__ . '/trunk.php',




/* not used */
	'Telecube\Curl' 				=> __DIR__ . '/curl.php',
	'Telecube\Did' 					=> __DIR__ . '/did.php',

	'Telecube\Callforward' 			=> __DIR__ . '/callforward.php',
	'Telecube\Iptrunk' 				=> __DIR__ . '/iptrunk.php',
	'Telecube\Liveop' 				=> __DIR__ . '/liveop.php',
	'Telecube\Queue' 				=> __DIR__ . '/queue.php',
	'Telecube\Ringgroup' 			=> __DIR__ . '/ringgroup.php',
);


spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require_once( $mapping[$class] );
    }
}, true);

?>