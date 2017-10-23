<?php
define('_HOST_', 'localhost');
define('_DB_', 'forum');
define('_USER_', 'root');
define('_PASS_', 'root');

spl_autoload_register(function($class){
	require 'classes/'.$class.'.class.php';
});

?>