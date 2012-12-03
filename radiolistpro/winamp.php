<?PHP
set_time_limit(0);
ob_start("ob_gzhandler");
//phpinfo();exit;
echo "<pre>".microtime(true);
/*$winamp = 'C:/Archivos de programa/Winamp/winamp.exe';
$lista = '//Radiocomunicate/lista de reproduccion radiocomunicate/ELECTROBIT JUEVES 8H 26022009 27022009.m3u';
$comando = $winamp." ".$lista;
$comando = '& comandos.bat';
$salida2 = shell_exec($comando);
echo "Salida: {$salida} - {$salida2}";
*/
/*$runCommand = "C:\\WINDOWS\\system32\\explorer.exe"; //Wrong by purpuse to get some good output
$WshShell = new COM("WScript.Shell");
$output = $WshShell->Exec($runCommand)->StdOut->ReadAll;
echo "<p>$output</p>";*/
/*$runCommand = 'comandos.bat';
 $WshShell = new COM("WScript.Shell");
    $oExec = $WshShell->Run($runCommand, 7, false);*/
	
	function newIEtoForeground($title, $evtPrefix="") {
    // brings new instance of IE to foreground with title $title
    if (!$extPrefix) $ie = new COM("InternetExplorer.Application");
    else $ie = new COM("InternetExplorer.Application", $evtPrefix);
    $ie->Navigate2("about:blank");
    $oWSH = new COM("WScript.Shell");
    while ($ie->ReadyState!=4) usleep(1000);

    $ie->Document->Title = ($tmpTitle = mt_rand());  //unique title
    $ie->Visible = true;
    while (!$oWSH->AppActivate("$tmpTitle - M")) usleep(10000);

    $ie->Document->Title = $title;
    $ie->Document->ParentWindow->opener="me";  // allows self.close()
    return $ie;
}
//echo newIEtoForeground("Pruebas de jose");
 /* $shell= &new COM('WScript.Shell');
  var_dump($shell->regRead('HKEY_CURRENT_USER\Environment\TEMP'));*/
 /* $shell= &new COM('WScript.Shell');
  var_dump($shell->regRead('HKEY_CURRENT_USER\Environment\TEMP'));*/
 /* $runCommand = "C:/WINDOWS/SYSTEM32/notepad.exe"; 
$WshShell = new COM("WScript.Shell",array("Server"=>"RADIOCOMUNICATE","Username"=>"administrador","Password"=>"skycomunic"),0,true);
$WshShell->Run($runCommand);*/
//usleep(1000);

//chdir("C:/archivos de programa/TeamViewer/Version4");
echo system("netstat -b -a -n");
echo "<br />".microtime(true);
?></pre>