<?PHP
//28 Diciembre de 2008, 10:30AM - 2:00PM
//Escritor de tags
require_once('id3/getid3/getid3.php');
require_once('id3/getid3/write.id3v1.php');
require_once('id3/getid3/write.id3v2.php');
require_once('id3/getid3/module.tag.id3v1.php');
require_once('id3/demos/demo.audioinfo.class.php'); 

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
if(is_writable($ruta) !== TRUE){
die("ERROR: <br />\r\n EL ARCHIVO: ".$ruta." <br />\r\n NO SE PUEDE ESCRIBIR O NO EXISTE");
}
//Escribir los tags del archivo
$tw = new getid3_write_id3v1($ruta);
$tw->remove();//Quito los que tags que hallan
$tw->title      = $titulo;
$tw->artist     = $artista;
//$tw->album      = 'album';
//$tw->year       = 2005;
unset($tw->genre);//El obtiene el nombre
$tw->genre_id = $genero_ID3v1;//Le envio la Id
$tw->comment    = 'Editado por HacerList - www.radiocomunicate.com';
// $tw->track      =  11;
$tw->write();

//Ahora los ID3v2

echo "Se escribió los tags ID3v1, ahora se van a escribir los tags ID3v2: <br />";
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

$tw2->tag_data = $TagData;
$tw2->write();
print_r($tw2);
//Actualizar los tags de la base de datos


//Leer los tags y verificar que se guardaron bien


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
$au = new AudioInfo();
$info = $au->Info($ruta);
$duracion = $info->playing_time;
//Si en ID3v1 existe titulo o artista se usa si no ID3v2, en genero si esta en ID3v2 se usa, sino ID3v1
$titulo = utf8_decode(strtoupper($info->comments['title'][0]));//idv3v1
$artista = utf8_decode(strtoupper($info->comments['artist'][0]));//idv3v1
$generoID3v2 = utf8_decode(strtoupper($info->comments['genre'][1]));//idv3v2
$generoID3v1 = utf8_decode(strtoupper($info->comments['genre'][0]));//idv3v1
echo "\r\n<br />Los nuevos tags quedaron así:\r\n<br />";
echo "Titulo: ".$titulo."\r\n<br />";
echo "Artista: ".$artista."\r\n<br />";
echo "Genero ID3v1: ".$generoID3v1."\r\n<br />";
echo "Genero ID3v2: ".$generoID3v2."\r\n<br />";

?>


</body>
</html>
<?PHP
//1:00AM, 30 (29) DICIEMBRE DE 2008
/*
ME RINDÓ, ESA BERRACA CLASE write.id3v2.php esta podrida, la lanzaron asquerosamente llena de errores, gaste 4 horas intentando arreglarla, logré quitar todas las warnings, notices, fatal errors, metodo no encontrados, argumentos faltantes, etc, infinidad de cosas que yo nose que pasó, si es que estaban borrachos o que, pero en sintesis, logré escribir bien los tags id3v1, logré borrar los tags id3v2, pero la jedionda clase no los escribe, creo que ya probé todas TODAAAAAAS las clases que sirven para escribir los tags id3v2, pero ninguna, y creo que esta getid3 es las más completa, y definitivamente no funciona, es demasiado complejo esos tags id3v2, con ese monton de frames, de longitud variable, los padding, la sicronización y un monton de carajadas que hace que eso sea horrible, espantoso, así que quien algún día mejore esta clase, le comento que se que falta una bobadita, por que ya lo más dificil lo hice, que fue encontrar más de 500 errores de programación, de lógica, de escritura, de todas las carajadas posibles y por haber, de una ves le cuento que el temporal que crea que parece que ya lo hizo, no lo ha hecho, simplemente borra los tags id3v2, sin más dejo estos archivos a quien algún día trabaje acá, y se de cuenta, que yo para tener tan solo 19 años, no haber completado ni 20 creditos en la universidad del valle, soy un verdadero genio, lastima, que no supe usar a favor mio tanta inteligencia y me fui a hacer autokill a cartagena, =-(


*/
?>
