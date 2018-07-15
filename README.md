# iploya 
your process automater.

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

this example will try to build a sln, if if fails (default) he will wait 10 seconds and try again

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
