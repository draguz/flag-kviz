<?php 
include ('includes/init.php'); 

$broj = $_POST['broj'];


//echo Zastava::$limit.'<hr>';
$z = new Zastava();
$z->setLimit($broj);


?>