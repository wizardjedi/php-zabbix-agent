<?php

class ZabbixNotSupportedItem implements ZabbixItem {
    const ZABBIX_NOT_SUPPORTED = "ZBX_NOTSUPPORTED";

    protected $message;

    public function create($message) {
        return new ZabbixNotSupportedItem($message);
    }

    function __construct($message) {
        $this->message = $message;
    }

    public function toValue() {
        return self::ZABBIX_NOT_SUPPORTED."\0".$this->message;
    }

}
