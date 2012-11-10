<?php
class User {//
	var $dbc;
	public $information;
	function User() {
		global $con;
		$this->dbc = $con;
	}
	function getAll() {
	}
	
	function getByProfile() {
		$sql = "SELECT user_id as id, user_name as name FROM lae_user WHERE user_profile_id in(3, 13, 2) AND is_disabled !=1 ORDER BY name";
		$rst = $this->dbc->query ( $sql );
		return $rst;
	}
	function getPrivilegesByProfile($profileId) {
		$sql = <<<EOT
SELECT l.privilege_id, l.privilege_name, l.script_file
FROM lae_profile_has_lae_privilege pp
INNER JOIN lae_profile p ON p.profile_id = pp.lae_profile_profile_id
INNER JOIN lae_privilege l ON l.privilege_id = pp.lae_privilege_privilege_id
WHERE p.profile_id = %s
EOT;
		
		$tSql = sprintf ( $sql, $profileId );
		$rst = $this->dbc->query ( $tSql );
		return $rst;
	}
	function confirmUserPass($userLogin, $password) {
		$sql = "SELECT password, is_disabled FROM lae_user WHERE login=%s LIMIT 1";
		
		if (! get_magic_quotes_gpc ())
			$userLogin = addslashes ( $userLogin );
		
		$tSql = sprintf ( $sql, $this->dbc->quote ( $userLogin ) );
		$rst = $this->dbc->query ( $tSql );
		
		if (empty ( $rst ))
			return 1;
			// Login failure
		

		$rst [0] ["password"] = stripslashes ( $rst [0] ["password"] );
		$rst [0] ["is_disabled"] = intval ( $rst [0] ["is_disabled"] );
		$password = stripslashes ( $password );
		
		if ($rst [0] ["is_disabled"] == 1)
			return 3;
		
		if ($password == $rst [0] ["password"])
			return 0;
			// Login and password confirmed
		else
			return 2; // Password failure
	}
	function confirmUserID($userLogin, $userIdRnd) {
		$sql = "SELECT user_cookie, is_logged_in,ip_address FROM lae_user WHERE login = %s LIMIT 1";
		
		if (! get_magic_quotes_gpc ())
			$userLogin = addslashes ( $userLogin );
		
		$tSql = sprintf ( $sql, $this->dbc->quote ( $userLogin ) );
		$rst = $this->dbc->query ( $tSql );
		
		if (empty ( $rst ))
			return 1;
			// Login failure
		

		$rst [0] ["user_cookie"] = stripslashes ( $rst [0] ["user_cookie"] );
		$userIdRnd = stripslashes ( $userIdRnd );
		
		if (($userIdRnd == $rst [0] ["user_cookie"]) || ($rst [0] ["is_logged_in"] == 0))
			return 0;
			// Login and password confirmed, not logged
		else if (($userIdRnd == $rst [0] ["user_cookie"]) || ($rst [0] ["is_logged_in"] == 1)) { // Login and password confirmed,
			// already logged
			$this->information = "Your are already loggued from other IP: <b>" . $rst [0] ["ip_address"] . "</b><br />Your IP is: " . getIP ();
			return 3;
		} else
			return 2; // User not logged in
	}
	function usernameTaken($userLogin) {
		$sql = "SELECT login FROM lae_user WHERE login='%s'";
		
		if (! get_magic_quotes_gpc ())
			$userLogin = addslashes ( $userLogin );
		
		$tSql = sprintf ( $sql, $userLogin );
		$rst = $this->dbc->query ( $tSql );
		
		return ! empty ( $rst );
	}
	function getUserInfo($userLogin) {
		$strJoin = "";
		$userLogin = $this->dbc->quote ( $userLogin );
		
		$sqlini = <<<EOT
SELECT p.profile_group_id AS group_id
FROM lae_user u
INNER JOIN lae_profile p ON p.profile_id = u.user_profile_id
WHERE u.login = %s
EOT;
		
		$sql = <<<EOT
SELECT %s
FROM lae_user u
INNER JOIN lae_profile p ON p.profile_id = u.user_profile_id
%s
WHERE u.login = %s
EOT;
		
		$tSql1 = sprintf ( $sqlini, $userLogin );
		$rst1 = $this->dbc->query ( $tSql1 );
		
		if (empty ( $rst1 ))
			return NULL;
		
		$groupId = $rst1 [0] ["group_id"];
		$aSels = array (
				"u.user_id",
				"u.user_name",
				"u.login",
				"p.profile_id",
				"p.profile_name",
				"p.profile_group_id as group_id",
				"u.target_id" 
		);
		
		if ($groupId == 2) { // Office Profiles
			$aSels [] = "t.office_name AS target_name";
			$aSels [] = "t.office_email";
			$strJoin = "INNER JOIN lae_office t ON t.office_id = u.target_id";
		} else if ($groupId == 3) { // Referral Profiles
			$aSels [] = "t.referral_name AS target_name";
			$strJoin = "INNER JOIN lae_referral t ON t.referral_id = u.target_id";
		}
		
		$tSql2 = sprintf ( $sql, implode ( ",", $aSels ), $strJoin, $userLogin );
		$rst2 = $this->dbc->query ( $tSql2 );
		
		if (empty ( $rst2 ))
			return NULL;
		
		return $rst2 [0];
	}
	function addActiveUser($userLogin, $time) {
		$ipAddr = getIp ();
		$sql = "UPDATE lae_user SET last_access = %s, ip_address = '$ipAddr' WHERE login = %s";
		$tSql = sprintf ( $sql, $this->dbc->quote ( $time ), $this->dbc->quote ( $userLogin ) );
		$rst = $this->dbc->query ( $tSql );
	}
	function generateRandID() {
		return md5 ( $this->generateRandStr ( 16 ) );
	}
	function generateRandStr($length) {
		$randstr = "";
		
		for($i = 0; $i < $length; $i ++) {
			$randnum = mt_rand ( 0, 61 );
			if ($randnum < 10) {
				$randstr .= chr ( $randnum + 48 );
			} else if ($randnum < 36) {
				$randstr .= chr ( $randnum + 55 );
			} else {
				$randstr .= chr ( $randnum + 61 );
			}
		}
		
		return $randstr;
	}
	function updateUserCookie($userLogin, $value) {
		$sql = "UPDATE lae_user SET user_cookie = %s WHERE login = %s";
		$tSql = sprintf ( $sql, $this->dbc->quote ( $value ), $this->dbc->quote ( $userLogin ) );
		$rst = $this->dbc->query ( $tSql );
		return $rst;
	}
	function getByOffice($officeId) {
		$sql = <<<EOT
SELECT u.user_id AS id, u.user_name AS name
FROM lae_user u
INNER JOIN lae_profile p ON p.profile_id = u.user_profile_id
WHERE u.target_id = %s AND u.is_disabled = false AND u.user_name!=""
AND p.profile_group_id = 2 AND p.profile_id IN (3,4,7,13)
ORDER BY name
EOT;
		
		$tSql = sprintf ( $sql, $officeId );
		$rst = $this->dbc->query ( $tSql );
		return $rst;
	}
	function getByCuntry($codUsr) {
		$sql = "select U.user_id, C.currency_country_id as id, currency_id as cId, C.currency_name as name, C.currency_code as cCode, O.office_id as off,
				U.user_name as Uname, U.email as Umail, O.office_phone as Uphone, O.office_address as udd, O.office_email as Office,
				O.office_name as oName, Z.zone_id as zId, P.profile_id as pId
				from lae_user U
				INNER JOIN lae_office O ON O.office_id = U.target_id
				INNER JOIN lae_currency C ON C.currency_id = O.office_currency_id
				INNER JOIN lae_country Co ON Co.country_id = C.currency_country_id
				INNER JOIN lae_profile P ON P.profile_id = U.user_profile_id
				INNER JOIN lae_zone Z ON Z.zone_id = Co.country_zone_id
				WHERE U.user_id = %s
				AND P.profile_group_id = 2
				ORDER BY U.user_id DESC";
		
		$tSql = sprintf ( $sql, $codUsr );
		$rst = $this->dbc->query ( $tSql );
		return $rst;
	}
	function getUserOffice($office) {
		if (empty ( $office ))
			return false;
		
		$sql = "SELECT user_id as id, user_name as name
				FROM lae_user
				WHERE target_id = %s
				AND is_disabled=0
				AND user_profile_id in(4)
				AND is_disabled = 0";
		
		$tSql = sprintf ( $sql, $office );
		$rst = $this->dbc->query ( $tSql );
		return $rst;
	}
	
	//cargamos los usuarios que estan de cumpleaños
	function birthdayUser() {
		$sql = "SELECT user_id as id, user_name as name
				FROM lae_user
				WHERE birthday = %s";
		
		$time = $this->dbc->quote ( date ( "Y-m-d" ) );
		
		$tSql = sprintf ( $sql, $time );
		$rst = $this->dbc->query ( $tSql );
		return $rst;
	}
	
	//cargamos pefil del usuario
	function UserProfile($user) {
		$sql = "SELECT user_id as id, user_name as name, description, email, phone, address, office_name, charge
				FROM lae_user u 
				INNER JOIN lae_office o ON u.target_id = office_id
				WHERE user_id = $user";
		
		$rst = $this->dbc->query ( $sql );
		return $rst [0];
	}
}

?>