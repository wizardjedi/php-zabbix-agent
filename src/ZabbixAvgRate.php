<?php

class ZabbixAvgRate implements InterfaceZabbixItem, InterfaceZabbixItemTime
{
    protected $time;
    protected $count = 0;

    function __construct($time)
    {
        $this->time = $time;
    }

    public static function now()
    {
        return new self(time());
    }

    public function acquire($cnt = 1)
    {
        $this->count += $cnt;
    }

    public function toValue()
    {
        $curTime = time();

        if ($this->getTime() == $curTime) {
            return (string)$this->count;
        } else {
            $timeDiff = time() - $this->getTime();
            $cnt = $this->count;

            $this->count = 0;
            $this->setTime($curTime);

            return (string)($cnt / $timeDiff);
        }
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }
}