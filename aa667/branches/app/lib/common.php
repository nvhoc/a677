<?php 
class common {
	public static function getRandomString($nLengthRequired = 8, $sCharList="" ){
		if( !$sCharList ) {
			$sCharList = "23456789abcdefghijkmnpqrstuvwxyABCDEFGHJKLMNPQRSTUVWXY";
		}
		mt_srand();
		$sRes = "";
		for($i = 0; $i < $nLengthRequired; $i++)
		$sRes .= $sCharList{mt_rand(0, strlen($sCharList) - 1)};
		return $sRes;
	}
	public static function trim($str){
		$ret = preg_replace('/(^[ 　]+)|([ 　]+$)/u', '', $str);
		return $ret;
	}
}
?>