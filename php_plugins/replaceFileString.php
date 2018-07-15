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
	
	class replaceFileString extends iployaCore {
		
		const MinArgs = 3;
		const MaxArgs = 4;
		
		public static function process($name, $iploya, $args)  {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			if (!isset($args[3]))
				$args[3] = $args[0];
			try {
				file_put_contents($args[3], str_replace($args[1], $args[2], file_get_contents($args[0])));
				return 0;
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
	}
?>