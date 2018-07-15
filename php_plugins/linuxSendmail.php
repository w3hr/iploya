<?php
	/*
	*	iploya - easy deployer (php_plugin)
	*	author: Ludwig Oberheuser<oberheuser@gmail.com>
	*	created: 21.06.2018
	*
	*	(c) 2018 Ludwig Oberheuser
	*	License: MIT
	*/
	
	namespace iploya;
	require_once(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'core.php');
	
	class linuxSendmail extends iployaCore {
		
		const MinArgs	= 3;
		const MaxArgs	= 3;
		
		public static function process($name, $iploya, $arg) {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			try {
				return mail ($arg[0], $arg[1], $arg[2]); 
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
	}
?>