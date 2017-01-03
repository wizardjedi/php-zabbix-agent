<?php

class ZabbixTimeDuration implements InterfaceZabbixItem, InterfaceZabbixItemTime
{
    protected $time;

    function __construct($time)
    {
        $this->time = $time;
    }

    public function acceptIfNewer($timeValue)
    {
        if ($this->getTime() < $timeValue) {
            $this->setTime($timeValue);
        }
    }

    public static function now()
    {
        return new self(time());
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

    public function toValue()
    {
        return (string)(time() - $this->getTime());
    }
}
