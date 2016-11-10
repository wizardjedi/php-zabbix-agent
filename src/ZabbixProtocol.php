<?php

final class ZabbixProtocol {
    const ZABBIX_MAGIC = "ZBXD";

    const ZABBIX_DELIMETER = 1;

    public static function getHeader() {
        return self::ZABBIX_MAGIC.pack("C", self::ZABBIX_DELIMETER);
    }

    public static function getLength($value){
        $len = strlen($value);

        $lo = (int)$len & 0x00000000FFFFFFFF;

        $hi = ((int)$len & 0xFFFFFFFF00000000) >> 32;

        return pack("V*", $lo, $hi);
    }

    public static function serialize(ZabbixItem $item) {
        $value = $item->toValue();

        return self::getHeader().self::getLength($value).$value;
    }
}
