<?php

class ZabbixTimeDuration implements ZabbixItem {
    protected $time;

    public static function now() {
        return new ZabbixTimeDuration(time());
    }

    function __construct($time) {
        $this->time = $time;
    }

    function acceptIfNewer($timeValue) {
        if ($this->time < $timeValue) {
            $this->time = $timeValue;
        }
    }

    function getTime() {
        return $this->time;
    }

    function setTime($time) {
        $this->time = $time;
    }

    public function toValue() {
        return (string)(time() - $this->time);
    }
}
