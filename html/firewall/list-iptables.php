<?php
require("../init.php");

$last = exec('sudo /sbin/iptables -nL', $o, $r);  
print nl2br(htmlentities(implode("\n", $o)));

?>