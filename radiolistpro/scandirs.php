<?PHP
ob_implicit_flush(1);
set_time_limit(0);
ini_set("memory_limit","-1");


/////////////////////////////////////////////////////
// This function parse ID3 tag from MP3 file. It's quite fast.
// syntax mp3_id(filename)
// function will return -1 if file not exists or no frame cynch found at the beginning of file. i realized that some songs downloaded thru gnutella have about four lines of text info at the beginning. it seepms players can handle. so i will implement it later.
// variable bitrates are not yet implemented, as they are quite slow to check. you can find them to read lot of first frames and check their bitrates. If theyre not the same, its variable bitrate. and also you then cannot compute real song lenght, unless you will scan the whole file for frames and compute its lenght... (at least what i read)
// there is second version of ID3 tag which is tagged at the beginning of the file and its quite large. you can learnt more about at http://www.id3.org/. i dont finding this so interesting. there are too good things on new version: you can write more than 30 chars at field and the tag is on the beginning of file. there are so many fields in v2 that i found really unusefull in many case. while it seems that id3v2 will still write tag v1 at the end, i can see no reason why to implement it, cos it is really 'slow' to parse all these informations.

// You can use 'genres' to determine what means the 'genreid' number. if you think you will not need it, delete it to. And also we need to specify all variables for mp3 header.

 $genres=Array("Not Set","Classic Rock","Country","Dance","Disco","Funk","Grunge","Hip-Hop","Jazz","Metal","New Age","Oldies","Other","Pop","R&B","Rap","Reggae","Rock","Techno","Industrial","Alternative","Ska","Death Metal","Pranks","Soundtrack","Euro-Techno","Ambient","Trip-Hop","Vocal","Jazz+Funk","Fusion","Trance","Classical","Instrumental","Acid","House","Game","Sound Clip","Gospel","Noise","AlternRock","Bass","Soul","Punk","Space","Meditative","Instrumental Pop","Instrumental Rock","Ethnic","Gothic","Darkwave","Techno-Industrial","Electronic","Pop-Folk","Eurodance","Dream","Southern Rock","Comedy","Cult","Gangsta","Top 40","Christian Rap","Pop/Funk","Jungle","Native American","Cabaret","New Wave","Psychadelic","Rave","Showtunes","Trailer","Lo-Fi","Tribal","Acid Punk","Acid Jazz","Polka","Retro","Musical","Rock & Roll","Hard Rock","Folk","Folk/Rock","National Folk","Swing","Bebob","Latin","Revival","Celtic","Bluegrass","Avantgarde","Gothic Rock","Progressive Rock","Psychedelic Rock","Symphonic Rock","Slow Rock","Big Band","Chorus","Easy Listening","Acoustic","Humour","Speech","Chanson","Opera","Chamber Music","Sonata","Symphony","Booty Bass","Primus","Porn Groove","Satire","Slow Jam","Club","Tango","Samba","Folklore");
 $version=Array("00"=>2.5, "10"=>2, "11"=>1);
 $layer  =Array("01"=>3, "10"=>2, "11"=>1);
 $crc=Array("Yes", "No");
 $bitrate["0001"]=Array(32,32,32,32,8,8);
 $bitrate["0010"]=Array(64,48,40,48,16,16);
 $bitrate["0011"]=Array(96,56,48,56,24,24);
 $bitrate["0100"]=Array(128,64,56,64,32,32);
 $bitrate["0101"]=Array(160,80,64,80,40,40);
 $bitrate["0110"]=Array(192,96,80,96,48,48);
 $bitrate["0111"]=Array(224,112,96,112,56,56);
 $bitrate["1000"]=Array(256,128,112,128,64,64);
 $bitrate["1001"]=Array(288,160,128,144,80,80);
 $bitrate["1010"]=Array(320,192,160,160,96,96);
 $bitrate["1011"]=Array(352,224,192,176,112,112);
 $bitrate["1100"]=Array(384,256,224,192,128,128);
 $bitrate["1101"]=Array(416,320,256,224,144,144);
 $bitrate["1110"]=Array(448,384,320,256,160,160);
 $bitindex=Array("1111"=>"0","1110"=>"1","1101"=>"2",
"1011"=>"3","1010"=>"4","1001"=>"5","0011"=>"3","0010"=>4,"0001"=>"5");
 $freq["00"]=Array("11"=>44100,"10"=>22050,"00"=>11025);
 $freq["01"]=Array("11"=>48000,"10"=>24000,"00"=>12000);
 $freq["10"]=Array("11"=>32000,"10"=>16000,"00"=>8000);
 $mode=Array("00"=>"Stereo","01"=>"Joint stereo","10"=>"Dual channel","11"=>"Mono");
 $copy=Array("No","Yes");

// here goes the function

 function mp3_id($file) {
   global $version, $layer, $crc, $bitrate, $bitindex, $freq, $mode, $copy, $genres;
   if(!$f=@fopen($file, "r")) { return -1; break; } else {

// read first 4 bytes from file and determine if it is wave file if so, header begins five bytes after word 'data'

     $tmp=fread($f,4);
     if($tmp=="RIFF") {
       $idtag["ftype"]="Wave";
       fseek($f, 0);
       $tmp=fread($f,128);
       $x=StrPos($tmp, "data");
       fseek($f, $x+8);
       $tmp=fread($f,4);
     }

// now convert those four bytes to BIN. maybe it can be faster and easier. dunno how yet. help?

     for($y=0;$y<4;$y++) {
       $x=decbin(ord($tmp[$y]));
       for($i=0;$i<(8-StrLen($x));$i++) {$x.="0";}
       $bajt.=$x;
     }

// every mp3 framesynch begins with eleven ones, lets look for it. if not found continue looking for some 1024 bytes (you can search multiple for it or you can disable this, it will speed up and not many mp3 are like this. anyways its not standart)

//     if(substr($bajt,1,11)!="11111111111") {
//        return -1;
//        break;
//     }
     if(substr($bajt,1,11)!="11111111111") {
       fseek($f, 4);       
       $tmp=fread($f,2048);
         for($i=0;$i<2048;$i++){
           if(ord($tmp[$i])==255 && substr(decbin(ord($tmp[$i+1])),0,3)=="111") {
              $tmp=substr($tmp, $i,4);
              $bajt="";
              for($y=0;$y<4;$y++) {
                $x=decbin(ord($tmp[$y]));
                for($i=0;$i<(8-StrLen($x));$i++) {$x.="0";}
                $bajt.=$x;
              }
              break;
            }
          }
     }
     if($bajt=="") {
        return -1;
        break;
     }


// now parse all the info from frame header

     $len=filesize($file);
     $idtag["version"]=$version[substr($bajt,11,2)];
     $idtag["layer"]=$layer[substr($bajt,13,2)];
     $idtag["crc"]=$crc[$bajt[15]];
     $idtag["bitrate"]=$bitrate[substr($bajt,16,4)][$bitindex[substr($bajt,11,4)]];
     $idtag["frequency"]=$freq[substr($bajt,20,2)][substr($bajt,11,2)];
     $idtag["padding"]=$copy[$bajt[22]];
     $idtag["mode"]=$mode[substr($bajt,24,2)];
     $idtag["copyright"]=$copy[$bajt[28]];
     $idtag["original"]=$copy[$bajt[29]];

// lets count lenght of the song

     if($idtag["layer"]==1) {
       $fsize=(12*($idtag["bitrate"]*1000)/$idtag["frequency"]+$idtag["padding"])*4; }
     else {
	 $denominador = $idtag["frequency"]+$idtag["padding"];
	 if($denominador<=0)$denominador=1;
       $fsize=144*(($idtag["bitrate"]*1000)/$denominador);}
	   if($fsize<=0)$fsize = filesize($file);
     $idtag["lenght"]=date("i:s",round($len/round($fsize)/38.37));

// now lets see at the end of the file for id3 tag. if exists then  parse it. if file doesnt have an id 3 tag if will return -1 in field 'tag' and if title is empty it returns file name.

     if(!$len) $len=filesize($file);
     fseek($f, $len-128);
     $tag = fread($f, 128);
     if(Substr($tag,0,3)=="TAG") {
       $idtag["file"]=$file;
       $idtag["tag"]=-1;
       $idtag["title"]=trim(Substr($tag,3,30));
       $idtag["artist"]=trim(Substr($tag,33,30));
       $idtag["album"]=trim(Substr($tag,63,30));
       $idtag["year"]=trim(Substr($tag,93,4));
       $idtag["comment"]=trim(Substr($tag,97,30));
       $idtag["genreid"]=trim(Ord(Substr($tag,127,1)));
       $idtag["genero"]=trim($idtag["genre"]);
	   $idtag["genre"]=trim($genres[$idtag["genreid"]]);
       $idtag["filesize"]=trim($len);
     } else {
       $idtag["tag"]=0;
     }

// close opened file and return results.

   if(!$idtag["title"]) {
     $idtag["title"]=Str_replace("\\","/", $file);
     $idtag["title"]=substr($idtag["title"],strrpos($idtag["title"],"/")+1, 255);
   }
   fclose($f);
   return $idtag;
   }
 }
/////////////////////////////////////////////////////////
//phpinfo();
/*$prueba = "C:/wamp/radiocomunicate.com/MUSITECA RADIOCOMUNICATE/POP-LATINO/Baila-Baila-Mix-.mp3";
if(is_writable($prueba)){
echo "es escribible: ".$prueba;
}else{
echo "NO es escribible: ".$prueba;
}
$versionId3 = id3_get_version($prueba);
$tag = id3_get_tag($prueba,ID3_V1_0);
$tag['genre'] = id3_get_genre_name($tag['genre']);
echo '<pre>';print_r($tag);//ID3_BEST
echo '</pre>';
exit;*/
$ruta = 'C:/wamp/radiocomunicate.com/MUSITECA RADIOCOMUNICATE/';
$ruta = '//Zararadio/c/RADIOCOMUNICATE.COM/MUSITECA/';
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

$files = listdir($ruta);
$mp3 = array();
echo '<pre>';
foreach($files as $file){
$infoFile = pathinfo($file);
$extension = $infoFile['extension'];
if(strtolower($extension) == 'mp3'){
$mp3[] = $file;
echo $file.": \r\n";
$tag = "NO SE PUDO LEER";
$versionId3 = id3_get_version($file);
//$tag = id3_get_tag($file,ID3_V2_3);mp3_id
$tag = mp3_id($file);
/*$tag['Genero'] = $tag['genre'];
$tag['genre'] = id3_get_genre_name($tag['genre']);//int to human readable genero*/
/*if(strlen($tag['genre'])<1){
$tag['genre'] = $genero;
}*/
$trozos = explode("/",$file);
$GENERO = $trozos[count($trozos)-3];
echo "GENERO: ".$GENERO."\r\n";
echo "TITULO: ".$tag['title']."\r\n";
echo "ARTISTA: ".$tag['artist']."\r\n";
echo "DURACION: ".$tag['lenght']."\r\n";
echo "CALIDAD: ".$tag['bitrate']."Kb/S\r\n";
echo "MODO: ".$tag['mode']."\r\n";
//print_r($tag);//ID3_BEST
}
}
echo '</pre>';