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
	
	class linuxGzipDirectory extends iployaCore {
		
		const MinArgs	= 2;
		const MaxArgs	= 2;
		
		public static function process($name, $iploya, $args)  {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			$source	= $args[0];
			$dest 	= $args[1];
			try {
				
				$return_var = 0;
				$output = [];
				exec ("tar -zcf \"" . $dest . "\" \"" . $source . "\"", $output, $return_var);
				
				foreach ($output as $o)
					$iploya->addLog(__CLASS__ . ": " . $o);
					
				return $return_var;
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
	}
	
/*
				
0

    `Successful termination'.
1

    `Some files differ'. If tar was invoked with `--compare' (`--diff', `-d') command line option, this means that some files in the archive differ from their disk counterparts (see section Comparing Archive Members with the File System). If tar was given `--create', `--append' or `--update' option, this exit code means that some files were changed while being archived and so the resulting archive does not contain the exact copy of the file set.
2

    `Fatal error'. This means that some fatal, unrecoverable error occurred. 
*/
	
?>