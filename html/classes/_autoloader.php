<?php

$mapping = array(
	'Telecube\Agi' 			=> __DIR__ . '/phpagi/phpagi.php',
	'Telecube\CubeAgi' 		=> __DIR__ . '/phpagi/phpagi-telecube.php',
	'Telecube\Config' 		=> __DIR__ . '/config.php',
	'Telecube\Common' 		=> __DIR__ . '/common.php',
	'Telecube\Curl' 		=> __DIR__ . '/curl.php',
	'Telecube\Channel' 		=> __DIR__ . '/channel.php',
	'Telecube\Db' 			=> __DIR__ . '/db.php',
	'Telecube\Did' 			=> __DIR__ . '/did.php',
	'Telecube\Exten' 		=> __DIR__ . '/exten.php',

	'Telecube\Callforward' 	=> __DIR__ . '/callforward.php',
	'Telecube\Iptrunk' 		=> __DIR__ . '/iptrunk.php',
	'Telecube\Linehunt' 	=> __DIR__ . '/linehunt.php',
	'Telecube\Liveop' 		=> __DIR__ . '/liveop.php',
	'Telecube\Queue' 		=> __DIR__ . '/queue.php',
	'Telecube\Ringgroup' 	=> __DIR__ . '/ringgroup.php',
);


spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require_once( $mapping[$class] );
    }
}, true);

?>