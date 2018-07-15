<?php
	/*
	*	iploya - easy deployer (php_plugin:core)
	*	author: Ludwig Oberheuser<oberheuser@gmail.com>
	*	created: 19.06.2018
	*
	*	(c) 2018 Ludwig Oberheuser
	*	License: MIT
	*/
	
	namespace iploya;
	class iployaCore {
		
		public static function execShell($iploya, $args) {
			
			if (!isset($args))
				return 1;
			
			$return_var = 0;
			foreach ($args as $a) {
				$output = [];
				exec ($a, $output, $return_var);
				
				if (is_array($output))
					foreach ($output as $o)
						$iploya->addLog($o);
			}
			return $return_var;
		}	
		
		public static function checkArgumentsCount($args, $MinArgs, $MaxArgs) {
			if (!is_array($args))
				throw new \Exception('wrong args format');
			if (count($args) < $MinArgs)
				throw new \Exception('wrong args count: need more arguments');
			if (count($args) > $MaxArgs)
				throw new \Exception('wrong args count: to many arguments');	
		}
	}
?>