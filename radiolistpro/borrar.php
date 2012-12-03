<?PHP
ob_implicit_flush(TRUE);
ob_end_flush();
set_time_limit(0);
ini_set("memory_limit","-1");   

require_once('id3/getid3/getid3.php');
require_once('id3/demos/demo.audioinfo.class.php'); 
Function listdir($start_dir='.') {
echo ". ";
  $files = array();
  if (is_dir($start_dir)) {
    $fh = opendir($start_dir) or die('Error abriendo directorio: '.$start_dir);
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
$ruta = "//Zararadio/C/RADIOCOMUNICATE OK/MUSITECA/ELECTROBIT";
$rutaOK = '//Zararadio/C/RADIOCOMUNICATE OK/ELECTRONICA_OK';
$rutaNOOK = '//Zararadio/C/RADIOCOMUNICATE OK/MUSITECA/ELECTRONICA_NOTAGS';
require_once('id3/getid3/getid3.php');
require_once('id3/demos/demo.audioinfo.class.php'); 
$archivos = listdir($ruta);

$mainLista = array();
$mainListaNO = array();
echo "LEER TAGS: ";
foreach($archivos as $file){
$tmp1 = pathinfo($file);
if(strtoupper($tmp1['extension']) == 'MP3' || strtoupper($tmp1['extension']) == 'WMA'){//wma o mp3
/*$getid3 = new getID3;
$getid3->encoding = 'UTF-8';
    $getid3->Analyze($file);*/
    // Show audio bitrate and length
  $artist = $title = '';
            //leer artista y titulo	
			/*$title = $getid3->info['id3v1']['title'][0];			
			$artist = $getid3->info['id3v1']['artist'][0];	
			if ((strlen($title)<1 || strlen($artist)<1) && @$getid3->info['tags']) {
			echo "<pre>";print_r(@$getid3->info['tags']);echo "</pre>";
                foreach ($getid3->info['comments'] as $tag => $tag_info) {
                    if (@$getid3->info['comments'][$tag]['artist'] || @$getid3->info['comments'][$tag]['title']) {
                        $artist = @implode('_', @$getid3->info['comments'][$tag]['artist']);
                        $title  = @implode('_', @$getid3->info['comments'][$tag]['title']);
						
                     }
                }
            }*/
		$au = new AudioInfo();
$info = $au->Info($file);
$duracion = $info->playing_time;
//Si en ID3v1 existe titulo o artista se usa si no ID3v2, en genero si esta en ID3v2 se usa, sino ID3v1
$title = utf8_decode(strtoupper($info->comments['title'][0]));//idv3v1
$artist = utf8_decode(strtoupper($info->comments['artist'][0]));//idv3v1





			//echo '<pre>';print_r($mp3->id3v1Info);echo '</pre>';
	//echo '<pre>';print_r($mp3->id3v2Info);echo '</pre>';
		//	echo "Artista: {$artist}<br />\r\n Titulo: {$title}<br />\r\nFile: {$file}<br />\r\n";
			
			//fin de leer artista y titulo
			if(strlen($artist)<1 || strlen($title)<1){
			$mainListaNO[$artist][] = array("FILENAME"=>$file,"NOMBRE"=>$tmp1['basename']);
			}else{
			$mainLista[$artist][] = array("TITLE"=>$title,"ARTIST"=>$artist,"FILENAME"=>$file,"EXT"=>$tmp1['extension']);
			}
			echo ". ";
}//if de archivo
}//foreach

foreach($mainLista as $artista){

//COPIAR TODAS LAS CANCIONES AL DIRECTORIO DE SU ARTISTA, CON EL NOMBRE ARREGLADO
foreach($artista as $pista){
$crear = $rutaOK."/".strtoupper($pista["ARTIST"])."/";
if(!file_exists($crear)){
echo "CREAR DIRECTORIO: ".$crear."<br />\r\n";
mkdir($crear,null,true);//
}
$newFile = strtoupper($pista["TITLE"]." - ".$pista["ARTIST"].".".$pista["EXT"]);
copy($pista["FILENAME"],$crear."".$newFile);
echo $pista["FILENAME"]." -> ".$crear."".$newFile."<br />\r\n";
}//foreach de artista
//FIN DE COPIAR
}//foreach

foreach($mainListaNO as $artista){

//COPIAR TODAS LAS CANCIONES AL DIRECTORIO DE SU ARTISTA, CON EL NOMBRE ARREGLADO
foreach($artista as $pista){
copy($pista["FILENAME"],$rutaNOOK."/".$pista["NOMBRE"]);
echo $pista["FILENAME"]." -> ".$rutaNOOK."/".$pista["NOMBRE"]."<br />\r\n";
}//foreach de artista
//FIN DE COPIAR
}//foreach



/*echo microtime(true)."<br />open dir: ";
$fh = opendir('//Zararadio/c/RADIOCOMUNICATE.COM/MUSITECA/ELECTROBIT');
echo microtime(true)."<br />Readdir:";
 while (($file = readdir($fh)) !== false) {
 echo ". ";
 }
 echo microtime(true)."<br />Close dir: ";
 closedir($fh);
  echo microtime(true)."<br />is_dir(?): ";
 is_dir("//Zararadio/c/RADIOCOMUNICATE.COM/MUSITECA/ELECTROBIT");
  echo microtime(true)."<br />scandir: ";
  scandir("//Zararadio/c/RADIOCOMUNICATE.COM/MUSITECA/ELECTROBIT");
  echo microtime(true)."<br />fin scandir";
exit;*/

/*
phpinfo();exit;
echo "holaaaaaaaaaaaaaaaaaa";
sleep(5);
echo "CHAOOOOOOOOOOOOOOOOOOOOOOOOOOOOO";
*/


?>