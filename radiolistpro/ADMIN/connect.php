<?php 
$debug = false; 
$port=999;
require("class.winampstatus.php"); 
// instance the class with : username,pass  , first server     , fallback server (optional) 
// code to check if the server has been passed by a script...
if(isset($_POST['server'])){ 
    $server = $_POST['server']; 
}elseif(isset($_GET['server'])){ 
    $server = $_GET['server']; 
} 
$status = new winamp_control("user","pass","firstserver","secondserver",$port,$debug); 
if(!$status->error){ 
    if($debug){ 
    print "Thread = {$status->Thread}\n"; 
    } 
}else{ 
    print "The Following Error Occurred {$status->error}\n"; 
} 
?> 