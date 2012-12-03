<?php require_once('../../Connections/radiocomunicate.php'); ?><?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
   mysql_select_db($database_radiocomunicate, $radiocomunicate);
   
   function microtime_float()
{
    list($useg, $seg) = explode(" ", microtime());
    return ((float)$seg+(float)$useg);
}
$tiempoInicio = microtime_float();
/*
Este archivo es usado para escanear los directorios
Leer los tags artista, titulo, genero, longitud
Guardarlo en una base de datos
*/

header('Content-Type: text/html; charset=iso-8859-1');
//ob_start("ob_gzhandler");
ob_implicit_flush(TRUE);
ob_end_flush();
set_time_limit(0);
ini_set("memory_limit","-1");   
require_once('id3/getid3/getid3.php');
require_once('id3/demos/demo.audioinfo.class.php');   
echo '<pre>';
function listdir($start_dir='.') {
global $tiempoInicio;
//echo ". ";
  $files = array();
  if (is_dir($start_dir)) {
    $fh = opendir($start_dir);
    while (($file = readdir($fh)) !== false) {
	$ruta = str_replace('\\','/',$_POST['RUTA']);
	?><script type="text/javascript">
document.tester.ruta.value = '<?PHP echo addslashes(str_replace($ruta,"",$start_dir.'/'.$file));?>';
document.tester.accion.value = 'Buscando archivos - <?PHP echo microtime_float()-$tiempoInicio;?>';
</script><?PHP 
      # loop through the files, skipping . and .., and recursing if necessary
      if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue;
      $filepath = $start_dir . '/' . $file;
      if ( is_dir($filepath) )
        $files = array_merge($files, listdir($filepath));
      else
        array_push($files, $filepath);
    }
    closedir($fh);
  } elseif(is_file($start_dir)) {
  $files = array($start_dir);
  }else{
    # false if the function was called with an invalid non-directory argument
    $files = false;
  }
  return $files;
}//list dir
$ruta = str_replace('\\','/',$_POST['RUTA']);
?>
<form name="tester" >
Accion: <input name="accion" type="text" value="..." size="500" />
<br />
Ruta: <input name="ruta" type="text" value="..." size="500" />
<br />
Titulo: <input name="titulo" type="text" value="..." size="500" />
<br />
Artista: <input name="artista" type="text" value="..." size="500" />
<br />
Duracion: <input name="duracion" type="text" value="..." size="500" />
<br />

</form>
<?PHP
echo "Se va a escanear los archivos en directorios y subdirectorios de: \r\n".$ruta;
$insertSQL = "TRUNCATE TABLE hacerlist_audiofiles";
$Result1 = mysql_query($insertSQL, $radiocomunicate) or die(mysql_error());
foreach(listdir($ruta) as $file){
	
if(is_readable($file) === TRUE){
$infoPath = pathinfo($file);
$ext = strtoupper($infoPath["extension"]);
if("MP3" == $ext || "WMA" == $ext){//Windows Media Audio o MP3
//echo "\r\nArchivo: ".$file."\r\n";
$au = new AudioInfo();
$info = $au->Info($file);
if(!isset($info["error"])){
$duracion = $info["playing_time"];

//He descubierto que interprete y artista lo funciona en artista, siendo interprete el indice cero, y artista el uno

//Si en ID3v2 esta el artista o titulo se usa, sino en ID3v1, pero en este ultimo algunos vienen cortados
$titulo = utf8_decode(strtoupper(@array_pop($info["tags"]["id3v2"]['title'])));//idv3v2
$artista = utf8_decode(strtoupper(@array_pop($info["tags"]["id3v2"]['artist'])));//idv3v2

if($titulo == "")
	$titulo = utf8_decode(strtoupper(@array_pop($info["tags"]["asf"]['title'])));//asf
if($artista == "")
	$artista = utf8_decode(strtoupper(@array_pop($info["tags"]["asf"]['artist'])));//asf

if($titulo == "")
	$titulo = utf8_decode(strtoupper(@array_pop($info["tags"]["id3v1"]['title'])));//idv3v1
if($artista == "")
	$artista = utf8_decode(strtoupper(@array_pop($info["tags"]["id3v1"]['artist'])));//idv3v1	
	
if($artista == "")
	$artista = utf8_decode(strtoupper(@array_pop($info["tags"]["asf"]['albumartist'])));//asf
	
}else{
	//echo "<pre>";var_dump($info);echo "</pre>";
	$error = @$info["error"];
}
/*if(isset($info->comments['genre'][1]) && strlen($info->comments['genre'][1])>0){//idv3v2
$genero = utf8_decode(strtoupper($info->comments['genre'][1]));//idv3v2
}else{
$genero = utf8_decode(strtoupper($info->comments['genre'][0]));//idv3v1
}*/
$tmp0 = explode("/",str_replace($ruta.'/','',$file));
//$genero = strtoupper(trim($tmp0[count($tmp0)-3]));//Funciona perfectamente con los tags, pero hay que hacerlo por carpeta
$genero = strtoupper(trim($tmp0[0]));
//echo $genero."\r\n";
//echo ". ";

//print_r($au->Info($file));

?><script type="text/javascript">
document.tester.ruta.value = '<?PHP echo addslashes(str_replace($ruta,"",$file));?>';
document.tester.accion.value = 'Leyendo Tags = <?PHP echo microtime_float()-$tiempoInicio;?>';
document.tester.titulo.value = '<?PHP echo $titulo;?>';
document.tester.artista.value = '<?PHP echo $artista;?>';
document.tester.duracion.value = '<?PHP echo $duracion;?>';
</script><?PHP 
if(strlen($artista)<1 || strlen($titulo)<1){
echo "\r\nArchivo: ".$file;
echo "\r\nError: <b>".print_r($info,true)."</b> - Artista: {$artista}, Titulo: {$titulo}, No se encontraron tags ID3v1 (MP3),ID3v2 (MP3), o ASF (WMA - Windows Media) - Saltando Archivo \r\n";
}
else{
/*echo "Artista: ".$artista."\r\n";
echo "Titulo: ".$titulo."\r\n";
echo "Genero: ".$genero."\r\n";
echo "Duracion: ".$duracion."\r\n";
*/
//Almacenar la informacion en bases de datos
 $insertSQL = sprintf("INSERT INTO hacerlist_audiofiles (`path`, titulo, artista, genero, longitud) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($file, "text"),
                       GetSQLValueString($titulo, "text"),
                       GetSQLValueString($artista, "text"),
                       GetSQLValueString($genero, "text"),
                       GetSQLValueString($duracion, "int"));


  $Result1 = mysql_query($insertSQL, $radiocomunicate) or die(mysql_error());
  //Fin de almacenar la informacion en base de datos
}//else de si se encontró artista y titulo en los tags
}//if de es wma o mp3
}//if de es leible el archivo
else{
if(strlen($file)>255){
echo "\r\n".'Largo de ruta mayor a 255 caracteres, su largo actual es de : '.strlen($file);
}
echo "\r\n".'No se pudo leer el archivo: '.$file."\r\n";
}
}//foreach
?>
</pre>
<br />
Todos los archivos que fue posible leer los tags fueron guardados en la base de datos, ahora se muestran informes: 
<a href="informes.php">Contiunar con Reportes</a>