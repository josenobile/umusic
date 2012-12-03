<?PHP
ob_implicit_flush(TRUE);
ob_end_flush();
set_time_limit(0);
ini_set("memory_limit","-1");   


function listdir($start_dir='.') {
echo ". ";
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
$ruta = "//zararadio/C/RADIOCOMUNICATE OK/MUSITECA/VALLENATO/BINOMIO DE ORO CANTA RAFAEL OROZCO/ARREGLADA";

//28 Diciembre de 2008, 10:30AM - 30 Diciembre de 2008, 10:30AM
//Escritor de tags
$TaggingFormat = 'UTF-8';

require_once('writer/getid3/getid3.php');
foreach(listdir($ruta) as $ruta){
$titulo = $artista = $generoID3v2 = $generoID3v1 = 'NADA';
$au = new getID3();
$info = $au->analyze($ruta);
getid3_lib::CopyTagsToComments($info);
$duracion = $info->playing_time;
//Si en ID3v1 existe titulo o artista se usa si no ID3v2, en genero si esta en ID3v2 se usa, sino ID3v1
$titulo = /*utf8_decode(*/strtoupper(html_entity_decode(html_entity_decode($info['comments_html']['title'][0])))/*)*/;//idv3v1
$artista = utf8_decode(strtoupper($info['comments_html']['artist'][0]));//idv3v1
$generoID3v2 = utf8_decode(strtoupper($info['tags']['id3v2']['genre'][0]));//idv3v2
$generoID3v1 = utf8_decode(strtoupper($info['tags']['id3v1']['genre'][0]));//idv3v1
//Cargar variables
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
$tagwriter->id3v2_tag_language = 'esl';
//$tagwriter->filename       = '/path/to/file.mp3';
$tagwriter->filename       = $ruta;
$tagwriter->tagformats     = array('id3v1','id3v2.4');//La v2.3 no la lee el explorer de XP, OJO

// set various options (optional)
$tagwriter->overwrite_tags = true;
$tagwriter->tag_encoding   = $TaggingFormat;
//$tagwriter->remove_other_tags = true;//titulo del album no!

// populate data array
$TagData = array();
$TagData['title'][]   = html_entity_decode(html_entity_decode($titulo));
$TagData['artist'][]  = 'BINOMIO DE ORO CANTA RAFAEL OROZCO';
//$TagData['album'][]   = 'Greatest Hits';
//$TagData['year'][]    = '2004';
$TagData['genre'][]   = 'VALLENATO';
//$TagData['comment'][] = 'http://www.radiocomunicate.com - Editado por HacerList';
//$TagData['track'][]   = '04/16';

$tagwriter->tag_data = $TagData;

// write tags
if ($tagwriter->WriteTags()) {
/////////
//Renombrar con formato: TITULO - ARTISTA.ext
$newFileName = dirname($ruta)."/finish/".str_replace("/",'',$titulo." - ".$artista.".".strtolower($ext));
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

////////
	//echo 'Successfully wrote tags<br>';
	if (!empty($tagwriter->warnings)) {
		echo 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
	}
} else {
	echo 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
}


//////////

$au = new getID3();
$info = NULL;
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
//echo "<a href=\"file:///".dirname($ruta)."\">Abrir Carpeta donde se encuentra el erchivo</a>";
?>
<hr color="#CC0000" />
<?PHP
}//for que recorre archivos
?>
</body>
</html>