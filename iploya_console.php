<?php
	namespace iploya;
	/*
	*	iploya - easy deployer (console class)
	*	author: Ludwig Oberheuser<oberheuser@gmail.com>
	*	created: 19.06.2018
	*
	*	example:
	*
	*	php "./iploya_console.php" "./iploya/examples/simpleVariableMessage.json"
	*
	*
	*
	*	(c) 2018 Ludwig Oberheuser
	*	License: MIT
	*/	
	
	require_once(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'iploya.php');

	/*
		checking args
	*/	
	if (!isset($argv) || !is_array($argv) || count($argv) < 2)
		throw new \Exception('iploya: error: no commandline arguments defined.');

	/*
		run
	*/		
	iploya_console::run($argv);		

				
	final class iploya_console {
		
		/*
			run
		*/			
		public static function run($argv) {
			
			/*
				checking jobfile argument
			*/
			$jobfile		= self::checkJobfile($argv);
			
			/*
				get templatefile argument or null
			*/
			$templatefile	= self::getTemplatefileOrNull();
			
			/*
				create an instance of iploya
			*/
			$iploya 		= new iploya();
			
			/*
				process iploya
			*/
			$iploya->process($jobfile, $templatefile, $argv);
		}

		/*
			checkJobfile
		*/
		private static function checkJobfile($argv) {
			if (!isset($argv[1])) {
				throw new \Exception("iploya: jobfile argument missing.");
			}
			if (!is_file($argv[1])) {
				throw new \Exception('iploya: specified jobfile ' . $argv[1] . ' dont exist.');	
			}
			return $argv[1];
		}

		/*
			checkTemplatefile
		*/
		private static function getTemplatefileOrNull() {
			$templatefile = null;
			if (isset($argv[2]) && substr($argv[2],0,2) !== '-t') {
				if (!is_file($argv[2])) {
					throw new \Exception("iploya: specified templatefile " . $argv[2] . " dont exist.");
				}
				$templatefile = $argv[2];
			}			
			return $templatefile;
		}

	}

?>