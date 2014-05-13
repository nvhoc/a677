<?php 
//require config file
require_once(CONFIG_DIR . DS . 'System.conf');

class Database {

	public static function connectBros($host=DB_BROS_HOST, $DBname=DB_BROS_NAME, $port=DB_BROS_PORT, $user=DB_BROS_USER, $password=DB_BROS_PASS) {
		if (strlen($port)>0) {
		   $host = $host.','.$port;
		}


		// Create connection
		$conn = sqlsrv_connect($host, array('UID'=>$user, 'PWD'=>$password, 'Database'=>$DBname, "CharacterSet" =>"UTF-8"));

		// Check connection
		if( !$conn ) {
			 echo "Connection could not be established.<br />";
			 die( print_r( sqlsrv_errors(), true));
		}

		return $conn;
	}

	public static function connectTools($host=DB_BROSTOOLS_HOST, $DBname=DB_BROSTOOLS_NAME, $port=DB_BROSTOOLS_PORT,  $user=DB_BROSTOOLS_USER, $password=DB_BROSTOOLS_PASS){
		try {
		$dsn = 'pgsql:dbname=' . $DBname . ' host=' . $host . ' port='.$port;
		$db = new PDO(
					$dsn,
					$user,
					$password
			);

		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $db;
		}
		catch(PDOException $e)
		{
			  echo $e->getMessage();
		}
	}
	
	public static function connectBrantect($host=DB_BRANTECT_HOST, $DBname=DB_BRANTECT_NAME, $port=DB_BRANTECT_PORT,  $user=DB_BRANTECT_USER, $password=DB_BRANTECT_PASS){
		try {
		$dsn = 'pgsql:dbname=' . $DBname . ' host=' . $host . ' port='.$port;
		$db = new PDO(
					$dsn,
					$user,
					$password
			);

		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $db;
		}
		catch(PDOException $e)
		{
			  echo $e->getMessage();
		}
	}
	
	public static function selectComBros( $sql, $replace_data=null, $order="", $rowNumStart="", $rowNumEnd="" ){
		$url = COMMON_SELECT_API_URL . '?q='.urlencode($sql);
		foreach($replace_data as $value ){
			$url .= '&p[]='.urlencode($value);
		}
		if( $order ){
			$url .= '&o='.urlencode($order);
		}
		if( $rowNumStart ){
			$url .= '&rs='.urlencode($rowNumStart);
		}
		if( $rowNumEnd ){
			$url .= '&re='.urlencode($rowNumEnd);
		}
//print $url."<br>";
		$data = file_get_contents($url);
		$ret = json_decode($data, true);
		return $ret;
	}
}
?>