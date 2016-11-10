# php-zabbix-agent

Zabbix Agent implemented in PHP for long living php-servers

1. Create `composer.json` file

```
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

2. Update composer deps

```
$ composer update
```

3. Add `autoload.php` to your app

```php
include("vendor/autoload.php");
```

4. Simple script

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
