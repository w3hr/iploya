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
	
	class createDirectory extends iployaCore {
		
		const MinArgs	= 1;
		const MaxArgs	= 1;
		
		public static function process($action, $iploya, $args)  {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			
			try {
				return (int)mkdir($args[0]);	
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
			
		}
	}
?>