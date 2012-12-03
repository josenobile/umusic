<?php

// ************************************************************************
// Program:   test.php
// Version:   0.5.1
// Date:      17/04/2003
// Author:    michael kamleitner (mika@ssw.co.at)
// WWW:          http://www.entropy.at/forum.php?action=thread&t_id=15
//            (suggestions, bug-reports & general shouts are welcome)
// Desc:      this test-script lists all audio-files (.wav, .aif, .mp3, .ogg)
//            which reside in the ./ directory. If a file is selected,
//            it is loaded and its audio-attributes are displayed.
// Copyright: copyright 2003 michael kamleitner
//
//            This file is part of classAudioFile.
//
//            classAudioFile is free software; you can redistribute it and/or modify
//            it under the terms of the GNU General Public License as published by
//            the Free Software Foundation; either version 2 of the License, or
//            (at your option) any later version.
//
//            classAudioFile is distributed in the hope that it will be useful,
//            but WITHOUT ANY WARRANTY; without even the implied warranty of
//            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//            GNU General Public License for more details.
//
//            You should have received a copy of the GNU General Public License
//            along with classAudioFile; if not, write to the Free Software
//            Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// ************************************************************************
 ob_implicit_flush(1);
set_time_limit(0);
ini_set("memory_limit","-1");   
    require ('classAudioFile.php');


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
			   $inicio = microtime(true);
			   $i=0;
			  // echo '<pre>';
foreach($files as $file){
$i++;
        $AF = new AudioFile;
        $AF->loadFile($file);
$AF->printSampleInfo();

}
//echo '</pre>';
?> 