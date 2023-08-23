<?php 

include (dirname(__FILE__) .'/../templates/header.php');

global $session;
global $path;

$session -> logout();
redirect($path);


?>