<?php

class ZabbixAgent {
    protected $items;

    protected $listenSocket;

    protected $port = 10050;

    protected $host = "0.0.0.0";

    /**
     *
     * @param type $port
     * @param type $host
     * @return \ZabbixAgent
     */
    public static function create($port, $host = "0.0.0.0") {
        return new ZabbixAgent($host, $port);
    }

    function __construct($host, $port) {
        $this->port = $port;
        $this->host = $host;
    }

    public function start() {
        $this->listenSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        socket_set_option($this->listenSocket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->listenSocket, $this->host, $this->port);

        socket_listen($this->listenSocket, 0);

        socket_set_nonblock($this->listenSocket);
    }

    public function tick() {
        $connection = @socket_accept($this->listenSocket);

        if ($connection > 0) {
            $commandRaw = socket_read($connection, 1024);

            $command = trim($commandRaw);

            $agentItem = $this->getItem($command);

            $buf = ZabbixProtocol::serialize($agentItem);

            socket_write($connection, $buf, strlen($buf));

            socket_close($connection);
        }
    }

    /**
     *
     * @param type $key
     * @return ZabbixItem
     */
    public function getItem($key) {
        return $this->items[$key];
    }

    /**
     *
     * @param type $key
     * @param type $val
     */
    public function setItem($key, $val) {
        $this->items[$key] = $val;
    }
}
