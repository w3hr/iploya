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
	
	class gitStageAndCommit extends iployaCore {
		
		const MinArgs	= 2;
		const MaxArgs	= 2;
		
		public static function process($name, $iploya, $args)  {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			$folder		= $args[0];
			$commitmessage	= $args[1];;
			try {
				return self::execShell(["cd '" . $folder . "'\ngit add . \ngit commit -m '".$commitmessage."'"]);
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
	}
?>