<?php
//require config file
require_once(CONFIG_DIR . DS . 'System.conf');
require_once(MODELS_BRANTECT_DIR . DS . 'MstClientId.php');

class callOuterModuleAPI {

	public static function callBrantectApi( $post_data=null ){
		$MstClientId = new MstClientId();
		$retData = $MstClientId->getBrantectAccessPassword(BRANTECT_API_BASIC_AUTH_ID,BRANTECT_API_LOGIN_ID);
		$accsess_client_pwd = $retData['client_pwd'];
		$accsess_user_pwd = $retData['user_pwd'];

		if( !$accsess_client_pwd || !$accsess_user_pwd ){
			$ret['status'] = '800';
			$ret['message'] = 'Exception Error';
			return $ret;
		}
		$param = "u=".BRANTECT_API_LOGIN_ID."&p=".$accsess_user_pwd;
		foreach($post_data as $key => $value ){
			if( $param ){
				$param .= "&";
			}
			$param .= $key . '='.urlencode($value);
		}
		$url = str_replace('user', BRANTECT_API_BASIC_AUTH_ID, BRANTECT_API_URL);
		$url = str_replace('password', $accsess_client_pwd, $url);
		$url = $url . '?'.$param;
		$data = file_get_contents($url);
		$ret = json_decode($data, true);
		return $ret;
	}
}
?>