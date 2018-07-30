<?php

	/*
	*	iploya - easy deployer
	*	author: Ludwig Oberheuser<oberheuser@gmail.com>
	*	created: 19.06.2018
	*
	*	(c) 2018 Ludwig Oberheuser
	*	License: MIT
	*/
	
	namespace iploya;
	
	final class iploya {
		
		const IPLOYA_VERSION			= 'iploya 00.06a';
		private $ConsoleArguments		= [];
		private $CurrentVariables		= [];
		private $PluginReturnText		= [];
		private $Log					= [];
		private $CommandlineOutput		= true;
		private $Goto					= false;
		private $Placeholders			= false;
		private $StartTime				= null;

		/*
			process
		*/		
		public function process($jobFile, $placeholderFile=null, $consoleArguments=null) {

			/*
				set defined console arguments
			*/
			$this->ConsoleArguments = $consoleArguments;
			
			/*
				parse commandline arguments
			*/
			self::parseCommandlineArgs();

			/*
				set starttime
			*/
			$this->StartTime	= time();
			
			/*
				process commandline argument "wait" if exists
			*/
			$this->commandlineWait();
			
			/*
				Run iploya
			*/			
			$this->run($jobFile, $placeholderFile);
			
			/*
				process commandline argument "exportlog" if exists
			*/	
			$this->commandlineExportlog();
		}

		/*
			run
		*/
		private function run($jobFile, $placeholderFile) {
			
			if ($placeholderFile !== null)
				$this->Placeholders = $this->parse($placeholderFile);
				
			$parsed	= $this->parse($jobFile);
			if (isset($parsed->Commands)) {
				$this->addLog('found ' . count((array)$parsed->Commands) . ' commands, processing ..', true);
				$this->processCommands($parsed->Commands);
			}	
			else
				$this->addLog('no commands defined', true);
			
			//in the end...	
				$this->addLog('jobfile ' . $jobFile . ' processed.' . PHP_EOL . PHP_EOL, true);	
			
			exit;	
		}

		/*
			commandlineWait
		*/
		private function commandlineWait($wait = null) {
			if ($wait === null)
				return;
			$this->addLog('wait argument given, sleeping ' . $this->getCommandlineGetArgOrFalse('wait') . ' seconds ..', true);
			sleep((int)$this->getCommandlineGetArgOrFalse('wait'));
		}

		/*
			commandlineExportlog
		*/
		private function commandlineExportlog() {
			if (!$this->getCommandlineGetArgOrFalse('exportlog'))
				return;
			$this->addLog('/texportlog argument given, exporting logfile to ' . $this->getCommandlineGetArgOrFalse('exportlog') . '.', true);
			file_put_contents($this->getCommandlineGetArgOrFalse('exportlog'), $this->getCurrentLog());
		}

		/*
			processCommands
		*/
		private function processCommands($commands, $lGoto = null) {
		
			if ($lGoto !== null) 
				$this->Goto = false;
		
			foreach ($commands as $k => $v) {

				if ($lGoto !== null)
					if ($k !== $lGoto)
						continue;
						
				$lGoto = null;
				
				if ($this->Goto !== false) {
					$t = $this->Goto;
					if (!isset($commands->$t))
						$this->addLog('Goto error: command ' . $this->Goto . ' not defined.', true, true);
					else
						break;
				}

				if (isset($v->Action))
					$this->processAction($k, $v);
				else 
					$this->addLog('no action defined.', true, true);
			}	
			
			if ($this->Goto !== false)
				$this->processCommands($commands, $this->Goto);	
		}

		/*
			processAction
		*/
		private function processAction($name, $action) {
			
			$actionFilePath = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'php_plugins'.DIRECTORY_SEPARATOR.$action->Action.'.php';
			
			if (!is_file($actionFilePath))
				$this->addLog(PHP_EOL . 'no plugin for action ' . $action->Action . ' installed.' . PHP_EOL, true, true);
			
			require_once($actionFilePath);
			
			$curPlugin = 'iploya\\' . $action->Action;

			$result = $curPlugin::process($name, $this, $this->replacePlaceHolderFromArray($action->Arguments));
			
			if (isset($action->setVariable))
				$this->setVariable($action->Action, $action->setVariable, ['RESULT' => $result]);
			
			$this->addLog('[' . $name . '/' . $action->Action . "] returns " .  $result . PHP_EOL, true);

			$on = 'next';
			if (isset($action->On->$result)) {
				$on = $action->On->$result; 
			}
			else if (isset($action->On->default)) {
				$on = $action->On->default; 
			}
			
			$this->processOnTrueOrOnFalse($name, $action, $on);
		}

		/*
			setVariable
		*/
		private function setVariable($setVariable, $values) {
			if (!is_array($setVariable))
				$this->addLog('error: setVariable wrong Arguments!', true);
			if (count($setVariable) === 0)
				return;
			foreach ($setVariable as $a) 
				foreach ($a as $k => $v) 
					$this->CurrentVariables[$k] = $this->replaceStringWithArrayOfValues($this->replacePlaceHolder($v), $values);
		}

		/*
			replaceStringWithArrayOfValues
		*/
		private function replaceStringWithArrayOfValues($str, $arr) {
			foreach ($arr as $k => $v) 
				return str_replace("%%".$k."%%", $v, $str);	 
		}

		/*
			processOnTrueOrOnFalse
		*/
		private function processOnTrueOrOnFalse($name, $action, $value) {
			if (is_string($value)) {
				switch($value) {
					case "next":
					break;
					case "break":
						$this->addLog("programm will break on command " . $name, true);
						exit(0);
					break;
				}
			}
			else if (is_object($value)) {
				switch(key($value)) {
					case "Goto":
						$this->addLog("Goto .. " . $value->Goto, true);
						$this->Goto 	= $value->Goto;
					break;
					case "msg":
						var_dump($value);
						echo $value . PHP_EOL;
					break;
				}
			}
		}

		/*
			getprocessingTime
		*/		
		private function getprocessingTime() {
			return time() -  $this->StartTime;
		}

		/*
			getCurrentLog
		*/	
		private function getCurrentLog() {
			return implode("", $this->Log);
		}

		/*
			addLog
		*/	
		public function addLog($str, $addIploya=false, $throwException=false) {
			$add = PHP_EOL . ($addIploya ? 'iploya: ' : '') . date('d.m.Y H:i:s') . ' (' . $this->getprocessingTime() . "secs processing time) :" . PHP_EOL . $str;
			$this->Log[] = $add;
			if ($this->CommandlineOutput && !$this->getCommandlineArgExistsOrFalse('console', '0'))
				echo $add;	
			if ($throwException)
				throw new \Exception($add);
		}

		/*
			replacePlaceHolderFromArray
		*/	
		private function replacePlaceHolderFromArray(&$arr) {
			if (!is_array($arr))
				return $arr;
			$na = [];
			foreach ($arr as $a)
				$na[] = $this->replacePlaceHolder($a);
			return $na;
		}
		
		/*
			replacePlaceHolder
		*/			
		private function replacePlaceHolder($str) {
			$str = str_replace("%%TIMESTAMP%%", time(), $str);
			$str = str_replace("%%DATE%%", date("m.d.y"), $str);
			$str = str_replace("%%TIME%%", date("H:i:s"), $str);
			$str = str_replace("%%DATETIME%%", date("Y-m-d H:i:s"), $str);
			$str = str_replace("%%SAVEFILEDATETIME%%", date("Y-m-d H-i-s"), $str);
			$str = str_replace("%%CURRENTDIR%%", getcwd(), $str);
			$str = str_replace("%%LOG%%", $this->getCurrentLog(), $str);
			$str = str_replace("%%PROCCESSINGTIME%%", $this->getprocessingTime(), $str);
			$str = str_replace("%%IPLOYAVERSION%%", self::IPLOYA_VERSION, $str);
			
			if (is_array($this->ConsoleArguments)) 
				foreach ($this->ConsoleArguments as $k => $v)
					$str = str_replace("%%".$k."%%", $v, $str);			
			if ($this->Placeholders !== null && $this->Placeholders !== false) 
				foreach ($this->Placeholders as $k => $v)
					$str = str_replace("%%".$k."%%", $v, $str);
					
			foreach ($this->CurrentVariables as $k => $v)
				$str = str_replace("{{".$k."}}", $v, $str);
					
			return $str;
		}

		/*
			parse
		*/			
		private function parse($file) {
			$s = json_decode(file_get_contents($file));
			if (json_last_error() !== JSON_ERROR_NONE) {
					$this->addLog('could not parse file ' . $file . ' :' . json_last_error(), true);
					throw new \Exception("iploya: could not parse file " . $file . ': ' . json_last_error());	
			}
			return $s;
		}
		
		/*
			setPluginReturnText
		*/			
		private function setPluginReturnText($pluginId, $text) {
			$this->PluginReturnText[$pluginId]	= $text;
		}
		
		/*
			getCommandlineArgExistsOrFalse
		*/			
		private function getCommandlineArgExistsOrFalse($arg, $val) {
			return isset(self::$ConsoleArguments[$arg]) && self::$ConsoleArguments[$arg] === $val;
		}

		/*
			getCommandlineGetArgOrFalse
		*/	
		private function getCommandlineGetArgOrFalse($arg) {
			if (isset($this->ConsoleArguments[$arg]))
					return $this->ConsoleArguments[$arg];
			return false;
		}	

		/*
			parseCommandlineArgs
		*/	
		private function parseCommandlineArgs() {
			foreach ($this->ConsoleArguments as $k => $v) {
				if (strlen($v) < 3 || strpos($v, ':') === false || substr($v, 0, 2) !== '-t') 
					continue;
					list ($a, $b) = explode(':', substr($v, 2), 2);
					if ($a !== '' && $b !== '')
						$this->ConsoleArguments[$a] = $b; 
			}
		}
		
	}
	
?>	