<?php

require_once 'frontend_tpl_conf.php';

if(!empty($_POST)) {    
    //echo "<pre>".print_r($_POST, true)."</pre>";die();    
    $retval = $session->login($_POST['username'], $_POST['password'], isset($_POST['remember']));
        
    if($retval){       
        //header("Location: ".$session->referrer);
        unset($_SESSION['error_array']);
		session_write_close();//free the php
		$return = "index.php";
		if(isset($_GET['return']) && $_GET['return'] != '' && strpos($_GET['return'],"qid=")===false){
			$return = $_GET['return'];
		}
        header("Location: ".$return);
    }
    else{
        //header("Location: ".$session->referrer);
        echo $engine->render('formLogin', array("error_array" => $session->getErrors()));
    }    
}
else { 
    echo $engine->render('formLogin');
}

?>