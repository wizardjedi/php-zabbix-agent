<?php

class ZabbixPrimitiveItem implements ZabbixItem {
    protected $item;

    public static function create($value) {
        return new ZabbixPrimitiveItem($value);
    }

    function __construct($item) {
        $this->item = $item;
    }

    public function toValue() {
        if (
            is_object($this->item)
            && is_array($this->item)
        ) {
            return (string)var_export($this->item, true);
        } else {
            return (string)$this->item;
        }
    }
}
