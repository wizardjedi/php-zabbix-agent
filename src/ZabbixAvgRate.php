<?php

class ZabbixAvgRate implements ZabbixItem
{
    protected $time;
    protected $count = 0;

    function __construct($time)
    {
        $this->time = $time;
    }

    public static function now()
    {
        return new ZabbixAvgRate(time());
    }

    public function acquire($cnt = 1)
    {
        $this->count += $cnt;
    }

    public function toValue()
    {
        $curTime = time();

        if ($this->time == $curTime) {
            return (string)$this->count;
        } else {
            $timeDiff = time() - $this->time;
            $cnt = $this->count;

            $this->count = 0;
            $this->time = $curTime;

            return (string)($cnt / $timeDiff);
        }
    }
}