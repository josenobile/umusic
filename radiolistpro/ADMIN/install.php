<?php
print "<html><title>Winamp Controler config page<title><body>";
print "Winamp Controler Class version 1.0<br>\n";
if($_POST['submit']){
    if($_POST['Username'] == ""){
    $str = "<br>You're username is blank... a username is required\n";
    if($_POST['Password'] == "" ){
        $str = "<br>You're password is blank... a password is required\n";
        }
    }
    if($str !=""){
    $connStr = "\$status = new winamp_control(\"{$_POST['username']}\",\"{$_POST['password']}\",\"{$_POST['Server1']}\",\"{$_POST['Server2']}\",\"{$_POST['port']}\"";
    print "Use this string to make a new instance of the winamp class...\n";
    print "<br>$connStr\n<br>";
    }else{
    print "$str";
}
print "</body><html>";
}