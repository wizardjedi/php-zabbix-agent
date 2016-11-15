<?php

interface InterfaceZabbixItemTime
{
    public static function now();

    public function getTime();

    public function setTime($time);
}
