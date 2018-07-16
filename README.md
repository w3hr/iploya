# iploya - your process automater.


## Install iploya:

* git clone https://github.com/w3hr/iploya/
* create a jobfile
* process your jobfile
* have fun


#### with iploya u can easily process "iploya process files":

```javascript
{
  "Description": "Simple create directory example",
  "Version": "iploya 00.05a",
  "Commands": {
    "NGxgQ": {
      "Action": "createDirectory",
      "Arguments": [
        "/home/foo/bar/"
      ],
      "SetVariable": [],
      "On": {
        "0": "next"
      }
    }
  }
}
```


#### u can combine multiple "Commands":

```javascript
{
  "Description": "Simple message output with an sleep timer and placeholders",
  "Version": "iploya 00.05a",
  "Commands": {
    "PoRLO": {
      "Action": "wait",
      "Arguments": [
        2
      ],
      "SetVariable": [],
      "On": {
        "0": "next"
      }
    },
    "BMyz4": {
      "Action": "msg",
      "Arguments": [
        "Hey itse me on current datetime %%DATETIME%% (%%IPLOYAVERSION%%)"
      ],
      "SetVariable": [],
      "On": {
        "0": "next"
      }
    }
  }
}
```

### detailed description

```
"Description": "Simple create directory example", < -- not necessary only for example
  "Version": "iploya 00.05a", < -- not necessary ( but maybe in future, so its better to define it )
  "Commands": { < -- the "Commands" block of the processing file
    "NGxgQ": { < -- the id (have to be unique!) of the "Command" action
      "Action": "createDirectory", < -- the "Action" ( plugin name )
      "Arguments": [ < -- Arguments that will be the parameters of the plugin
        "/home/foo/bar/" < --  argument 1
      ],
      "SetVariable": [], < -- set variable block
      "On": { < -- on block
        "0": "next" < -- on returncode 0 goto next
      }
    }
```

#### And u can react on returncode:

this example will try to build a sln, if if fails he will wait 10 seconds and try again

```javascript
{
 "Description": "Simple moxoXBuild with react on 0 and default",
  "Version": "iploya 00.04a",
  "Commands": {
    "1Tgjz": {
      "Action": "monoXBuild",
      "Arguments": [
        "/coolproject/coolproject.sln",
        "/p:Configuration=Release /p:TargetFrameworkVersion='v4.5'"
      ],
      "SetVariable": [],
      "On": {
        "0": "goto Btwyk",
        "default": "next"
      }
    },
    "kxw6G": {
      "Action": "wait",
      "Arguments": [
        10
      ],
      "SetVariable": [],
      "On": {
        "0": "goto 1Tgjz",
      }
    },
    "Btwyk": {
      "Action": "linuxSendmail",
      "Arguments": [
        "fo@oo.bar",
        "hey my friend your build works!",
        "build completed heres the log: %%LOG%%"
      ],
      "SetVariable": [],
      "On": {
        "0": "break"
      }
    }
  }
}
```
#### Current php_plugins:

In Arguments you can use Placeholders and setVariables

Plugin/Actionname | Arguments | Result | OS
------------ | ------------- | ------------- | -------------
copyDirectory | [source, destination] | copy a directory from source to destination (recursively) | *
createDirectory | [directory path] | create a new directory | *
deleteFile | [file path] | delete a file | *
execShell | [shell command] | execute a shell command | *
gitCheckoutBare | [work tree folder, git repo folder, git branch name] | checkout a git bare repo branch to a specific folder | *
gitCreateRepo | [path] | create a (non-bare) git repo | *
gitStageAndCommit | [git repo path, commit message] | commits (staged) all current changes on a git repo | *
linuxGzipDirectory | [source, destination] | gzip a directory from source to target | linux
linuxKillScreen | [screen id] | kills a running linux screen | linux
linuxSendmail | [to email, description, text] | sends a email with linux sendmail | linux
linuxStartProcessInScreen | [screen id, execute file path, execute command] | starts a process in a new screen | linux
monoCSCBuild | [path to cs file] | builds a .cs file | linux
monoXBuild | [path to sln file, buildoptions] | builds a .sln file with buildoptions | linux
msg | [message] | simple echo message | *
removeDirectory | [path to directory] | removes a directory (recursively) | *
replaceFileString | [path to replace file, replace search, replace with, OPTIONAL new file path] | replace a string in a file | *
wait | [seconds as integer| wait x seconds | *
writeStringToFile | [filepath, string] | (over-)write a string to a file | *


#### On

With On you can react on returncodes of php_plugins

in the following example, on returncode 0 it will go to next, on 1 it will break the process.
```javascript
	"On": {
	"0": "next",
	"1": "break"
	}
```
possible react possibilities:

Command | Result | Example
------------ | ------------- | -------------
next | jumps to next command | next
break | stopps processing | break
goto action id | go's to action id | goto Btwyk

#### Placeholders

You can also use placeholders, standart placeholders are:

Placeholer | Result
------------ | -------------
%%TIMESTAMP%% | Current unix timestamp
%%DATE%% | Current date in m.d.y Format
%%TIME%% | Current time in H:i:s Format
%%DATETIME%% | Current full date in Y-m-d H:i:s format
%%SAVEFILEDATETIME%% | Current date in filesave Y-m-d H-i-s format
%%CURRENTDIR%% | Current working directory
%%LOG%% | go's to action id
%%PROCCESSINGTIME%% | Current log output
%%IPLOYAVERSION%% | Current processing time in milliseconds 

its possible to add placeholders as commandline argument 

php iploya.php "Z:\job1.json" -tFoo:Bar

now u can use %%Foo%% as a Placeholder and it will result Bar

and its also possible to load an external "placeholder" file:

php iploya.php "Z:\job2.json" "Z:\placeholdersforjob1.json"

if the file has content like this:

```javascript
{
	"NAME": "Mr. Foo",
	"LASTNAME": "Bar"
}
```

u can use it as %%NAME%% and %%LASTNAME%%

#### SetVariable

In all Commands u can define your own Variables.

Example: 

..
"SetVariable": [ { "Foo": "Bar" } ],
..

Now u can use this like a placeholder, for your own defined variables use {{Foo}}.
You can also use placeholders in your SetVariable Statement:

..
"SetVariable": [ { "ActionACalledAt": "%%TIMESTAMP%%" } ],
..

```javascript
...
    "BMyz4": {
      "Action": "msg",
      "Arguments": [
        "Hey, i only want to say that ActionACalledAt at {{ActionACalledAt}}"
      ],
      "SetVariable": [],
      "On": {
        "0": "next"
      }
    }
...
```
(remember to use {{FOO}} instead of %%FOO%% for your own defined variables)

#### your own php_plugin
if u want to build ur own php plugin, u can do it:

```php
	namespace iploya;
	require_once(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'core.php');
	
	class createDirectory extends iployaCore {
		
		const MinArgs	= //MinArgs;
		const MaxArgs	= //MaxArgs;
		
		public static function process($action, $iploya, $args)  {
			/*
				checkArgumentsCount
			*/
			self::checkArgumentsCount($args, self::MinArgs, self::MaxArgs);
			
			
			try {			
              //Your code here
			}
			catch (exception $ex) {
				
              //Your exception handling here (most return 1 to tell iploya something goes wrong)
        
			}
			
		}
	}
```
