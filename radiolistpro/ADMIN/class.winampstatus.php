<?php
//#* VERSION 1.1.8 TooMuchSleepVersion //
/**************************************************************************
*                    Winamp Controler Class
*                       liscenced under gpl
*          you must have winamp and Zal's General Shell plug-in
*          the plugin can be found at:
*          http://www.winamp.com/plugins/detail.jhtml?componentId=13815
*
*            Comments, Suggestions, questions?
*           e-mail me at funyon@funyon.com
***************************************************************************/

/*
 * README:
 * The WiampControler Class is a usefull class for the purpose of
 * controling winamp. It has a forum which you can get help at:
 * http://groups.funyon.com/index.php?c=7
 * To use the class simply copy the class.winampstatus.php file into your
 * favorite include place then connect to the class using this:
 * $somevar = new winamp_control("user","pass","server1",["server2"],[port],[debug]);
 * then get the status
 * //$somvar->getstatus();
 * Getting the status is no longer needed ;)
 * ... rest of script ...
 * you must use:
 * $somvar->closefp();
 * at the end off all the files that you instanced the winampcontroler
 * in, or winamp will crash after a few times of accessing winamp.
 *
 *
 * ChangeLog:
 * Version 1.0.0   Initial release
 * Version 1.0.1   Added listitems function
 * Version 1.0.2   Added formatting to the listitems function
 * Version 1.1.0   Fixed problem with the class not being able to detect a
 *                  song if winamp was stopped.
 * Version 1.1.1   Fixed an intermetant problem with people not being able to see the
 *                  current song if winamp was playing.
 * Version 1.1.2   Changed the way Winamp_status::getstatus worked (now
 *                  you don't have to call get winamp_control::getinformation
 * Version 1.1.3   Edited listitems so that a song like 'DJ Shoe - Energetic Aggressions - Vol. 2' wouldn't
 *                  come up with an artist of 'DJ Shoe' and a title of 'Energetic Aggerssions'
 *                  it will now come up properly artist = 'DJ Shoe' title = 'Energetic Aggressions - Vol. 2''
 * Version 1.1.3.1 Oops, I messed up some stuff, forgot to add a break at the end of each case in the select clause :(
 *                  added breaks
 * Version 1.1.4   Added Controls for winamp to fade-stop, and added Volume controls.
 *                  if statments are almost working (8/27/2002)
 * Version 1.1.5   Added a function to get all the data from the socket so I wouldn't have to deal with the script
 *                  waiting for data, I could just go through an array (Which in theory is faster) (8/30/2002)
 * Version 1.1.6   Finished adding the function mentioned above and made the class use that function for all it's
 *                  socket needs.
 * Version 1.1.7   Added the findsong function, the class now allows you to find a song in the playlist.
 * Version 1.1.8   Finished up on the parseif function you can now use one if statment in the format string. (9/11/2002)
 *
 */
if(!defined('WINAMP_CLASS')){ // is the winamp class defined yet?
    define('WINAMP_CLASS',true); // define the class, so we don't include it more than once and have problems
    define('WINAMP_CONTROL_VERSION',"1.1.7");
    class winamp_control{    // start the Class!!!!
	var $fp;                // the file pointer, or the connection to the server running on winamp/
	var $server;            // the current server that the class is connected to
	var $error;             // May Not be a real error, used if the server could not be connected to or if we are not listening to anything
	var $status;            // the status
	var $TrackName;         // the current track name
	var $bitrate;           // bitrate that the track is playing at, if the track is playing
	var $channels;          // the numbers of channels that are currently playing, i.e. 1 or 2
	var $samplerate;        // the current samplerate
	var $artist;            // the current artist (may be an array)
	var $title;             // the current title of the song (may be an array)
	var $time;              // how long the song is in minutes
	var $number;            // the song number
	var $Thread;            // the thread that you connected to
	var $songs;             // an array of songs that is currently in the playlist (either formatted with the special format string or not)
	var $isplaying;         // the current song that is playing (just the song number nothing more)
	var $repeat;            // Repeat enabled??
	var $shuffle;           // Shuffle Enabled?
	var $version;           // the version of the class (also in WINAMP_CONTROL_VERSION)
	var $volumecontrol;     // if the volume control is in the plugin.
	var $shufflecontrol;    // if shuffle is in the plugin
	var $repeatcontrol;     // if repeat is in the plugin
	var $fadeoutcontrol;    // if fadeout is in the plugin
	var $debug = false;     // debug, self explainatory
	var $verbose = false;   // lots of  debug, may crash netscapeo
	var $start_time;        // time that the class started...
	var $total_time;        // total time that it took the class to run, in seconds
	var $debugbeg = '<DIV STYLE="margin: 0.5 em; padding: 0.5 em; border-width: thin; border-color: black; border-style: solid">';
	var $debugend = '</DIV>';
	
	function winamp_control($user,$pass,$firstserver,$secondserver,$port=999,$debug=false){
	    $this->start();
	    $this->debug = $debug;
	    $this->version = "1.1.2";
	    $this->timeout = 5;
	    $this->server = $firstserver;
	    if($this->debug){
		print "{$this->debugbeg}<b>Winamp_control</b><HR>";
		$serv = explode(".",$this->server);
		if(@strlen($serv[3]) == 3){
		    $hostname = gethostbyaddr($this->server);
		}else{
		    $hostname = gethostbyname($this->server);
		}
		print "Trying to connect to the server {$this->server} ($hostname)<br>\n";
	    }
	    $this->fp = @fsockopen($this->server,$port,$this->errno,$this->errstr,$this->timeout);
	    if(!$this->fp){
		if($secondserver){
		    if($this->debug){
			Print "The Server {$this->server} decided not to accept my connection<br>\n";
			$serv = explode(".",$secondserver);
			if(@strlen($serv[3]) == 3){
			    $hostname = gethostbyaddr($secondserver);
			}else{
			    $hostname = gethostbyname($secondserver);
			}
			print "Trying to connect to the server {$secondserver} ($hostname)<br>\n";
		    }
		    $this->server = $secondserver;
		    $this->fp = @fsockopen($this->server,$port,$this->errno,$this->errstr,$this->timeout);
		}
	    }
	    if (!$this->fp) {
		if($this->debug){
		    $this->error = "{$this->errstr} ({$this->errno})<br>\n";
		}else{
		    $this->error = "Not Listening to anything :(\n<br>";
		}
	    }else{
		if($this->debug){
		    print "Successfully connected to {$this->server} ($hostname), sending login info!<br>";
		}
		$this->login($user,$pass);
	    }
	    if($this->debug){print "{$this->debugend}";}
	}
	/**************************************************************
	 *
	 * Login function, called from winamp_control to make a
	 *  connection to the winamp plugin.
	 *
	 **************************************************************/
	function login($user, $pass) {
	    if($this->debug){print "{$this->debugbeg}Login<HR>";}
	    $line = "";
	    $this->listen("login: ", $line);
	    $this->say($user);
	    $this->listen("password: ", $line);
	    $this->say($pass);
	    $this->listen("=", $line);
	    $this->getstatus();
	    if($this->debug){print $this->debugend;}
	}
	/**************************************************************
	 *
	 * optional function for getting the current status of the player
	 * Retunrns the raw data that the status command gives out.
	 *
	 **************************************************************/
	function getstatus(){
	    if($this->debug){print "{$this->debugbeg}GetStatus<HR>";}
	    $data = $this->getdata("status");
	    if($this->debug){
		print "<pre>";
		print_r($string);
		print "</pre>";
	    }
	    $this->getinformation($data);
	    if($this->debug){
		print "\n";
	    }
	    $this->checkforfeatures();
	    if($this->debug){print $this->debugend;}
	}
	/**************************************************************
	 *
	 * Gets information from winamp:
	 *  the TrackName, Samplerate, number of channels, bitrate, and
	 *  Status of winamp (Playing, Blip Stopped, paused)
	 *
	 **************************************************************/
	function getinformation($data = false){
	    $j=0;
	    if($data == false){
		return false;
	    }
	    if($this->debug){
		print "{$this->debugbeg}GetInformation<HR>";
	    }
	    $string = "";
	    $i = 0;
	    foreach($data as $string){  //while(trim($string) != "+OK"){
		if($this->debug){
		    print "<br>String  is *". trim($string)."*<br>";
		    print nl2br( "\nsubstr(\$string,0,2) = *". substr($string,0,2) ."*\n" );
		}
		switch(strtoupper(substr($string,0,2))){
		 case 'MU':
		    $this->status = trim(substr($string,10));
		    if($this->debug){
			print nl2br( trim("Got Status of *{$this->status}*") ."\n" );
		    }
		    break;
		 case 'CU':
		    if($this->debug){
			print nl2br( "Trackname detected....\n" );
		    }
		    $this->TrackName =  trim(substr($string,strpos($string,".")+1));
		    break;
		 case 'RE':
		    list($junk,$this->repeat) = explode(": ",$string);
		    $this->repeat = trim($this->repeat);
		    if($this->debug){
			print nl2br( "Found repeat string... Repeat is {$this->repeat}" );
		    }
		    break;
		 case 'SH':
		    list($junk,$this->shuffle) = explode(": ",$string);
		    $this->shuffle = trim($this->shuffle);
		    if($this->debug){
			print nl2br( "Found shuffle string... Repeat is {$this->shuffle}" );
		    }
		    break;
		 case 'SA':
		    list($this->samplerate,$this->bitrate,$this->channels) = explode(",",$string);
		    list($junk, $this->samplerate) = explode(": ", $this->samplerate);
		    list($junk, $this->bitrate) = explode(": ", $this->bitrate);
		    list($junk, $this->channels) = explode(": ", $this->channels);
		    if($this->debug){
			print nl2br( "Samplerate : $this->samplerate\n" );
			print nl2br( "Bitrate : $this->bitrate\n" );
			print nl2br( "Channels : $this->channels\n" );
		    }
		    break;
		 default:
		    break;
		}
	    }
	    if(!$this->TrackName){
		if($this->debug){
		    print nl2br( "is it true that *{$this->TrackName}* is blank?\n" );
		}
		$items = $this->listitems();
		while(list($number, $song) = each($items)){
		    $j++;
		    if($this->debug){
			print "Current Song is $song<br>";
		    }
		    if(substr($song,0,1) != "*"){
			if($this->debug){
			    print "Song = $song, i = $j<br>";
			}
			continue;
		    }else{
			$item=$j-1;
			$this->TrackName = trim(substr($song,strpos($song,".")+1));
			if($this->debug){
			    print "Got the trackname of {$this->TrackName} ($string) item is $item<br>\n";
			}
			break;
		    }
		}
	    }
	    if($this->debug){
		print nl2br( "While Loop is complete....\n\n\n<br> ");
	    }
	    $this->GetArtistandTitle();
	    if($this->debug){
		print nl2br( "\nArtist = {$this->artist}\nTitle = {$this->title}\n");
	    }
	    if($this->debug){print $this->debugend;}
	}
	/**************************************************************
	 *
	 * Checks winamp for some options, namely if the volume and fadeout commands are there.
	 * 
	 **************************************************************/
	function CheckForFeatures(){
	    if($this->debug){print "{$this->debugbeg}CheckForFeatures<HR>";}
	    $data = $this->getdata("?");
	    foreach($data as $string){  //while(trim($string) != "+OK"){
		switch(strtoupper(substr($string,0,2))){
		 case 'VO':
		    if($this->debug){
			print "Found ". substr($string,0,2) ."!<br>\n";
		    }
		    $this->volumecontrol = true;
		    break;
		 case 'SH':
		    if($this->debug){
			print "Found ". substr($string,0,2) ."!<br>\n";
		    }
		    $this->shufflecontrol = true;
		    break;
		 case 'RE':
		    if($this->debug){
			print "Found ". substr($string,0,2) ."!<br>\n";
		    }
		    $this->repeatcontrol = true;
		    break;
		 case 'FA':
		    if($this->debug){
			print "Found ". substr($string,0,2) ."!<br>\n";
		    }
		    $this->fadeoutcontrol = true;
		}
	    }
	    if($this->debug){print "{$this->debugend}";}
	}
	/**************************************************************
	 *
	 * Gets the artist and title of a song that was parsed by
	 *  the script... I kinda had to use a hack to get it to work with
	 *   stuff like streams (which have multipule hyphens in them)
	 *  I guess you could use it for anything that
	 *  was formatted like:
	 *  Something - is good
	 *
	 **************************************************************/
	function GetArtistandTitle($something = ""){
	    if($this->debug){print "{$this->debugbeg}GetArtistandTitle<HR>";}
	    unset($this->artist);
	    unset($this->title);
	    if($something != ""){
		if($this->debug){
		    print nl2br( "Something is Not blank!\n" );
		}
		list($this->artist,$this->title) = explode("-",$something);
	    }else{
		if($this->debug){
		    print nl2br( "Something is blank!\n" );
		}
		list($this->artist,$this->title) = explode("-",$this->TrackName,2);
	    }
	    $this->artist = trim($this->artist);
	    $this->title  = trim($this->title);
	    $this->artist = trim(ucwords($this->artist));
	    $this->title  = trim(ucwords($this->title));
	    if($this->debug){
		print nl2br( "\nArtist = {$this->artist}\nTitle = {$this->title}\n" );
	    }
	    if($this->debug){print $this->debugend;}
	    return true;
	}
	/**************************************************************
	 *
	 * control function, start, back, stop, next, shuffle and repeat
	 * 
	 **************************************************************/
	function controlme($todo, $songid="false"){
	    if($this->debug){
		print "{$this->debugbeg}controlme<HR>";
		print nl2br( "Entering Controlme! todo = $todo and songid = $songid\n" );
	    }
	    if(!$this->fp){
		$this->error = "No File pointer!";
		return false;
	    }else{
		if($this->debug){
		    print nl2br( "Sending the command '$todo $songid' to the socket at '{$this->fp}'\n" );
		}
		switch($todo){
		 case 'back':
		    $data = $this->getdata("back");
		    break;
		 case 'next':
		    $data = $this->getdata("next");
		    break;
		 case 'play':
		    if(is_numeric($songid)){
			$data = $this->getdata("play $songid");
		    }else{
			$data = $this->getdata("play");
		    }
		    break;
		 case 'pause':
		    $data = $this->getdata("pause");
		    break;
		 case 'stop':
		    $data = $this->getdata("stop");
		    break;
		 case 'shuffle':
		    $data = $this->getdata("shuffle");
		    break;
		 case 'repeat':
		    $data = $this->getdata("repeat");
		    break;
		 case 'volume up':
		    $data = $this->getdata("volume +");
		    break;
		 case 'volume down':
		    $data = $this->getdata("volume -");
		    break;
		 case 'volume':
		    if($songid){
			$data = $this->getdata("volume $songid");
		    }else{
			return false;
		    }
		    break;
		 case 'fade':
		    $data = $this->getdata("fadeout");
		    break;
		 default:
		    return false;
		}
		foreach($data as $s){  //while(trim($string) != "+OK"){
		    // PAUSE, PLAY, Stop
		    if(strpos($s, "PAUSE")=== true){
			if($this->debug){
			    print "Found Status of 'PAUSED'<br>\n";
			}
			$this->status = "PAUSED";
		    }elseif(strpos($s,"PLAY") == true){
			if($this->debug){
			    print"Found Status of 'PLAYING'<br>\n";
			}
			$this->status = "PLAYING";
		    }elseif(strpos($s,"Stop") == true){
			if($this->debug){
			    print "Found Status of 'BLIP STOPPED'<br>\n";
			}
			$this->status = "BLIP STOPPED";
		    }
		}
	    }
	    $this->getstatus();
	    if($this->debug){
		print "{$this->error}<br>";
	    }
	    if(isset($string)){
		$this->error = $string;
	    }
	    if($this->debug){print $this->debugend;}
	    return true;
	}
	/**************************************************************
	 *returns an array of songs pulled directly from winamp
	 * the songs look like this:
	 * array
	 * {
	 *  [1] => 01. "Some  - Song Here", (3,38m)
	 * }
	 * unless you give it a formatstring, then it will return a formatted string
	 * the following are the replacements:
	 *  %artist% with the artist,
	 *  %title% with the title,
	 *  %time% with the time,
	 *  %number% with the number
	 *  Simple if statments are ok to use, i.e.:
	 *    %if%?%number%:""%if%
	 *   Would check if the mp3number was there and if it was then print the number, else it would not print anything... 
	 *     this is done every time that we get a song.
	 *  i.e.
	 *  <a href=\"$PHP_SELF?id=%id%\">%artist% - %title%</a>
	 *  will print:
	 *  <a href=\"$PHP_SELF?id=1\">Songname - Songtiltle</a>
	 * it will then return:
	 * array
	 * {
	 *  [1] => $format
	 * }
	 *
	 **************************************************************/
	function listitems($format = false,$makearray = false){
	    if($this->debug){
		print $this->debugbeg ."listitems<HR>";
	    }
	    $tTitle = $this->title;
	    $tArtist = $this->artist;
	    if(!$this->fp){
		return false;
	    }
	    $pattern = array("/%title%/","/%artist%/","/%time%/","/%number%/");
	    $data = $this->getdata("list");
	    //fputs($this->fp,"list\n\r");
	    $string ="";
	    $i=0;
	    $j=0;
	    foreach($data as $string){  //while(trim($string) != "+OK"){
	    //while(trim($string) != "+OK"){
		unset($this->artist);
		unset($this->title);
		unset($this->time);
		unset($this->number);
		unset($extra);
		$i++;
		if($i<=3){
		    continue;
		}else{
		    $j++;
		    //$string = fgets($this->fp,200);
		    $this->songs[$j] = chop($string);
		    $this->songs[$j] = trim($this->songs[$j]);
		    if($format){
			if($this->debug){
			    //print nl2br( "format is true<br>\n" );
			}
			$array1 = split("\"",$string);
			$number = $array1[0];
			//$titleandartist = $array1[1];
			if(@$array1[1] == "" or is_null($array1[1])){
			    @$titleandartist = $array1[2];
			    @$tim = $array1[4];
			}else{
			    $tim = $array1[2];
			    $titleandartist = $array1[1];
			}
			@list($artist,$title) = @explode("-",$titleandartist,2);
			if($this->verbose){
			    print "<pre>";
			    print_r($array1);
			    print "\$titleandartist = $titleandartist\n";
			    print "\$tim = $tim\n";
			    print "\$title = $title\n";
			    print "\$artist = $artist\n\n";
			    print "</pre>";
			}
			
			if(strpos($number,"*")){
			    $this->isplaying = trim(str_replace("*","",$number));
			}
			$number = trim(str_replace(".","",str_replace("*","",$number)));
			$title = ucwords($title);
			$artist = ucwords($artist);
			$tim = $this->parsetime($tim);
			if($tim == "NOTHING"){
			    $tim = "";
			}
			if($makearray){
			    $this->artist[$i] = $artist;
			    $this->title[$i] = $title;
			    $this->time[$i] = $tim;
			    $this->number[$i] = $number;
			}else{
			    $replace = array($title,$artist,$tim,$number);
			    $tmpformat = $format;
			    //$format = ;
			    $this->songs[$j] = preg_replace($pattern, $replace,$this->parseif($format,$replace));
			    $format = $tmpformat;
			}
			if($this->verbose){
			    print nl2br( "Artist = $artist<br>\nTitle = $title<br>\nnumber = $number<br>\ntim = $tim<br>\n" );
			}
		    }else{
			$string = $this->songs[$j];
		    }
		    if($this->debug){
			print "Song[$j] = '". $this->songs[$j] ."'<br>\nString = '$string'<br><br>\n";
		    }
		}
	    }
	    if(!$makearray){
		unset($this->songs[$j]);
		unset($this->songs[$j-1]);
		unset($this->songs[$j-2]);
		$this->title = $tTitle;
		$this->artist = $tArtist;
		if($this->debug){print $this->debugend;}
		return $this->songs;
	    }else{
		if($this->debug){print $this->debugend;}
		return true;
	    }
	}
	/**************************************************************
	 *
	 * Parses the time out of a string similar to ', (3,45m)'
	 *
	 **************************************************************/
	function parsetime($tim){
	    if($this->debug){print $this->debugbeg ."Parsetime<HR>";}
	    $RegExIn = $tim;
	    if($this->debug){
		print nl2br( "With RegEx: $RegExIn\n" );
	    }
	    $RegExPattern = "/(\d{1,3})(,)(\d{1,3})([a-z]{1,2})(.*)/i";
	    if($this->debug){
		print nl2br( "<br>RegEx ~$RegExPattern~\n");
	    }
	    $bValidity = $iFound = preg_match( $RegExPattern, $RegExIn, $aRegExOut );
	    if ( FALSE === $bValidity ){
		$aLocated = 'NULLSTRING';
		if($this->debug){
		    print nl2br( '<br>Error interpreting RegEx' );
		}
	    }
	    if ( 0 == $iFound ){
		$aLocated = 'NULLSTRING';
	    }
	    if ( 0 < $iFound ){
		if($this->verbose){
		    print nl2br( "<br>First number =" . $aRegExOut[1] );
		    print nl2br( "<br>Separator =" . $aRegExOut[2] );
		    print nl2br( "<br>Second number =" . $aRegExOut[3] );
		    print nl2br( "<br>Text unit =" . $aRegExOut[4] );
		    print nl2br( "<br>Additional non-text =" . $aRegExOut[5] );
		}
		if(strlen($aRegExOut[3]) != 2){
		    $aRegExOut[3] = "0" . $aRegExOut[3];
		}
		$tim = $aRegExOut[1] . "." . $aRegExOut[3];
		if($this->debug){
		    print nl2br( "<br>Value = $tim\n");
		}
	    }else{
		$tim = "NOTHING";
	    }
	    if($this->debug){print $this->debugend;}
	    return $tim;
	}
	/**************************************************************
	 *
	 * parseif function - 
	 * Checks for the function %if%(%number%)?Truepart:falsepart%if%, evaluates it 
	 *  and returns a proper replacment for the string.
	 * It Returns the string with the proper replacment
	 * if you have  %if%(%number%)?Truepart:Falsepart%if% it will either return
	 * Truepart
	 * or
	 * Falsepart
	 * 
	 * depending on weather or not $this->number is a real value or not
	 * if you have "a href=\"classwrapper.php?thing=play&id=%number%\">%artist% - %title% %if%(%time%)?(%time% Minutes):"%if%</a>"
	 * it will return:
	 * "a href=\"classwrapper.php?thing=play&id=%number%\">%artist% - %title% (%artist% Minutes) </a>"
	 * or
	 * "a href=\"classwrapper.php?thing=play&id=%number%\">%artist% - %title% </a>"
	 *
	 **************************************************************/
	function parseif($string,$ValueToReplace){
	    if($this->debug){print $this->debugbeg ."checkFunction<HR>";}
	    $replacement['title'] =1;
	    $replacement['artist'] =2;
	    $replacement['tim'] =3;
	    $replacement['number'] =4;
	    //              "str  %     if     %% variable    %true % false  %if% rest of string
	    $RegExPattern = "/(.*)".                // First part of the string
	                     "%([a-z]{1,3})%".      // if
			     "\(%([a-z]{1,10})%\)". // Variable
			     "(\W{0,1})".           // ?
	                     "(.*)".
	                     "(.*):".               // rest of string
	                     "(.*)".                //
	                     "%([a-z]{1,3})%".      // if
	                     "(.*)/i";              // string after end of if statment
	    if($this->debug){
		print nl2br("RegEx ~$RegExPattern~\nstring before... '$string'\n");
	    }
	    $th = $err = preg_match($RegExPattern,$string,$answer);
	    if($this->verbose){
		print_r($answer);
	    }
	    if($answer[3] == "time"){
		$answer[3] = "tim";
	    }
	    $torep = $replacement[$answer[3]];
	    $torep = $torep -1;
	    if(!isset($ValueToReplace[$torep]) or $ValueToReplace[$torep] != ""){
		$string = $answer[1] . $answer[5] . $answer[9];
	    }else{
		$string = $answer[1] . $answer[7] . $answer[9];
	    }
	    if($this->debug){
		print nl2br("\$ValueToReplace['{$torep}'] = '". $ValueToReplace[$torep] ."'\n");
		print nl2br("string after... '$string'\n");
		print $this->debugend;
	    }
	    return $string;
	}
	/**************************************************************
	 *
	 * findsong function returns songs that matched the filter 
	 *  that you specified, also
	 *
	 **************************************************************/
	function findsong($tobefound = false,$format = false){
	    if($this->debug){print "{$this->debugbeg} Findsong <HR>\n";}
	    if($tobefound == false){
		$this->error = "No Song was passed to be found... please pas one to the function!";
		return false;
	    }
	    if(!is_array($this->songs)){
		if($format == false){
		    if($this->debug){
			print  "calling listitems to get the array of songs and format it...<br>\n";
		    }
		    $this->debug = false;
		    $this->listitems("%number% - %artist% - %title% (%time%)");
		    $this->debug = true;
		}else{
		    if($this->debug){
			print  "calling listitems to get the array of songs and format it with the formatstring ($format)...<br>\n";
		    }
		    $this->listitems($format);
		}
	    }
	    $tobefound = strtolower($tobefound);
	    if($this->debug){
		print "Finding the song(s)...<br>";
	    }
	    foreach($this->songs as $songarr){
		if($this->verbose){
		    print  "Current song ($songarr) === ($tobefound)<br>\n";
		}
		$songarr = strtolower($songarr);
		$pos = strpos($songarr, $tobefound);
		if($pos !== false) { // note: three equal signs
		    $j++;
		    $songarr = ucwords($songarr);
		    $returned[$j] = $songarr;
		}
	    }
	    if(!is_array($returned)){
		$this->error = "The Song $tobefound was not found... check the spelling and try again";
		if($this->debug){print $this->debugend;}
	    }else{
		if($this->debug){print $this->debugend;}
		return $returned;
	    }
	}
	
	/**************************************************************
	 *
	 * listen function, listens for particular character, and stops after it finds the character
	 * 
	 **************************************************************/
	function listen($for, &$line) {
	    if($this->debug){print "{$this->debugbeg}listen $for<HR>";}
	    while (!strpos($line, $for)) {
		$line = $line.fgets($this->fp, 1);
		#error_log("|$line| |$for|", 0);
	    }	
	    if($this->debug){
		print nl2br("$line");
	    }
	    if($this->debug){print "\n<br>". $this->debugend;}
	    return;
	}
	
	/**************************************************************
	 * 
	 * say function "Says" something to winamp
	 *
	 **************************************************************/
	function say($something) {
	    if($this->debug){print "{$this->debugbeg}say $something<HR>";}
	    $something = $something."\n\r";
	    fputs ($this->fp, $something);
	    if($this->debug){print $this->debugend;}
	}
	/**************************************************************
	 *
	 * getdata function, gets the data from a socket connection
	 *  Returns an array of all the data that was returned from
	 *   the socket
	 *   (optionally sends a command to the socket)
	 * 
	 * usage:
	 *  $this->getdata("list");
	 * This will get you a list of songs in an array
	 *
	 **************************************************************/
	function GetData($command = ""){
	    if($this->debug){print "{$this->debugbeg}GetData<HR>";}
	    if(!$this->fp){
		$this->error = "No Socket!";
		return false;
	    }
	    if($command){
		if($this->debug){
		    print "A command of $command was sent to the function, executing the command....<br>\n";
		}
		fputs($this->fp, $command ."\n\r");
	    }
	    $i=0;
	    $s="";
	    while (trim($s) != "+OK" or trim($s) != "-ERR"){
		flush();
		$s = fgets($this->fp,2000);
		if(trim($s) == "+OK"){
			break;
		}
		if(trim($s) == "-ERR"){
		    break;
		}
		$string[$i] = $s;
		$i++;
	    }
	    if($this->debug){
		print "Splitting String by newlines!<br>";
		print $this->debugend;
	    }
	    return $string;
	}
	/**************************************************************
	 * Closes the connection to winamp
	 *
	 **************************************************************/
	function closefp(){
	    if($this->debug){
		print $this->debugbeg ."closefp<HR>";
		print nl2br( "Closefp called... Closing the connection\n" );
	    }
	    if($this->fp){
		fputs($this->fp,"quit\n\r");
		fclose($this->fp);
		if($this->debug){
		    print nl2br( "Connection Closed\n" );
		}
	    }else{
		if($this->debug){
		    print nl2br( "Connection Not open, therefore not closing the connection\n" );
		}
	    }
	    $this->stop();
	    if($this->debug){print $this->debugend;}
	}
	/************************************************************
	 * 
	 * Start The timer.....
	 * 
	 ************************************************************/
	function start(){
	    $mtime = microtime();
	    $mtime = explode(" ",$mtime);
	    $mtime = $mtime[1] + $mtime[0];
	    $this->start_time = $mtime;
	}
	/************************************************************
	 * 
	 * Stop the timer....
	 * 
	 ************************************************************/
	function stop(){
	    $mtime = microtime();
	    $mtime = explode(" ",$mtime);
	    $mtime = $mtime[1] + $mtime[0];
	    $end_time = $mtime;
	    $this->total_time = ($end_time - $this->start_time);
	}
	/************************************************************
	 * 
	 * Get The time it took to run the script.
	 * 
	 ************************************************************/
	function gettime(){
	    return $this->total_time;
	}
    }// end class
}// end defined

?>
