
<?php 
include_once "../app/config.php";
session_unset();
session_destroy();
redirect('index.php','');
?>