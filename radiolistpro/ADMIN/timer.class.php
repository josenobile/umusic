<?php
if(!defined('TIMER_CLASS')){ // is the timer class defined yet?
    define('TIMER_CLASS',true); // define the class, so we don't include it more than once and have problems
    class timer{
	var $start_time;
	var $total_time;
	function start(){
	    $mtime = microtime();
	    $mtime = explode(" ",$mtime);
	    $mtime = $mtime[1] + $mtime[0];
	    $this->start_time = $mtime;
	}
	function stop(){
	    $mtime = microtime();
	    $mtime = explode(" ",$mtime);
	    $mtime = $mtime[1] + $mtime[0];
	    $end_time = $mtime;
	    $this->total_time = ($end_time - $this->start_time);
	}
	function gettime(){
	    return $this->total_time;
	}
    }
}
?>
