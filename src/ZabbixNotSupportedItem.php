<?php

class ZabbixNotSupportedItem implements InterfaceZabbixItem, InterfaceZabbixItemCreatable
{
    const ZABBIX_NOT_SUPPORTED = "ZBX_NOTSUPPORTED";

    protected $message;

    function __construct($message)
    {
        $this->message = $message;
    }

    public static function create($message)
    {
        return new ZabbixNotSupportedItem($message);
    }

    public function toValue()
    {
        return self::ZABBIX_NOT_SUPPORTED . "\0" . $this->message;
    }

}
