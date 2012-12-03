<?php 
require_once('../../Connections/radiocomunicate.php'); ?><?php
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
function seconds2FormatTime($second3){
       
        //print $second3;
        if ($second3==0)
        {
            $h3=0;
        }
        else
        {
            $h3=floor($second3/3600);//find total hours
        }
           
        $remSecond=$second3-($h3*3600);//get remaining seconds
        if ($remSecond==0)
        {
            $m3=0;
        }
        else
        {
            $m3=floor($remSecond/60);// for finding remaining  minutes
        }
           
        $s3=$remSecond-(60*$m3);
       
        if($h3==0)//formating result.
        {
            $h3="00";
        }
        if($m3==0)
        {
            $m3="00";
        }
        if($s3==0)
        {
            $s3="00";
        }
           
        return "$h3:$m3:$s3";
}//Function seconds2FormatTime

$postartista_pistas = "-1";
if (isset($_GET['ARTISTA'])) {
  $postartista_pistas = $_GET['ARTISTA'];
}
mysql_select_db($database_radiocomunicate, $radiocomunicate);
$query_pistas = sprintf("SELECT * FROM hacerlist_audiofiles WHERE hacerlist_audiofiles.artista = %s ORDER BY artista DESC", GetSQLValueString($postartista_pistas, "text"));
$pistas = mysql_query($query_pistas, $radiocomunicate) or die(mysql_error());
$row_pistas = mysql_fetch_assoc($pistas);
$totalRows_pistas = mysql_num_rows($pistas);

if($totalRows_pistas<1){
echo "Ups! No encontre pistas del artista selccionado: ".GetSQLValueString($postartista_pistas, "text");
die("<br />\r\n La consulta ejecutada que no produjo resultados: <br />".$query_pistas);
}


$generos_ID3v1 = array (
            0    => 'Blues',
            1    => 'Classic Rock',
            2    => 'Country',
            3    => 'Dance',
            4    => 'Disco',
            5    => 'Funk',
            6    => 'Grunge',
            7    => 'Hip-Hop',
            8    => 'Jazz',
            9    => 'Metal',
            10   => 'New Age',
            11   => 'Oldies',
            12   => 'Other',
            13   => 'Pop',
            14   => 'R&B',
            15   => 'Rap',
            16   => 'Reggae',
            17   => 'Rock',
            18   => 'Techno',
            19   => 'Industrial',
            20   => 'Alternative',
            21   => 'Ska',
            22   => 'Death Metal',
            23   => 'Pranks',
            24   => 'Soundtrack',
            25   => 'Euro-Techno',
            26   => 'Ambient',
            27   => 'Trip-Hop',
            28   => 'Vocal',
            29   => 'Jazz+Funk',
            30   => 'Fusion',
            31   => 'Trance',
            32   => 'Classical',
            33   => 'Instrumental',
            34   => 'Acid',
            35   => 'House',
            36   => 'Game',
            37   => 'Sound Clip',
            38   => 'Gospel',
            39   => 'Noise',
            40   => 'Alt. Rock',
            41   => 'Bass',
            42   => 'Soul',
            43   => 'Punk',
            44   => 'Space',
            45   => 'Meditative',
            46   => 'Instrumental Pop',
            47   => 'Instrumental Rock',
            48   => 'Ethnic',
            49   => 'Gothic',
            50   => 'Darkwave',
            51   => 'Techno-Industrial',
            52   => 'Electronic',
            53   => 'Pop-Folk',
            54   => 'Eurodance',
            55   => 'Dream',
            56   => 'Southern Rock',
            57   => 'Comedy',
            58   => 'Cult',
            59   => 'Gangsta Rap',
            60   => 'Top 40',
            61   => 'Christian Rap',
            62   => 'Pop/Funk',
            63   => 'Jungle',
            64   => 'Native American',
            65   => 'Cabaret',
            66   => 'New Wave',
            67   => 'Psychedelic',
            68   => 'Rave',
            69   => 'Showtunes',
            70   => 'Trailer',
            71   => 'Lo-Fi',
            72   => 'Tribal',
            73   => 'Acid Punk',
            74   => 'Acid Jazz',
            75   => 'Polka',
            76   => 'Retro',
            77   => 'Musical',
            78   => 'Rock & Roll',
            79   => 'Hard Rock',
            80   => 'Folk',
            81   => 'Folk/Rock',
            82   => 'National Folk',
            83   => 'Swing',
            84   => 'Fast-Fusion',
            85   => 'Bebob',
            86   => 'Latin',
            87   => 'Revival',
            88   => 'Celtic',
            89   => 'Bluegrass',
            90   => 'Avantgarde',
            91   => 'Gothic Rock',
            92   => 'Progressive Rock',
            93   => 'Psychedelic Rock',
            94   => 'Symphonic Rock',
            95   => 'Slow Rock',
            96   => 'Big Band',
            97   => 'Chorus',
            98   => 'Easy Listening',
            99   => 'Acoustic',
            100  => 'Humour',
            101  => 'Speech',
            102  => 'Chanson',
            103  => 'Opera',
            104  => 'Chamber Music',
            105  => 'Sonata',
            106  => 'Symphony',
            107  => 'Booty Bass',
            108  => 'Primus',
            109  => 'Porn Groove',
            110  => 'Satire',
            111  => 'Slow Jam',
            112  => 'Club',
            113  => 'Tango',
            114  => 'Samba',
            115  => 'Folklore',
            116  => 'Ballad',
            117  => 'Power Ballad',
            118  => 'Rhythmic Soul',
            119  => 'Freestyle',
            120  => 'Duet',
            121  => 'Punk Rock',
            122  => 'Drum Solo',
            123  => 'A Cappella',
            124  => 'Euro-House',
            125  => 'Dance Hall',
            126  => 'Goa',
            127  => 'Drum & Bass',
            128  => 'Club-House',
            129  => 'Hardcore',
            130  => 'Terror',
            131  => 'Indie',
            132  => 'BritPop',
            133  => 'Negerpunk',
            134  => 'Polsk Punk',
            135  => 'Beat',
            136  => 'Christian Gangsta Rap',
            137  => 'Heavy Metal',
            138  => 'Black Metal',
            139  => 'Crossover',
            140  => 'Contemporary Christian',
            141  => 'Christian Rock',
            142  => 'Merengue',
            143  => 'Salsa',
            144  => 'Trash Metal',
            145  => 'Anime',
            146  => 'JPop',
            147  => 'Synthpop',

            255  => 'Unknown',

            'CR' => 'Cover',
            'RX' => 'Remix'
        );


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Canci&oacute;n por Artista</title>
</head>

<body>

<?PHP
$musica = array();
?>
<?php do { ?>
<?PHP
$musica[] = array('RUTA'=>$row_pistas['path'],'TITULO'=>$row_pistas['titulo'],'ARTISTA'=>$row_pistas['artista'],'GENERO'=>$row_pistas['genero'],'LONGITUD'=>$row_pistas['longitud']);
?>
  <?php } while ($row_pistas = mysql_fetch_assoc($pistas)); ?>


<table cellpadding="1" cellspacing="1" border="0" >
<tr><td>#</td><td>ARTISTA</td><td>TITULO</td><td>GENERO TAG ID3v1</td><td>GENERO TAG ID3v2</td><td>GENERO CARPETA</td><td>PLAY</td><td>LONGITUD</td><td>GRABAR</td></tr>
<?PHP
require_once('id3/getid3/getid3.php');
require_once('id3/demos/demo.audioinfo.class.php'); 
$i=1;
foreach($musica as $pista){
$file = $pista['RUTA'];
$au = new AudioInfo();
$info = $au->Info($file);
$duracion = $info->playing_time;
$extension = pathinfo($file);
$extension = strtoupper($extension['extension']);
//Si en ID3v1 existe titulo o artista se usa si no ID3v2, en genero si esta en ID3v2 se usa, sino ID3v1
$titulo = utf8_decode(strtoupper($info->comments['title'][0]));//idv3v1
$artista = utf8_decode(strtoupper($info->comments['artist'][0]));//idv3v1
$genero_ID3v2 = utf8_decode(strtoupper($info->comments['genre'][1]));//idv3v2
$genero_ID3v1 = utf8_decode(strtoupper($info->comments['genre'][0]));//idv3v1
if(strlen($genero_ID3v2)<1){
$genero_ID3v2 = $genero_ID3v1;
}
?>
<tr>
<td><?PHP echo $i++;?></td>
<td>
<form action="id3Editor.php" enctype="application/x-www-form-urlencoded" method="post" target="_blank">
<input type="text" name="ARTISTA" value="<?PHP echo $pista['ARTISTA'];?>" size="30" />
</td>

<td><input type="text" name="TITULO" value="<?PHP echo $pista['TITULO'];?>" size="30" /></td>
<td><?php echo $genero_ID3v1;?><?PHP /*<select name="GENERO_ID3v1" disabled="disabled">
<?PHP
foreach($generos_ID3v1 as $genID => $generoID3v1){
$selected = '';
if(strtoupper($generoID3v1) == $genero_ID3v1){
$selected = ' selected="selected"';
}
?>
<option value="<?PHP echo $genID;?>"<?PHP echo $selected;?>><?PHP echo $generoID3v1;?></option>
<?PHP
}//foreach
?>
</select>*/?>
</td>
<td><input type="text" name="GENERO_ID3v2" value="<?PHP echo $genero_ID3v2;?>" size="30" /></td>
<td><?PHP
$unaInfo = pathinfo($pista['RUTA']);
$dirPista = $unaInfo['dirname'];
?><a href="file:///<?PHP echo $dirPista;?>" target="_blank"><?PHP echo $pista['GENERO'];?></a></td>
<td><object id=Player height=44 width=98
classid=CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6>
    <param name="URL" value="<?PHP echo $pista['RUTA'];?>" />
    <param name="FileName" value="<?PHP echo $pista['RUTA'];?>" />
    <param name="TransparentAtStart" value="1" />
    <param name="AutoStart" value="FALSE" />
    <param name="AnimationatStart" value="true" />
    <param name="ShowControls" value="true" />
    <param name="autoSize" value="true" />
    <param name="displaySize" value="0" />
    <embed  src="<?PHP echo $pista['RUTA'];?>" width=98 height=44 autostart="FALSE" align="bottom" type="application/x-mplayer2" name="wmPlayer" uimode="none" url="<?PHP echo $pista['RUTA'];?>" filename="<?PHP echo $pista['RUTA'];?>" transparentatstart="1" animationatstart="true" showcontrols="true" autosize="true" displaysize="0"></embed>
  </object></td>
  <td><?PHP echo seconds2FormatTime($pista['LONGITUD']);?></td>
<td><input type="hidden" name="ruta" value="<?PHP echo $pista['RUTA'];?>" />
<?PHP
if($extension == 'MP3'){
?>
<input type="submit" value="Grabar Tags" />
<?PHP
}else{
echo $extension;
}
?>
</form></td>
</tr>
<?PHP
}//foreach
?>
</table>
<?php
mysql_free_result($pistas);
?>
</body>
</html>