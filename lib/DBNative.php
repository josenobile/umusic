<?PHP
/**
 * Class for MySQL Connections and Queries
 */
class DBNative {
	private $link; // Database connection
	private $db;
	private static $obj;
	private $mailSupport; // Email to send errors
	private $debugIP; // IP for print error details
	private $remoteIP;
	private $userErrorMsg;
	private $lastQuery;
	private $transactionStarted = false; // Prevent nested transaction, mysql
	// don't support it.
	public $DSN;
	public $ajax; // defined into the constructor, if true, is ajax request, else
	// is not.
	public $debug = false; // print query and answer (boolean or rows number)
	public $ajaxDebug = false; // Do debug in ajax request, if is ON, json answer
	// will not working on browser
	private function DBNative($DSN = false, $host = false, $user = false, $passwd = false, $db = false, $mailSupport = 'jose.nobile@gmail.com', $debugIP = "181.53.154.20", $userErrorMsg = "Internal server error") {
		$this->printDebug(print_r($_REQUEST,true));
		$this->mailSupport = $mailSupport;
		if ($this->mailSupport == '') {
			$this->mailSupport = "it@" . $_SERVER ["HTTP_HOST"];
		}
		$this->debugIP = $debugIP;
		$this->remoteIP = $this->getIp ();
		$this->userErrorMsg = $userErrorMsg;
		$this->db = $db;
		$this->DSN = $DSN;
		$this->ajax = (((isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') === false) ? false : true);
		if (! empty ( $DSN )) {
			$aTmp = parse_url ( $DSN );
			if (empty ( $aTmp ))
				$this->error ( "The DSN Format is invalid!", "DSN: " . $DSN );
			$aTmp ["db"] = trim ( str_replace ( "/", "", $aTmp ["path"] ) );
			$this->connect ( $aTmp ["host"], $aTmp ["user"], @$aTmp ["pass"], $aTmp ["db"] );
		} else if (! empty ( $host ) && ! empty ( $user ) && ! empty ( $passwd ) && ! empty ( $db )) {
			$this->connect ( $host, $user, $passwd, $db );
		} else
			$this->error ( "The DSN Format is invalid!", "Please your must to pass either the DSN String or the Connection Parameters, DSN: " . $DSN );
		$this->db = $aTmp ["db"];
	}
	public static function get($DSN = false, $host = false, $user = false, $passwd = false, $db = false) {
		if (! self::$obj)
			self::$obj = new DBNative ( $DSN, $host, $user, $passwd, $db );
		return self::$obj;
	}
	private function printDebug($text) {
		if ($this->remoteIP == $this->debugIP && ($this->debug && ! $this->ajax) || ($this->ajax && $this->ajaxDebug)) {
			echo "<div class='debugText'>" . $text . "</div>";
		}
	}
	private function error($subject, $details) {
		ob_start ();
		echo "DSN: ".$this->DSN . "<br />\r\n";
		echo "Last Query: <br />\r\n" . $this->lastQuery . "<br />\r\n";
		echo "BackTrace: ";
		debug_print_backtrace ();
		echo "_POST: ";
		print_r ( $_POST );
		echo "_GET: ";
		print_r ( $_GET );
		echo "_REQUEST: ";
		print_r ( $_REQUEST );
		echo "_SESSION: ";
		print_r ( $_SESSION );
		echo "_SERVER: ";
		print_r ( $_SERVER );
		//echo "GLOBALS: ";
		//print_r ( $GLOBALS );
		$systemInfo = ob_get_contents ();
		ob_end_clean ();
		ob_start ();
		mail ( $this->mailSupport, $subject, $details . $systemInfo, "From: " . $this->mailSupport );
		$mailError = ob_get_contents ();
		ob_end_clean ();
		if ($mailError != '') {
			@file_put_contents ( dirname ( __FILE__ ) . "/../logs/errors.txt", $details . $systemInfo . "\r\n", FILE_APPEND );
		}
		if ($this->remoteIP == $this->debugIP) {
			echo $mailError;
			echo "<h1>" . $subject . "</h1>";
			echo "<pre>" . $details . $systemInfo . "</pre>";
		} else {
			@header ( "HTTP/1.1 503 Service Temporarily Unavailable" );
			@header ( "Status: 503 Service Temporarily Unavailable" );
			@header ( "Retry-After: 30" ); // Retry 20 seconds after
			?><meta http-equiv="refresh" content="30"><?PHP
			echo $this->userErrorMsg;
		}
		exit ( 1 ); // Exit with error
	}
	function getIp() {
		$ipAddr = ! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER ['HTTP_X_FORWARDED_FOR'] : $_SERVER ["REMOTE_ADDR"];
		// fix multiple
		$tmp = explode ( ",", $ipAddr );
		$ipAddr = array_shift ( $tmp );
		return $ipAddr;
	}
	/**
	 * Connect to the Database
	 * 
	 * @param mixed $host
	 *        	Server Name
	 * @param mixed $user
	 *        	User Name
	 * @param mixed $password
	 *        	Password for the User
	 * @param mixed $db
	 *        	Database Name
	 */
	public function connect($host, $user, $password, $db, $c = 0) {
		ob_start ();
		$link = mysql_connect ( $host, $user, $password );
		$error = ob_get_contents ();
		ob_end_clean ();
		if (! $link) {
			if ($c > 30) {
				$this->error ( "Not connection to MySQL Server", $error . "<br />\r\n" . mysql_error () );
			}
			sleep ( 1 );
			return $this->connect ( $host, $user, $password, $db, $c + 1 );
		}
		register_shutdown_function ( array (
				$this,
				'disconnect' 
		) ); // close
		// connection on exit
		// make foo the
		// current db
		$db_selected = mysql_select_db ( $db, $link );
		if (! $db_selected) {
			$this->error ( "Can\'t use $db : ", mysql_error ( $link ) );
		}
		$this->link = $link;
	}
	public function disconnect() {
		mysql_close ( $this->link );
	}
	/**
	 * Executes a query in the Database
	 * 
	 * @param mixed $SQL
	 *        	Query String
	 * @return int
	 */
	public function query($SQL, $numericArray = 0) {
		$this->lastQuery = $SQL;
		$db_selected = mysql_select_db ( $this->db, $this->link ); // Si hay varias
		// instancias de
		// DBNative con
		// diferentes base de
		// datos, solo abra un
		// link, en cada
		// consulta se debe
		// seleccionar
		// (cambiar) la base
		// de datos
		if (empty ( $SQL )) {
			if ($this->transactionStarted === true) {
				$this->query ( "ROLLBACK" ); // ROLLBACK TRANSACTION IN COURSE
				$this->transactionStarted = false;
			}
			$this->error ( "Query must be non-empty!!", "Query must be non-empty!!" );
		}
		$this->printDebug ( $SQL );
		$error = '';
		ob_start ();
		$start = microtime ( true );
		$result = mysql_unbuffered_query ( $SQL, $this->link ); // Test, is good for
		// slow querys, big data
		// in result
		// $result =
		// mysql_query($SQL,
		// $this->link);//is good
		// for fast querys, some
		// rows
		$end = microtime ( true );
		$total = number_format ( ($end - $start) * 1000, 3 ); // ms
		$error = ob_get_contents () . " - TIME=" . $total . " sec - " . @mysql_error ( $this->link );
		ob_end_clean ();
		if ($result === FALSE) {
			if ($this->transactionStarted === true) {
				$this->query ( "ROLLBACK" ); // ROLLBACK TRANSACTION IN COURSE
				$this->transactionStarted = false;
			}
			$this->error ( "Invalid Query", $error );
		}
		$this->printDebug ( "Execution Time (Without fetch): " . $total . " ms" );
		if (is_resource ( $result )) {
			$aTmp = array ();
			$fetch_function = "mysql_fetch_assoc";
			if ($numericArray)
				$fetch_function = "mysql_fetch_array";
			$start = microtime ( true );
			while ( $row = @$fetch_function ( $result ) )
				$aTmp [] = $row;
			$end = microtime ( true );
			$total = number_format ( ($end - $start) * 1000, 3 ); // ms
			$this->printDebug ( "Fectch time: " . $total . " ms" );
			$this->printDebug ( "Rows: " . count ( $aTmp ) );
			return $aTmp;
		} else {
			$affectedRows = $this->getLastAffectedRows ();
			$this->printDebug ( "Affected Rows: " . $affectedRows );
			return $affectedRows;
		}
	}
	public function autoInsert($fields, $table) {
		$values = implode ( ",", array_map ( array (
				$this,
				"quote" 
		), array_values ( $fields ) ) );
		$cols = implode ( ",", array_map ( array (
				$this,
				"quoteColumn" 
		), array_keys ( $fields ) ) );
		$sql = "INSERT INTO $table ($cols) VALUES ($values)";
		$this->query ( $sql );
		return $this->getLastID ( $table );
	}
	public function autoUpdate($fields, $table, $whereString, $autoQuote = true) {
		$sql = "UPDATE  $table SET ";
		$sqlA = array ();
		foreach ( $fields as $name => $value )
			$sqlA [] = $this->quoteColumn ( $name ) . " = " . ($autoQuote ? $this->quote ( $value ) : $value);
		$sql .= implode ( ", ", $sqlA ) . " WHERE $whereString";
		return $this->query ( $sql );
	}
	// Start a Transaction
	public function begin() {
		if ($this->transactionStarted === false) {
			$this->query ( "START TRANSACTION" );
			$this->transactionStarted = true; // transaction started
		} else {
			$this->error ( "Transaction already started", "Transaction already started" );
		}
	}
	// Save transaction
	public function commit() {
		if ($this->transactionStarted === true) {
			$this->query ( "COMMIT" );
			$this->transactionStarted = false; // end of transaction
		} else {
			$this->error ( "Transaction is not already started (not commit possible)", "Transaction is not already started (not commit possible)" );
		}
	}
	// Rollback the transaction
	public function rollback() {
		if ($this->transactionStarted === true) {
			$this->query ( "ROLLBACK" );
			$this->transactionStarted = false; // end of transaction
		} else {
			$this->error ( "Transaction is not already started (not rollback possible)", "Transaction is not already started (not rollback possible)" );
		}
	}
	// execute a array of queries in a transaction, if query return none
	// affected rows... rollback transaction
	public function transaction($q_array) {
		$retval = 1;
		$this->begin ();
		foreach ( $q_array as $qa ) {
			$result = $this->query ( $qa );
			if ($this->getLastAffectedRows () == 0)
				$retval = 0;
		}
		if ($retval == 0) {
			$this->rollback ();
			return false;
		} else {
			$this->commit ();
			return true;
		}
	}
	public function getLastQuery() {
		return mysql_info ( $this->link );
	}
	/**
	 * Get the Last ID for a table
	 * 
	 * @param mixed $table
	 *        	The table name
	 * @return mixed
	 */
	public function getLastID($table) {
		if ($this->transactionStarted === TRUE) {
			return mysql_insert_id ( $this->link ); // only for transactions
		}
		if (empty ( $table ))
			$this->error ( "Table must be non-empty!", "Table must be non-empty!" );
		$SQL = "SELECT LAST_INSERT_ID() AS ID FROM $table ORDER BY ID DESC LIMIT 1";
		$rst = $this->query ( $SQL );
		return $rst [0] ["ID"];
	}
	public function getLastAffectedRows() {
		return mysql_affected_rows ( $this->link );
	}
	/**
	 * Quote and scape a values
	 * 
	 * @param mixed $value
	 *        	The Value to escape
	 */
	public function quote($value) {
		if ($value === NULL || trim ( $value ) == '')
			return "NULL";
		return "'" . $this->escape ( $value ) . "'";
	}
	/**
	 * Quote and scape a values
	 * 
	 * @param mixed $value
	 *        	The Value to escape
	 */
	public function escape($value) {
		/*if ($value === NULL || trim ( $value ) == '')
			return "NULL";*///fix Invoice.inc.php line 479, before uncomment this
		$ovalue = $value;
		if (get_magic_quotes_gpc ())
			$value = stripslashes ( $value );
		$escapedValue = mysql_real_escape_string ( $value, $this->link );
		if ($escapedValue === FALSE) {
			$this->error ( "Problem Escaping Value", "Value: " . print_r ( $value, true ) );
		}
		return $escapedValue;
	}
	public function quoteColumn($col) {
		return '`' . $col . '`';
	}
	
	// Function to Return All Possible ENUM Values for a Field
	public function getEnumValues($table, $field) {
		$enum_array = array ();
		$query = 'SHOW COLUMNS FROM ' . $this->quoteColumn ( $table ) . ' LIKE "' . $field . '"';
		$res = $this->query ( $query );
		$error = print_r ( $res, true );
		preg_match_all ( '/\'(.*?)\'/', $res [0] ["Type"], $enum_array );
		if (! empty ( $enum_array [1] )) {
			// Shift array keys to match original enumerated index in MySQL
			// (allows for use of index values instead of strings)
			foreach ( $enum_array [1] as $mkey => $mval )
				$enum_fields [$mkey + 1] = $mval;
			return $enum_fields;
		} else
			return array (
					$error 
			); // Return an empty array to avoid possible
		// errors/warnings if array is passed to foreach()
		// without first being checked with !empty().
	}
}
?>