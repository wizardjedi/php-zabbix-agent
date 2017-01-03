<?php

class ZabbixAgentSocketException extends ZabbixAgentException
{
    public function __construct($message, $code = null, Exception $previous = null)
    {
        $errorCode = socket_last_error();
        $errorMsg = socket_strerror($errorCode);

        parent::__construct($message . $errorMsg, $code, $previous);
    }
}
