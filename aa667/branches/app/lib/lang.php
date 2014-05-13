<?php
require_once(CONFIG_DIR . DS . 'System.conf');
require_once(MODELS_WEB_DIR . DS . 'sesUserInfo.php');
/**
  * We return the text that is set based on the key
  * To the position where you want to display characters from the language setting in the HTML
  * <?php echo getString ('hoge');?> Please insert in.
  * @ Param string $ key
  */
class Lang {
	private static $instance = null;
	private static $langValues = array();

	private function __construct()
	{
	}

	/**
	 * get the language corresponding string
	 * @param string $key
	 */
	public function getString($key)
	{
		$lang = sesUserInfo::getLanguage();
		if( !$lang ){
			$lang = 'en';
		}
		
		if(array_key_exists($lang, Lang::$langValues) === false) {
			try
			{
				//Load the language setting
				Lang::loadValue($lang);
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}

		if(array_key_exists($key, Lang::$langValues[$lang]))
		{
			return Lang::$langValues[$lang][$key];
		}
		else
		{
			return "";
		}
	}

	/**
	 * To load the file depending on the value of the session.
	 */
	private function loadValue($lang)
	{

		$file = CONFIG_DIR . DS . 'lang'. DS . $lang.'.conf';
		$fp = fopen($file, "r");
		if(!$fp)
		{
			throw new Exception("lang file not found: " . $file);
		}

		Lang::$langValues[$lang] = array();
		while(($line = fgets($fp)) !== false)
		{
			if($line == "")
			{
				continue;
			}

			list($key, $val) = explode("=", $line);
			$key = trim($key);
			$val = trim($val);

			if($key != "")
			{
				Lang::$langValues[$lang][$key] = $val;
			}
		}

		return true;
	}
}
?>