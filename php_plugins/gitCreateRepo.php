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
	
	class gitCreateRepo extends iployaCore {
		
		const MinArgs	= 1;
		const MaxArgs	= 1;
		
		public static function process($name, $iploya, $args)  {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			$folder	= $args[0];
			try {		
				return self::execShell($iploya, ["cd '" . $folder . "'\n git init"]);		
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
	}
?>