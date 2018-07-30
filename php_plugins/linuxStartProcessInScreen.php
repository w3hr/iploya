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
	
	class linuxStartProcessInScreen extends iployaCore {
		
		public static $MinArgs	= 3;
		public static $MaxArgs	= 3;
		
		public static function process($name, $iploya, $args)  {
			$process_path		= $args[1];
			if (!is_dir($process_path)) {
				$iploya->addLog('not a directory: ' . $process_path . "\n");
				return 1;
			}
			try {		
				return self::execShell($iploya, ["screen -dmS " . $args[0] . " sh",
				"screen -S " . $args[0] . " -X stuff \"cd " . $process_path . "\n\"",
				"screen -S " . $args[0] . " -X stuff \"" . $args[2] . "\n\""]);
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
	}
?>