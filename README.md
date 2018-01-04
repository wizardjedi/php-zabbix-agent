## php-zabbix-agent ![Build badge image](https://travis-ci.org/wizardjedi/php-zabbix-agent.svg?branch=master) [![codecov](https://codecov.io/gh/wizardjedi/php-zabbix-agent/branch/master/graph/badge.svg)](https://codecov.io/gh/wizardjedi/php-zabbix-agent)

Zabbix Agent implemented in PHP for long living php-servers

## 1. Create `composer.json` file

```json
{
   "require" : {
        "a1s/php-zabbix-agent" : "dev-master"
   },
   "minimum-stability": "dev",
   "prefer-stable": true,
   "repositories": [
        {
            "url": "https://github.com/wizardjedi/php-zabbix-agent.git",
            "type": "git"
        }
   ]
}
```

## 2. Update composer deps

```
$ composer update
```

## 3. Add `autoload.php` to your app

```php
include("vendor/autoload.php");
```

## 4. Simple script

```php
<?php

include("vendor/autoload.php");

$agent = ZabbixAgent::create(10051);

$agent->start();

$agent->setItem("some.key", ZabbixTimeDuration::now());

while (true) {
    echo "Usefull payload\n";

    $agent->tick();

    usleep(500000);
}
```

## 5. Main classes

 * `ZabbixPrimitiveItem` - holds primitive values like int, string, float. Return `var_export()`'ed string for object or array
 * `ZabbixTimeDuration` - holds duration from moment in past to current time.
   * Use `acceptIfNewer($timeValue)` to move moment near in past
 * `ZabbixAvgRate` - calculats rate of processing
   * Use `acquire($count)` method to inform item of processed objects count.

## 6. CI project page

Checkout project build status on: https://travis-ci.org/wizardjedi/php-zabbix-agent
