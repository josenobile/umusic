<?php
ob_implicit_flush(1);
set_time_limit(0);
ini_set("memory_limit","-1");
    require('error.inc.php');
    require('id3.class.php');
	$ruta = 'C:/wamp/radiocomunicate.com/MUSITECA RADIOCOMUNICATE/';
//$ruta = '//Zararadio/c/RADIOCOMUNICATE.COM/MUSITECA/';
Function listdir($start_dir='.') {

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
$inicio = microtime(true);
$files = listdir($ruta);
$fin = microtime(true);
$total = $fin-$inicio;
echo $total.' segundos escaneando los directorios';
$mp3 = array();
         echo('<a href= "'.$nome_arq.'">'.$nome_arq.'</a><br>');
         echo('<table border=1>
               <tr>
			    <td>#</td>
			   <td width="100px">Archivo</td>
                  <td><strong>Artista</strong></td>
                  <td><strong>Titulo</strong></font></div></td>
                  <td><strong>Album/Ano</strong></font></div></td>
                  <td><strong>G&ecirc;nero</strong></font></div></td>
                  <td><strong>Coment&aacute;rios</strong></font></div></td>
               </tr>');
			   $inicio = microtime(true);
			   $i=0;
foreach($files as $file){
$i++;
    $nome_arq  = $file;
     $myId3 = new ID3($nome_arq);
     if ($myId3->getInfo()){
        echo ('
               <tr>
			   <td>'.$i.'</td>
			   <td>'.'<a href= "'.$nome_arq.'">'.$nome_arq.'</a>'.'</td>
                  <td>'. $myId3->getArtist() . '&nbsp</td>
                  <td>'. $myId3->getTitle()  . '&nbsp</td>
                  <td>'. $myId3->getAlbum()  . '/'.$myId3->getYear().'&nbsp</td>
                  <td>'. $myId3->getGender() . '&nbsp</td>
                  <td>'. $myId3->tags['COMM']. '&nbsp</td>
               </tr>
 ');        
       }else{
        //echo($errors[$myId3->last_error_num]);
   }
   }
    echo ('  </table>');
	$fin = microtime(true);
$total = $fin-$inicio;
echo $total.' leyendo los tags de los MP3';
?>