<?php
	/*
	*	iploya - easy deployer (php_plugin)
	*	author: Ludwig Oberheuser<oberheuser@gmail.com>
	*	created: 19.06.2018
	*
	*	(c) 2018 Ludwig Oberheuser
	*	License: MIT
	*/
	
	namespace iploya;
	require_once(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'core.php');
	
	class gitCheckoutBare extends iployaCore {
		
		const MinArgs	= 3;
		const MaxArgs	= 3;
		
		public static function process($name, $iploya, $args)  {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			try {
				return self::execShell($iploya, ["git --work-tree='" . $args[0] . "' --git-dir='" . $args[1] . "' checkout $args[2] -f"]);
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
	}
?>