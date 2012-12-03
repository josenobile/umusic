<?PHP
set_time_limit(0);
ob_implicit_flush(1);
function listdir($start_dir='.') {
echo ". ";
flush();
  $files = array();
  if (is_dir($start_dir)) {
    $fh = opendir($start_dir);
    while (($file = readdir($fh)) !== false) {
      # loop through the files, skipping . and .., and recursing if necessary
      if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue;
      $filepath = $start_dir . '/' . $file;
      if ( is_dir($filepath) )
        $files = array_merge($files, listdir($filepath));
      else
        array_push($files, $filepath);
    }
    closedir($fh);
  } else {
    # false if the function was called with an invalid non-directory argument
    $files = false;
  }
  return $files;
}


$rutaF = "//zararadio/C/RADIOCOMUNICATE OK/MUSITECA/ELECTROBIT";
echo "renombrando: <br />";
$dirs = scandir($rutaF);
foreach($dirs as $ruta){
//echo $ruta."<br />";
if(/*file_exists($ruta)*/true){
echo $ruta."-&gt;".str_replace("-"," ",strtoupper($ruta))."<br />";
rename ($rutaF."/".$ruta,str_replace("-"," ",strtoupper($rutaF."/".$ruta)));
}
}
echo "Fin";
/*foreach(listdir($ruta) as $ruta){
echo ". ";
rename($ruta,str_replace("-"," ",strtoupper(basename($ruta))));
flush();
}*/
?>