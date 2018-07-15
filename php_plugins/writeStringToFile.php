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
	
	class writeStringToFile extends iployaCore {
		
		const MinArgs = 2;
		const MaxArgs = 2;
		
		public static function process($name, $iploya, $args)  {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			try {
				file_put_contents($args[0], $args[1]);
				return 0;
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
	}
?>