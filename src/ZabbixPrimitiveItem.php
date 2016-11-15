<?php

class ZabbixPrimitiveItem implements InterfaceZabbixItem
{
    protected $item;

    function __construct($item)
    {
        $this->item = $item;
    }

    public static function create($value)
    {
        return new ZabbixPrimitiveItem($value);
    }

    public function toValue()
    {
        if (
            is_object($this->item)
            || is_array($this->item)
        ) {
            return (string)var_export($this->item, true);
        } else {
            return (string)$this->item;
        }
    }
}
