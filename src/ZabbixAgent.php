<?php

/**
 * Class of zabbix agent server
 */
class ZabbixAgent
{
    /**
     * Items on this agent
     * @var array
     */
    protected $items = array();

    /**
     * Listen socket itself
     * @var resource
     */
    protected $listenSocket;

    /**
     * Default port for zabbix agent
     * @var int
     */
    protected $port = 10050;

    /**
     * Host for server listen socket
     * @var string
     */
    protected $host = "0.0.0.0";

    /**
     * Create zabbix agent object
     * @param string $host
     * @param int $port
     * @throws ZabbixAgentException
     */
    function __construct($host, $port)
    {
        if (empty($host)) {
            throw new ZabbixAgentException("You must set host");
        }

        if (empty($port)) {
            throw new ZabbixAgentException("You must set port");
        }

        $this->port = $port;
        $this->host = $host;
    }

    /**
     * Create zabbix agent object
     * @param int $port
     * @param string $host
     * @return \ZabbixAgent
     */
    public static function create($port, $host = "0.0.0.0")
    {
        return new ZabbixAgent($host, $port);
    }

    /**
     * Start listen socket.
     * @throws ZabbixAgentSocketException
     * @return ZabbixAgent
     */
    public function start()
    {
        $this->listenSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($this->listenSocket === false) {
            throw new ZabbixAgentSocketException('Create socket error.');
        }

        $result = socket_set_option($this->listenSocket, SOL_SOCKET, SO_REUSEADDR, 1);
        if ($result === false) {
            throw new ZabbixAgentSocketException('Set socket option error.');
        }

        $result = socket_bind($this->listenSocket, $this->host, $this->port);
        if ($result === false) {
            throw new ZabbixAgentSocketException('Socket bind error.');
        }

        $result = socket_listen($this->listenSocket, 0);
        if ($result === false) {
            throw new ZabbixAgentSocketException('Socket listen error.');
        }

        $result = socket_set_nonblock($this->listenSocket);
        if ($result === false) {
            throw new ZabbixAgentSocketException('Socket set nonblocking error.');
        }

        return $this;
    }

    /**
     * Method implements unit of work for server.
     * @throws ZabbixAgentException
     * @return ZabbixAgent
     */
    public function tick()
    {
        try {
            /**
             * @todo fix @
             */
            $connection = @socket_accept($this->listenSocket);
        } catch (Exception $e) {
            /*
             * Some implementations could transform php-errors to exceptions
             */
            throw new ZabbixAgentSocketException('Socket error on accept.');
        }

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
                socket_close($connection);
                if ($result === false) {
                    throw new ZabbixAgentSocketException('Socket write error.');
                }
            } else {
                throw new ZabbixAgentSocketException('Socket read error.');
            }
        }

        return $this;
    }

    /**
     * Get item from agent item storage
     * @param string $key
     * @return ZabbixItem
     */
    public function getItem($key)
    {
        if (!isset($this->items[$key])) {
            return new ZabbixNotSupportedItem("Key '${key}' not registered.");
        }

        return $this->items[$key];
    }

    /**
     * Set item to agent storage
     * @param string $key
     * @param ZabbixItem $val
     */
    public function setItem($key, $val)
    {
        $this->items[$key] = $val;
    }
}
