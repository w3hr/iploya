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
	
	class removeDirectory extends iployaCore {
		
		const MinArgs	= 1;
		const MaxArgs	= 1;
		
		public static function process($name, $iploya, $args)  {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			$dir	= $args[0];
			if (!is_dir($dir)) {
				$iploya->addLog(__CLASS__ . ": " . "not a directory: " . $dir . "\n");
				return 1;
			}
			try {
				self::rrmdir($dir);
				return 0;
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
		
		private static function rrmdir($dir) {
			$objects = scandir($dir); 
			foreach ($objects as $object) { 
	      		if ($object != "." && $object != "..") { 
		      		if (is_dir($dir.DIRECTORY_SEPARATOR.$object))
		      			self::rrmdir($dir.DIRECTORY_SEPARATOR.$object);
		      		else
		      			unlink($dir.DIRECTORY_SEPARATOR.$object); 
	      		} 
			}
			rmdir($dir); 
		}
		
	}
?>