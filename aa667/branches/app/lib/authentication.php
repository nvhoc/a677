<?php

//require config file
require_once(CONFIG_DIR . DS . 'System.conf');
require_once(MODELS_WEB_DIR . DS . 'sesUserInfo.php');
require_once(MODELS_WEB_DIR . DS . 'ckiAccessInfo.php');
require_once(LIBRARY_DIR . DS . 'dataBase.php');
require_once(LIBRARY_DIR . DS . 'common.php');
require_once(MODELS_BROS_TOOLS_DIR . DS . 'BTLoginTicket.php');
require_once(MODELS_BROS_TOOLS_DIR . DS . 'BTUserAccessLog.php');
require_once(MODELS_BROS_DIR . DS . 'TMUser.php');
require_once(MODELS_BROS_TOOLS_DIR . DS . 'BTUserProperties.php');
require_once(MODELS_BROS_TOOLS_DIR . DS . 'BTUserAvailableScreen.php');

date_default_timezone_set('Asia/Tokyo');
define('AUTH_STATUS_OK', "0");
define('AUTH_STATUS_OK_LOGIN', "1");
define('AUTH_STATUS_NG_INVALID', "900");
define('AUTH_STATUS_NG_TIMEOUT', "901");
define('AUTH_STATUS_NG_IP', "902");
define('AUTH_STATUS_NG_ACCESS_SCREEN', "903");
define('AUTH_STATUS_NG_CONTROLLER_NONE', "904");
define('AUTH_STATUS_NG_ACTION_NONE', "905");


class authentication {

	private $module_name="";
	private $controller_name="";
	private $action_name="";
	private $id="";
	private $status=AUTH_STATUS_OK;
	private $message="";
	private $accessKey=null;
	private $expirationDate=0;
	private $api_flg=0;
	private $db;
	private $dbCreateFlg=false;
		
	
	//constructor
	public function __construct($module_name=null,$controller_name=null,$action_name=null,$id=null,$api_flg=null,$db=null){
		if($module_name != null){
			$this->module_name= $module_name;
		}
		if($controller_name != null){
			$this->controller_name =$controller_name;
		}
		if($action_name != null){
			$this->action_name = $action_name;
		}
		if($id != null){
			$this->id = $id;
		}
		
		if($api_flg != null){
			$this->api_flg = $api_flg;
		}
		
		if( $db == null ){
			$this->db = Database::connectTools();
			$dbCreateFlg=true;
		} else {
			$this->db = $db;
		}
	}
	public function __destruct() {
		$db = $this->db;
		$logOnNm = sesUserInfo::getLoginNm();
		if( $this->status == AUTH_STATUS_OK && $this->accessKey != null){
			if( $logOnNm != null ){
				$expiration_date = date('YmdHi',strtotime( TICKET_TIMEOUT ));

				$btLoginTicket = new BTLoginTicket($db);
				if( $this->expirationDate ) {
					if($this->expirationDate != $expiration_date){
						$btLoginTicketData = $btLoginTicket->update($logOnNm, $this->accessKey, $this->expirationDate, $expiration_date);
					}
				} else {
					$btLoginTicketData = $btLoginTicket->insert($logOnNm, $this->accessKey, $expiration_date);
				}
			}
		}
		// output access log
		$btUserAccLog = new BTUserAccessLog($db);
		$log_id = $btUserAccLog->getAccessLogId();
		$response_type = sesUserInfo::getResponseType();
		if( $response_type == BT_MST_SCREEN_ACTION_RESPONSE_TYPE_HTML  ){
			$response_type = sesUserInfo::setReloadLogid($log_id);

		}
		$btUserAccLog->setAccessLog(
				$log_id,
				$logOnNm,
				$this->accessKey,
				($this->id != "")?$this->id:null,
				$this->module_name,
				$this->controller_name,
				$this->action_name,
				($this->status != "")?$this->status:null,
				$this->message,
				json_encode($_GET),
				json_encode($_POST),
				json_encode($_SESSION),
				$_SERVER['REMOTE_ADDR'],
				$_SERVER['HTTP_USER_AGENT']
				);
		if($this->dbCreateFlg){
			//DB Close
			$this->db = null;
		}
	}
	/*
	 * return true / false
	 * */
	public function chkAuth() {

		$api_log_on_nm = "";
		$psd = "";
		if( $this->api_flg ){//API Call
			if($_GET['u'] && $_GET['p']){
				$api_log_on_nm = $_GET['u'];
				$psd = $_GET['p'];
			}else if($_POST['u'] && $_POST['p']){
				$api_log_on_nm = $_POST['u'];
				$psd = $_POST['p'];
			}
		}
		
		if( !$api_log_on_nm ){
			$this->accessKey = ckiAccessInfo::getAccessKey();
//print $this->accessKey."********";
//exit;
			//Check Access Key
			if( !$this->accessKey ) {
				if( $this->module_name || $this->controller_name || $this->action_name){
					$this->status = AUTH_STATUS_NG_INVALID;
					$this->message = Lang::getString('ERROR_MSG_004');
				} else {
//print "A1*";
					$this->status = AUTH_STATUS_OK_LOGIN;
					$this->accessKey = $this->getAccessKey();
				}
				return false;
			}
		} else {
			if( !$this->chkLoginUser($api_log_on_nm, $psd) ){
				$this->status = AUTH_STATUS_NG_INVALID;
				$this->message = Lang::getString('ERROR_MSG_008');
				return false;
			}
			$this->accessKey = $this->getAccessKey();
		}

		//Login Scteen
		if( !$this->module_name || !$this->controller_name || !$this->action_name ) {
			$this->accessKey = $this->getAccessKey();
			$this->status = AUTH_STATUS_OK_LOGIN;
			return false;
		}
		
		//Will not be checked in later for login processing.
		if( $this->module_name == LOGIN_MODULE && $this->controller_name == LOGIN_API_CONTROLLER && $this->action_name == LOGIN_API_ACTION ) {
			$this->accessKey = $this->getAccessKey();
			return true;
		}

		//Cookie Time out Extension
		ckiAccessInfo::setAccessKey( $this->accessKey );
		$logOnNm = sesUserInfo::getLoginNm();
		//Logon name does not exist in the session.
		if( !$logOnNm ) {
			$this->status = AUTH_STATUS_OK_LOGIN;
			return false;
		}
		
		if( !$api_log_on_nm ) {
			$db = $this->db;
			$btLoginTicket = new BTLoginTicket($db);
			$btLoginTicketData = $btLoginTicket->find($logOnNm, $this->accessKey);
			if( $btLoginTicketData ){
				//expirationDate setting
				$this->expirationDate = $btLoginTicketData['expiration_date'];
			}
			// Check Time Out
			if( !$this->chkTimeOut( $this->expirationDate ) ){
				$this->expirationDate = 0;
				$this->accessKey = $this->getAccessKey();
				return false;
			}
		}
		// Check IP Address
		if( !$this->chkIpAdress() ){
			return false;
		}
		
		// Check Screen Action
		if( !$this->chkScreenAction() ){
			return false;
		}
		
		// Check Module, Controller, Action
		if( !$this->chkModule($this->module_name,$this->controller_name,$this->action_name) ){
			return false;
		}
		return true;
	}
	public function getStatus() {
		return $this->status;
	}
	public function getMessage() {
		return $this->message;
	}
	public function setScreenInfo($module_name=null,$controller=null,$action_name=null,$id=null){
		if($module_name != null){
			$this->module_name= $module_name;
		}
		if($controller != null){
			$this->controller_name =$controller;
		}
		if($action_name != null){
			$this->action_name = $action_name;
		}
		if($id != null){
			$this->id = $id;
		}
	}
	private function getAccessKey() {
		$accessKey = "";
		$accessKey = date('YmdHis').common::getRandomString();
		ckiAccessInfo::setAccessKey( $accessKey );
		return $accessKey;
	}

	private function chkTimeOut( $expirationDate ){
		$ret = true;
		$nowDate = date('YmdHi');
		if( !$expirationDate || $expirationDate < $nowDate ){
			$ret = false;

			if( $this->module_name || $this->controller_name || $this->action_name){
				$this->status = AUTH_STATUS_NG_TIMEOUT;
				$this->message = Lang::getString('ERROR_MSG_005') . $expirationDate ."/".$nowDate."";
			} else {
				$this->status = AUTH_STATUS_OK_LOGIN;
			}
		}
		return $ret;
	}
	private function chkIpAdress(){
		$ret = true;
		if( !sesUserInfo::getAccessIpUnlimitedFlg() ){
			$ret = false;
			$ip = $_SERVER["REMOTE_ADDR"];
			$array_cidr = explode('|',sesUserInfo::getAccessIp());
			foreach( $array_cidr as $cidr){
				list($network, $mask_bit_len) = explode('/', $cidr);
				if( $mask_bit_len != "" ){
					$host = 32 - $mask_bit_len;
					$net = ip2long($network) >> $host << $host;
					$ip_net = ip2long($ip) >> $host << $host;
					$ret= $net === $ip_net;
				} else {
					$ret = ( $ip === $cidr);
				}
				if( $ret ){
					break;
				}
			}
		}
		if( !$ret ){
			$this->status = AUTH_STATUS_NG_IP;
			$this->message = Lang::getString('ERROR_MSG_006');
		}

		return $ret;
	}
	private function chkScreenAction(){
		$ret = true;
		if(sesUserInfo::getAuthority() != BT_USER_PROPERTIES_AUTHORITY_ADMIN){

			$screenActionId = $this->id;
			$availableScrActId = sesUserInfo::getAvailableScreenActionId();
			if( !$availableScrActId || !in_array($screenActionId ,$availableScrActId) ){
				$ret = false;
			}
		}
		if( !$ret ){
			$this->status = AUTH_STATUS_NG_ACCESS_SCREEN;
			$this->message = Lang::getString('ERROR_MSG_007');
			$this->message .= "<br>". $this->module_name." / ".ucfirst($this->controller_name). " / " . $this->action_name;
		}
		return $ret;
	}
	private function chkModule($module_name,$controller_name,$action_name){
		$ret = true;
		$controller_class_name = $controller_name . 'Controller';

		if( !file_exists(CONTROLLER_DIR . DS . $module_name. DS . $controller_class_name . '.php')){
//print "$module_name,$controller_class_name";
			$this->status = AUTH_STATUS_NG_CONTROLLER_NONE;
			$this->message = Lang::getString('ERROR_MSG_003');
			$ret = false;
		} else {
			require_once(CONTROLLER_DIR . DS . $module_name . DS . $controller_class_name . '.php');
			$controller = new $controller_class_name();
			//Check for the existence of action
			if(!method_exists($controller, $action_name)){
				$this->status = AUTH_STATUS_NG_ACTION_NONE;
				$this->message = Lang::getString('ERROR_MSG_002');
				$ret = false;
			}
		}
		return $ret;
	}
	private function chkLoginUser($log_on_nm, $psd){

		$tmUser = new TMUser();
		$tmUserData = $tmUser->findLogonUser($log_on_nm, $psd);
		if( $tmUserData == null ){
			return false;
		}	

		sesUserInfo::setLoginNm($tmUserData["log_on_nm"]);
		sesUserInfo::setUserNm($tmUserData["user_nm"]);
		//Set Session Data of BTUserProperties
		$userProper = new BTUserProperties($db);
		$userProperData = $userProper->getUserProperties($log_on_nm);
		if( $userProperData ){
			sesUserInfo::setAuthority($userProperData['authority']);
			sesUserInfo::setLanguage($userProperData['def_language']);
			sesUserInfo::setAccessIp($userProperData['access_ip']);
			sesUserInfo::setAccessIpUnlimitedFlg($userProperData['access_ip_unlimited_flg']);
		}

		//Set Session Data of BTUserProperties
		$userAvailableScr = new BTUserAvailableScreen($db);
		$userAvailableScrData = $userAvailableScr->getAvailableScreen($log_on_nm);
		if( $userAvailableScrData ){
			$available_screen_action_id = array();
			foreach($userAvailableScrData as $arrayValue){
				array_push($available_screen_action_id, $arrayValue['available_screen_action_id'] );
			}
			sesUserInfo::setAvailableScreenActionId($available_screen_action_id);
		}

		return true;
	}
}
?>