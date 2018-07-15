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
	
	class monoXBuild extends iployaCore {
		
		const MinArgs	= 2;
		const MaxArgs	= 2;
		
		public static function process($name, $iploya, $args)  {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			$fileToBuild	= $args[0];
			$build_path		= dirname($fileToBuild);
			$fileToBuild	= basename($fileToBuild);
			
			$buildoptions = '';
			if (isset($args[1]))
				$buildoptions = $args[1];
			
			if (!is_dir($build_path)) {
				$iploya->addLog('not a directory: ' . $build_path . "\n");
				return 1;
			}
			try {
				return self::execShell($iploya, ["cd \"" . $build_path . "\"\nxbuild \"" . $fileToBuild . "\" $buildoptions"]);
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
	}
?>