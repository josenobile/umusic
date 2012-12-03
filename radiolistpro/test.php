<?PHP
   /* This code is released under the GNU LGPL. Go read it over here:
	*
	* http://www.gnu.org/copyleft/lesser.txt 
	*/
	ob_start("ob_gzhandler");
include_once("id3v2.php");
$file = '//Zararadio/c/RADIOCOMUNICATE.COM/MUSITECA';
if (!isset($file)) $file="";
$mp3 = new id3v2;
$ini=$mp3->myMicrotime();
if (is_dir($file)){
$mp3->myReaddir($file);}
else{
$mp3->GetInfo($file);
$mp3->ShowInfo();
}
$end=$mp3->myMicrotime();
$donetime=$end-$ini;
echo "<br>All done in ".$donetime." seconds<br>";
?>