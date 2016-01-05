<?php

$mapping = array(
	'Telecube\Config' 		=> __DIR__ . '/config.php',
	'Telecube\Common' 		=> __DIR__ . '/common.php',
	'Telecube\Curl' 		=> __DIR__ . '/curl.php',
	'Telecube\Db' 			=> __DIR__ . '/db.php',
);


spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require_once( $mapping[$class] );
    }
}, true);

?>