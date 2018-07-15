# iploya - your process automater.


## Install iploya:

* git clone https://github.com/w3hr/iploya/
* create a jobfile
* process your jobfile
* have fun


with iploya u can easily process "iploya process files":

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


u can combine multiple "Commands":

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


And u can react on returncode:

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


# On

Command | Result | Example
------------ | ------------- | -------------
next | jumps to next command | next
break | stopps processing | break
goto action id | gos to action id | goto Btwyk



# Placeholders

You can also use placeholders, standart placeholders are:

Placeholer | Result
------------ | -------------
%%TIMESTAMP%% | Current unix timestamp
%%DATE%% | Current date in m.d.y Format
%%TIME%% | Current time in H:i:s Format
%%DATETIME%% | Current full date in Y-m-d H:i:s format
%%SAVEFILEDATETIME%% | Current date in filesave Y-m-d H-i-s format
%%CURRENTDIR%% | Current working directory
%%LOG%% | gos to action id
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

# Current php_plugins:

* copyDirectory
* execShell
* linuxGzipDirectory
* monoCSCBuild
* replaceFileString
* gitCheckoutBare
* linuxKillScreen
* monoXBuild
* wait
* createDirectory
* gitCreateRepo
* linuxSendmail
* msg
* writeStringToFile
* deleteFile
* gitStageAndCommit
* linuxStartProcessInScreen
* removeDirectory


# if u want to build ur own php plugin, u can do it:

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
