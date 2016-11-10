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
        if (empty($host)) {
            throw new ZabbixAgentException("You must set host");
        }

        if (empty($port)) {
            throw new ZabbixAgentException("You must set port");
        }

        $this->port = $port;
        $this->host = $host;
    }

    public function start() {
        $this->listenSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($this->listenSocket === false) {
            $errorCode = socket_last_error();

            $errorMsg = socket_strerror($errorCode);

            throw new ZabbixAgentException('Create socket error. '.$errorMsg, $errorCode);
        }

        $result = socket_set_option($this->listenSocket, SOL_SOCKET, SO_REUSEADDR, 1);
        if ($result === false) {
            $errorCode = socket_last_error();

            $errorMsg = socket_strerror($errorCode);

            throw new ZabbixAgentException('Set socket option error.'.$errorMsg, $errorCode);
        }

        $result = socket_bind($this->listenSocket, $this->host, $this->port);
        if ($result === false) {
            $errorCode = socket_last_error();

            $errorMsg = socket_strerror($errorCode);

            throw new ZabbixAgentException('Socket bind error.'.$errorMsg, $errorCode);
        }

        $result = socket_listen($this->listenSocket, 0);
        if ($result === false) {
            $errorCode = socket_last_error();

            $errorMsg = socket_strerror($errorCode);

            throw new ZabbixAgentException('Socket listen error.'.$errorMsg, $errorCode);
        }

        $result = socket_set_nonblock($this->listenSocket);
        if ($result === false) {
            $errorCode = socket_last_error();

            $errorMsg = socket_strerror($errorCode);

            throw new ZabbixAgentException('Socket set nonblocking error.'.$errorMsg, $errorCode);
        }
    }

    public function tick() {
        $connection = @socket_accept($this->listenSocket);

        if ($connection > 0) {
            $commandRaw = socket_read($connection, 1024);

            if ($commandRaw !== false) {
                $command = trim($commandRaw);

                try {
                    $agentItem = $this->getItem($command);

                    $buf = ZabbixProtocol::serialize($agentItem);
                } catch (Exception $e) {
                    socket_close($connection);

                    throw new ZabbixAgentException("Serialize item error.", 0, $e);
                }

                $result = socket_write($connection, $buf, strlen($buf));
                if ($result === false) {
                    $errorCode = socket_last_error();

                    $errorMsg = socket_strerror($errorCode);

                    throw new ZabbixAgentException('Socket write error.'.$errorMsg, $errorCode);
                }

                socket_close($connection);
            }
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
