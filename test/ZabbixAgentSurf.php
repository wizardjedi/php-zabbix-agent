<?php

class ZabbixAgentSurf extends ZabbixAgent {
    public function getHost() {
        return $this->host;
    }

    public function getPort() {
        return $this->port;
    }
}
