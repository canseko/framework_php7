<?php

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$stopwatch = array();
$timeStart = microtime_float();
define("CURRENT_PATH", realpath(".") . "/");
define("SYSTEM_PATH", substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), "/") + 1));

//include SYSTEM_PATH . 'includes/Cache.php';

if(empty($robot))
    include SYSTEM_PATH . 'config/config.php';
else
	include SYSTEM_PATH . 'config/config_robot.php';

include SYSTEM_PATH . 'classes/mysql/shared/ez_sql_core.php';
include SYSTEM_PATH . 'classes/mysql/ez_sql_mysqli.php';

//start includes opcionales
include SYSTEM_PATH . 'classes/class.safesql.php';
include SYSTEM_PATH . 'classes/class.dbForm.php';
include SYSTEM_PATH . 'classes/class.upload.php';
include SYSTEM_PATH . 'classes/class.devices.php';
//end includes opcionales

include SYSTEM_PATH . 'includes/functions.php';
include SYSTEM_PATH . 'includes/global.php';

if(file_exists(CURRENT_PATH . 'code_behind/' . $page))
	include CURRENT_PATH . 'code_behind/' . $page;