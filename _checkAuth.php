<?php
if(/*!isset($_SERVER['REMOTE_ADDR']) || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"*/ true){
	$session->logged_in = TRUE;//ejecutado por consola
	$session->userLogin = "admin";
	$session->userLevel = 2;
	$session->userInfo = array("profile_id"=>1,"group_id"=>2,"login"=>"admin","target_name"=>"Cali", "user_id"=>1);
}
$currentPage = $_SERVER['REQUEST_URI'];
if(isset($_GET['return'])){
	$return = rawurlencode($_GET['return']);
}
elseif(strpos($currentPage,"login.php") === false)
	$return = rawurlencode($currentPage);
else
	$return = "index.php";
if(!$session->logged_in && basename($_SERVER["PHP_SELF"])!="login.php") {
    //die($session->referrer);
	if(strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest"){
		echo json_encode(array("time"=>"expired","msg"=>$session->information));
		exit;
	}
	header("Location: login.php?return=".$return);
    exit(0);
}elseif($session->logged_in && basename($_SERVER["PHP_SELF"])=="login.php"){//Yet is logged, 2011-03-25
	header("Location: ".rawurldecode($return));
    exit(0);
}
if(isset($_REQUEST["btnLogout"])) {
    $retval = $session->logout();
    header("Location: login.php?return=".$return);
    exit(0);
}

?>