<?php
include('connect.php');
if($status->isplaying){
$status->controlme("pause");
}else{
$status->controlme("play");
}
include('disconnect.php');
?>