<?php

class ZabbixAvgRate implements ZabbixItem {
    protected $time;
    protected $count;

    public static function now() {
        return new ZabbixAvgRate(time());
    }

    function __construct($time) {
        $this->time = $time;
        $this->count = 0;
    }

    public function acquire($cnt = 1) {
        $this->count += $cnt;
    }

    public function toValue() {
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

        return (string)(time() - $this->time);
    }
}