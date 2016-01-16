<?php

$mapping = array(
	'Telecube\Asterisk' 	=> __DIR__ . '/asterisk.php',
	'Telecube\Config' 		=> __DIR__ . '/config.php',
	'Telecube\Common' 		=> __DIR__ . '/common.php',
	'Telecube\Curl' 		=> __DIR__ . '/curl.php',
	'Telecube\Db' 			=> __DIR__ . '/db.php',
	'Telecube\Ext' 			=> __DIR__ . '/extension.php',
	'Telecube\Linehunt' 	=> __DIR__ . '/linehunt.php',
	'Telecube\Log' 			=> __DIR__ . '/log.php',
	'Telecube\Server' 		=> __DIR__ . '/server.php',
	'Telecube\Trunk' 		=> __DIR__ . '/trunk.php',
);


spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require_once( $mapping[$class] );
    }
}, true);

?>