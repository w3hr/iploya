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
	
	class copyDirectory extends iployaCore {
		
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
				if (!is_dir($dest))
					mkdir($dest);
				foreach ($iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $item) {
					if ($item->isDir()) 
						mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
					else 
						copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
				}
				return 0;
			}
			catch (exception $ex) {
				echo $ex;
				return 1;
			}
		}
		
	}
?>