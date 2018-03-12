<?php
class Config
{
	//funzione usata per accedere alle configurazioni piu facilmente
	//Con questa funzione ti permette di fare -> Config::get('mysql/host')
	//1 se path inserito 2 salva tutta la global variable 3 controlla se esiste 'mysql' in global 4 controlla se esiste host in global[mysql]
	//DA IMPLEMENTARE IL FAR VEDERE L ERRORE NEL CASO path errato
	public static function get($path=null)
	{
		$errorPath = false ;
		if($path)
		{
			$config = $GLOBALS['config'];
			$path = explode('/', $path);
			foreach($path as $bit)
			{
				if(isset($config[$bit]))
				{
					$config = $config[$bit];
					$errorPath = true;
				}
				else
				{
					$errorPath = false ;
				}
			}
			return ($errorPath) ? $config : false;
		}
		return $errorPath;
	}
}