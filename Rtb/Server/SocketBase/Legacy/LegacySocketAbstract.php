<?php

namespace Sam\Rtb\Server\SocketBase\Legacy;

use Sam\Rtb\Server\Daemon\RtbDaemonAwareTrait;
use Sam\Rtb\Server\Daemon\SocketHelper;
use Socket;

/**
 * Class socket
 */
abstract class LegacySocketAbstract
{
    use RtbDaemonAwareTrait;

    /** @var string|int */
    public $bindAddress;
    /** @var int */
    public $bindPort;
    /** @var int */
    public $domain;
    /** @var string */
    public $localAddress;
    /** @var int */
    public $localPort;
    /** @var int */
    public $protocol;
    /** @var string */
    public $readBuffer = '';
    /** @var string */
    public $remoteHost;
    /** @var int */
    public $remotePort;
    /** @var Socket|null */
    public $socketResource;
    /** @var int */
    public $type;
    /** @var string */
    public $writeBuffer = '';

    /**
     * socket constructor.
     * @param string $bindAddress
     * @param int $bindPort
     * @param int $domain
     * @param int $type
     * @param int $protocol
     * @throws LegacySocketException
     */
    public function __construct(
        $bindAddress = null,
        $bindPort = null,
        $domain = AF_INET,
        $type = SOCK_STREAM,
        $protocol = SOL_TCP
    ) {
        $this->bindAddress = $bindAddress ? (string)$bindAddress : '127.0.0.1';
        $this->bindPort = (int)$bindPort;
        $this->domain = (int)$domain;
        $this->type = (int)$type;
        $this->protocol = (int)$protocol;
        $this->socketResource = @socket_create($this->domain, $this->type, $this->protocol);
        if (!$this->socketResource instanceof Socket) {
            $message = "Could not create socket: " . socket_strerror(socket_last_error());
            log_error($message);
            throw new LegacySocketException($message);
        }
        if (!@socket_set_option($this->socketResource, SOL_SOCKET, SO_REUSEADDR, 1)) {
            throw new LegacySocketException("Could not set SO_REUSEADDR: " . $this->getSocketError());
        }
        if (!@socket_bind($this->socketResource, $this->bindAddress, $this->bindPort)) {
            $message = "Could not bind socket to [{$this->bindAddress} - {$this->bindPort}]: "
                . socket_strerror(socket_last_error($this->socketResource));
            log_error($message);
            throw new LegacySocketException($message);
        }
        if (!@socket_getsockname($this->socketResource, $this->localAddress, $this->localPort)) {
            $message = "Could not retrieve local address & port: " . socket_strerror(socket_last_error($this->socketResource));
            throw new LegacySocketException($message);
        }
        $this->enableNonBlockingMode();
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->socketResource instanceof Socket) {
            $this->close();
        }
    }

    /**
     * @return string
     */
    public function getSocketError()
    {
        $error = socket_strerror(socket_last_error($this->socketResource));
        socket_clear_error($this->socketResource);
        return $error;
    }

    public function close()
    {
        if ($this->socketResource instanceof Socket) {
            @socket_shutdown($this->socketResource);
            @socket_close($this->socketResource);
        }
        $this->socketResource = null;
    }

    /**
     * @param string $buffer
     * @param int $length
     * @return int
     * @throws LegacySocketException
     */
    public function write($buffer, $length = 8192)
    {
        if (!$this->socketResource instanceof Socket) {
            throw new LegacySocketException("Invalid socket or resource");
        }

        if (($ret = @socket_write($this->socketResource, $buffer, $length)) === false) {
            $message = "Could not write to socket: " . $this->getSocketError();
            log_error($message);
            throw new LegacySocketException($message);
        }
        return (int)$ret;
    }

    /**
     * @param int $length
     * @return string
     * @throws LegacySocketException
     */
    public function read($length = 8192)
    {
        if (!$this->socketResource instanceof Socket) {
            throw new LegacySocketException("Invalid socket or resource");
        }

        $ret = @socket_read($this->socketResource, $length);
        if ($ret === '') {
            throw new LegacySocketException("Could not read from socket: " . $this->getSocketError());
        }
        //$ret = str_replace(chr(255), '', $ret); // remove chr 255 character
        //$ret = str_replace(chr(0), '', $ret); // remove chr 0 character
        return $ret; // remove chr 255 character
    }

    /**
     * @param string $remoteAddress
     * @param int $remotePort
     * @throws LegacySocketException
     */
    public function connect($remoteAddress, $remotePort)
    {
        $this->remoteHost = (string)$remoteAddress;
        $this->remotePort = (int)$remotePort;
        if (!$this->socketResource instanceof Socket) {
            throw new LegacySocketException("Invalid socket or resource");
        }

        if (!@socket_connect($this->socketResource, $remoteAddress, $remotePort)) {
            $message = "Could not connect to {$remoteAddress}:{$remotePort} - " . $this->getSocketError();
            log_error($message);
            throw new LegacySocketException($message);
        }
    }

    /**
     * @param int $backlog
     * @throws LegacySocketException
     */
    public function listen($backlog = 128)
    {
        if (!$this->socketResource instanceof Socket) {
            throw new LegacySocketException("Invalid socket or resource");
        }

        if (!@socket_listen($this->socketResource, $backlog)) {
            $message = "Could not listen to {$this->bindAddress}:{$this->bindPort} - " . $this->getSocketError();
            log_error($message);
            throw new LegacySocketException($message);
        }
    }

    /**
     * @return Socket
     * @throws LegacySocketException
     */
    public function accept()
    {
        if (!$this->socketResource instanceof Socket) {
            throw new LegacySocketException("Invalid socket or resource");
        }

        $clientSocket = socket_accept($this->socketResource);
        if (!$clientSocket instanceof Socket) {
            $message = "Could not accept connection to {$this->bindAddress}:{$this->bindPort} - " . $this->getSocketError();
            log_error($message);
            throw new LegacySocketException($message);
        }
        return $clientSocket;
    }

    /**
     * @throws LegacySocketException
     */
    public function enableNonBlockingMode()
    {
        if (!$this->socketResource instanceof Socket) {
            throw new LegacySocketException("Invalid socket or resource");
        }

        if (!@socket_set_nonblock($this->socketResource)) {
            $message = "Could not set socket non_block: " . $this->getSocketError();
            log_error($message);
            throw new LegacySocketException($message);
        }
    }

    /**
     * @throws LegacySocketException
     */
    public function enableBlockingMode()
    {
        if (!$this->socketResource instanceof Socket) {
            throw new LegacySocketException("Invalid socket or resource");
        }

        if (!@socket_set_block($this->socketResource)) {
            $message = "Could not set socket non_block: " . $this->getSocketError();
            log_error($message);
            throw new LegacySocketException($message);
        }
    }

    /**
     * @param int $sec
     * @param int $usec
     * @throws LegacySocketException
     */
    public function enableReceiveTimeout($sec, $usec)
    {
        if (!$this->socketResource instanceof Socket) {
            throw new LegacySocketException("Invalid socket or resource");
        }

        if (!@socket_set_option($this->socketResource, SOL_SOCKET, SO_RCVTIMEO, ["sec" => $sec, "usec" => $usec])) {
            $message = "Could not set socket receive timeout: " . $this->getSocketError();
            log_error($message);
            throw new LegacySocketException($message);
        }
    }

    /**
     * @param bool|int $enable
     * @throws LegacySocketException
     */
    public function enableReuseAddress($enable = true)
    {
        $enable = (int)$enable;
        if (!$this->socketResource instanceof Socket) {
            throw new LegacySocketException("Invalid socket or resource");
        }

        if (!@socket_set_option($this->socketResource, SOL_SOCKET, SO_REUSEADDR, $enable)) {
            $message = "Could not set SO_REUSEADDR to '$enable': " . $this->getSocketError();
            throw new LegacySocketException($message);
        }
    }

    /**
     * Return log data as array
     * @return array
     */
    public function logData(): array
    {
        return ['#' => SocketHelper::new()->getSocketFd($this->socketResource)];
    }

    /**
     * Return log information as string
     * @return string
     */
    public function logInfo(): string
    {
        return composeLogData($this->logData());
    }
}
