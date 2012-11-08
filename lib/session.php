<?php

require_once "user.inc.php";
class Session {
	var $dbc;
	var $userLogin; // The Username
	var $userIdRnd; // The User ID Rand
	var $userLevel; // The User Profile
	var $time;
	var $logged_in = false;
	var $userInfo = array ();
	var $url;
	var $referrer;
	var $aErrors = array ();
	var $user;
	public $information;
	function Session() {
		global $con;
		$this->dbc = $con;
		$this->user = new User ();
		
		$this->time = date ( "Y-m-d H:i:s" );
		$this->startSession ();
	}
	function startSession() {
		session_start ();
		$this->logged_in = $this->checkLogin ();
		
		/* Update users last active timestamp */
		if ($this->logged_in)
			$this->user->addActiveUser ( $this->userLogin, $this->time );
			
			/* Set referrer page */
		if (isset ( $_SESSION ['url'] ))
			$this->referrer = $_SESSION ['url'];
		else
			$this->referrer = "/";
		
		$this->url = $_SESSION ['url'] = $_SERVER ['PHP_SELF'];
	}
	function checkLogin() {
		if (isset ( $_COOKIE ['cookname'] ) && isset ( $_COOKIE ['cookidrnd'] )) {
			$this->userLogin = $_SESSION ['userlogin'] = $_COOKIE ['cookname'];
			$this->userIdRnd = $_SESSION ['useridrnd'] = $_COOKIE ['cookidrnd'];
		}
		
		if (isset ( $_SESSION ['userlogin'] ) && isset ( $_SESSION ['useridrnd'] )) {
			if (($resp = $this->user->confirmUserID ( $_SESSION ['userlogin'], $_SESSION ['useridrnd'] )) != 0) {
				// var_dump($resp);exit;
				if ($resp == 2)
					$this->aErrors ["username"] = "* User not logged in";
				if ($resp == 3) {
					$this->information = $this->user->information;
					$this->aErrors ["twice_login"] = $this->information;
				}
				unset ( $_SESSION ['userlogin'] );
				unset ( $_SESSION ['useridrnd'] );
				return false;
			}
			
			/* User is logged in, set class variables */
			$this->userInfo = $this->user->getUserInfo ( $_SESSION ['userlogin'] );
			$this->userLogin = $this->userInfo ['login'];
			$this->userIdRnd = $this->userInfo ['user_cookie'];
			$this->userLevel = $this->userInfo ['profile_id'];
			
			return true;
		} else { /* User not logged in */
			return false;
		}
	}
	function login($subuser, $subpass, $subremember) {
		if (! $subuser || strlen ( $subuser = trim ( $subuser ) ) == 0)
			$this->aErrors ["username"] = "* Username not entered";
		else {
			if (! eregi ( "^([0-9a-z|\.])*\$", $subuser ))
				$this->aErrors ["username"] = "* Username not alphanumeric";
		}
		
		if (! $subpass)
			$this->aErrors ["password"] = "* Password not entered";
		
		if (! empty ( $this->aErrors ))
			return false;
		
		$subuser = stripslashes ( $subuser );
		$result = $this->user->confirmUserPass ( $subuser, md5 ( $subpass ) );
		
		/* Check error codes */
		if ($result == 1)
			$this->aErrors ["username"] = "Username not found";
		else if ($result == 2)
			$this->aErrors ["password"] = "Invalid password";
		else if ($result == 3)
			$this->aErrors ["username"] = "User is disabled";
		
		$this->userInfo = $this->user->getUserInfo ( $subuser );
		$randomId = $this->user->generateRandID ();
		
		// echo "here: {$_SESSION['userlogin']} - {$_SESSION['useridrnd']}
		// ".var_dump($this->user->confirmUserID($this->userInfo['login'],
		// $randomId)); die();
		// if($this->user->confirmUserID($this->userInfo['login'], $randomId) ==
		// 3)
		// $this->aErrors["username"] = "* This user is <br />already logged";
		

		if (! empty ( $this->aErrors ))
			return false;
		
		$this->userLogin = $_SESSION ['userlogin'] = $this->userInfo ['login'];
		$this->userIdRnd = $_SESSION ['useridrnd'] = $randomId;
		$this->userLevel = $this->userInfo ['profile_id'];
		
		$this->setLoggedIn ( $this->userInfo ["user_id"], 1 );
		$this->user->addActiveUser ( $this->userLogin, $this->time );
		$this->user->updateUserCookie ( $this->userLogin, $this->userIdRnd );
		
		if ($subremember) {
			setcookie ( "cookname", $this->userLogin, time () + COOKIE_EXPIRE, COOKIE_PATH );
			setcookie ( "cookidrnd", $this->userIdRnd, time () + COOKIE_EXPIRE, COOKIE_PATH );
		}
		
		return true;
	}
	function setLoggedIn($userId, $status) {
		if (empty ( $userId ))
			die ( "ERROR: User is requerided" );
		
		$sql = "UPDATE lae_user SET is_logged_in = %s WHERE user_id = %s";
		$tSql = sprintf ( $sql, $status, $userId );
		$rst = $this->dbc->query ( $tSql );
		
		return ! empty ( $rst ) ? true : false;
	}
	function logout() {
		if (isset ( $_COOKIE ['cookname'] ) && isset ( $_COOKIE ['cookidrnd'] )) {
			setcookie ( "cookname", "", time () - COOKIE_EXPIRE, COOKIE_PATH );
			setcookie ( "cookidrnd", "", time () - COOKIE_EXPIRE, COOKIE_PATH );
		}
		
		$_SESSION = array ();
		session_destroy ();
		
		$this->setLoggedIn ( $this->userInfo ["user_id"], 0 );
		$this->user->updateUserCookie ( $this->userLogin, '' );
		$this->logged_in = false;
	}
	function getErrors() {
		return $this->aErrors;
	}
}

$session = new Session ();
?>