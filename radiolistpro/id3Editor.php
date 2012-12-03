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

//28 Diciembre de 2008, 10:30AM - 30 Diciembre de 2008, 10:30AM
//Escritor de tags
$TaggingFormat = 'UTF-8';

require_once('writer/getid3/getid3.php');

//Cargar variables
$artista = trim($_POST['ARTISTA']);
$titulo = trim($_POST['TITULO']);
$genero_ID3v1 = trim($_POST['GENERO_ID3v1']);
$genero_ID3v2 = trim($_POST['GENERO_ID3v2']);
$ruta = trim($_POST['ruta']);
$info = pathinfo($ruta);
$ext = $info['extension'];
if(strtoupper($ext) != 'MP3'){
die("La extension: ".$ext." no es soportada, solo .MP3");
}
//Verificar que el archivo se escribible
if(file_exists($ruta) !== TRUE || is_readable($ruta)!== TRUE || is_writable($ruta) !== TRUE){
die("ERROR: <br />\r\n EL ARCHIVO: ".$ruta." <br />\r\n NO SE PUEDE ESCRIBIR O NO EXISTE");
}
else{
/*echo "Se verifico que existe, se puede leer y escribir: <br />".$ruta."<br />";
var_dump(file_exists($ruta));var_dump(is_readable($ruta));var_dump(is_writable($ruta));*/
}
//Escribir los tags del archivo

///////////////////////////
/*
$tw2 = new getid3_write_id3v2($ruta);
//$tw2->filename = $ruta;
$tw2->remove();
$tw2->id3v2_tag_language = 'esl';
$TagData['title'][]   = $titulo;
$TagData['artist'][]  = $artista;
//$TagData['album'][]   = 'Greatest Hits';
//$TagData['year'][]    = '2004';
$TagData['genre'][]   = $genero_ID3v2;
$TagData['comment'][] = 'Editado por HacerList - www.radiocomunicate.com';
//$TagData['track'][]   = '04/16';
////////////////////////////
$tw2->tag_data = $TagData;
$tw2->write();
*/
////////////
$getID3 = new getID3;
$getID3->setOption(array('encoding'=>$TaggingFormat));

require_once('writer/getid3/write.php');
// Initialize getID3 tag-writing module
$tagwriter = new getid3_writetags;
//$tagwriter->filename       = '/path/to/file.mp3';
$tagwriter->filename       = $ruta;
$tagwriter->tagformats     = array('id3v1', 'id3v2.4');//La v2.3 no la lee el explorer de XP, OJO

// set various options (optional)
$tagwriter->overwrite_tags = true;
$tagwriter->tag_encoding   = $TaggingFormat;
$tagwriter->remove_other_tags = true;

// populate data array
$TagData['title'][]   = $titulo;
$TagData['artist'][]  = $artista;
//$TagData['album'][]   = 'Greatest Hits';
//$TagData['year'][]    = '2004';
$TagData['genre'][]   = $genero_ID3v2;
$TagData['comment'][] = 'http://www.radiocomunicate.com - Editado por HacerList';
//$TagData['track'][]   = '04/16';

$tagwriter->tag_data = $TagData;

// write tags
if ($tagwriter->WriteTags()) {
/////////
//Actualizar tags en base de datos
//Renombrar con formato: TITULO - ARTISTA.ext
$newFileName = dirname($ruta)."/".str_replace("/",'',$titulo." - ".$artista.".".strtolower($ext));
if($ruta!=$newFileName && copy($ruta,$newFileName) && unlink($ruta)){
echo "El archivo fue renombrado de: <br />\r\n";
echo $ruta."<br />a:";
echo $newFileName."<br />\r\n";
$ruta = $newFileName;
}elseif($ruta!=$newFileName){
echo "El archivo NO PUDO SER renombrado de a: <br />\r\n";
echo $ruta."<br />";
echo $newFileName."<br />\r\n";
}

 /*$updateSQL = sprintf("UPDATE hacerlist_audiofiles SET titulo=%s, artista=%s, genero=%s, path=%s WHERE `path`=%s",
                       GetSQLValueString($titulo, "text"),
                       GetSQLValueString($artista, "text"),
                       GetSQLValueString($genero_ID3v2, "text"),
					   GetSQLValueString($newFileName, "text"),
                       GetSQLValueString($_POST['ruta'], "text"));*/
					   
					    $updateSQL = sprintf("UPDATE hacerlist_audiofiles SET titulo=%s, artista=%s, path=%s WHERE `path`=%s",
                       GetSQLValueString($titulo, "text"),
                       GetSQLValueString($artista, "text"),
					   GetSQLValueString($newFileName, "text"),
                       GetSQLValueString($_POST['ruta'], "text"));

  mysql_select_db($database_radiocomunicate, $radiocomunicate);
  $Result1 = mysql_query($updateSQL, $radiocomunicate) or die(mysql_error());
 // echo $updateSQL;
////////
	//echo 'Successfully wrote tags<br>';
	if (!empty($tagwriter->warnings)) {
		echo 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
	}
} else {
	echo 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
}


//////////



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>TAGS ID3 WRITER</title>
</head>

<body>
<?php
echo "Se recibieron los siguientes datos: <br />\r\n";
echo '<pre>';print_r($_POST);echo '</pre>';
$au = new getID3();
$info = $au->analyze($ruta);
getid3_lib::CopyTagsToComments($info);
$duracion = $info->playing_time;
//Si en ID3v1 existe titulo o artista se usa si no ID3v2, en genero si esta en ID3v2 se usa, sino ID3v1
$titulo = utf8_decode(strtoupper($info['comments_html']['title'][0]));//idv3v1
$artista = utf8_decode(strtoupper($info['comments_html']['artist'][0]));//idv3v1
$generoID3v2 = utf8_decode(strtoupper($info['tags']['id3v2']['genre'][0]));//idv3v2
$generoID3v1 = utf8_decode(strtoupper($info['tags']['id3v1']['genre'][0]));//idv3v1
echo "\r\n<br />Los nuevos tags quedaron así:\r\n<br />";
echo "Titulo: ".$titulo."\r\n<br />";
echo "Artista: ".$artista."\r\n<br />";
echo "Genero ID3v1: ".$generoID3v1."\r\n<br />";
echo "Genero ID3v2: ".$generoID3v2."\r\n<br />";
echo "<a href=\"file:///".dirname($ruta)."\">Abrir Carpeta donde se encuentra el erchivo</a>";
?>
<form name="form" action="<?php echo $editFormAction; ?>" method="POST" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="titulo" value="" />
<input type="hidden" name="path" value="" />
<input type="hidden" name="artista" value="" />
<input type="hidden" name="genero" value="" />
<input type="hidden" name="" value="" />
<input type="hidden" name="" value="" />
<input type="hidden" name="" value="" />
<input type="hidden" name="" value="" />
<input type="hidden" name="MM_update" value="form" />

</form>
</body>
</html>