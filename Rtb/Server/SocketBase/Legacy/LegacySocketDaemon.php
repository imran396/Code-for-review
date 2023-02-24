<?php

namespace Sam\Rtb\Server\SocketBase\Legacy;

use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Server\Daemon\SocketHelper;
use Socket;

/**
 * Class socketDaemon
 */
class LegacySocketDaemon extends CustomizableClass
{
    /** @var LegacyServer[] */
    public $serverSockets = [];
    /** @var LegacySocketClient[] */
    public $clientSockets = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $serverClass
     * @param string $clientClass
     * @param string|null $bindAddress
     * @param int|null $bindPort
     * @return LegacyServer|LegacySocketAbstract
     * @throws LegacySocketException
     */
    public function createServer(
        string $serverClass,   // httpdServer
        string $clientClass,   // httpdServerClient
        ?string $bindAddress = null,
        ?int $bindPort = null
    ) {
        /** @var LegacyServer $serverSocket */
        $serverSocket = new $serverClass($clientClass, $bindAddress, $bindPort, AF_INET, SOCK_STREAM, SOL_TCP, $this);
        if (!$serverSocket instanceof LegacyServer) {
            throw new LegacySocketException("Invalid server class specified! Has to be a subclass of LegacySocketServer");
        }
        $this->serverSockets[SocketHelper::new()->getSocketFd($serverSocket->socketResource)] = $serverSocket;
        return $serverSocket;
    }

    /**
     * @param string $clientClass
     * @param string $remoteAddress
     * @param int $remotePort
     * @param string $bindAddress
     * @param int $bindPort
     * @return LegacySocketClient|LegacySocketAbstract
     * @throws LegacySocketException
     */
    public function createClient(
        string $clientClass,
        string $remoteAddress,
        int $remotePort,
        $bindAddress = null,
        $bindPort = null
    ) {
        /** @var LegacySocketAbstract $clientSocket */
        $clientSocket = new $clientClass($bindAddress, $bindPort);
        if (!$clientSocket instanceof LegacySocketClient) {
            throw new LegacySocketException("Invalid client class specified! Has to be a subclass of LegacySocketClient");
        }
        $clientSocket->enableNonBlockingMode();
        $clientSocket->connect($remoteAddress, $remotePort);
        $clientFd = SocketHelper::new()->getSocketFd($clientSocket->socketResource);
        $this->clientSockets[$clientFd] = $clientSocket;
        return $clientSocket;
    }

    /**
     * @return LegacySocketAbstract[]
     */
    protected function getReadSocketResources(): array
    {
        $socketResources = [];
        if ($this->clientSockets) {
            foreach ($this->clientSockets as $clientSocket) {
                $socketResources[] = $clientSocket->socketResource;
            }
        }
        if ($this->serverSockets) {
            foreach ($this->serverSockets as $serverSocket) {
                $socketResources[] = $serverSocket->socketResource;
            }
        }
        return $this->filterSocketResources($socketResources);
    }

    /**
     * @return LegacySocketAbstract[]
     */
    protected function getWriteSocketResources(): array
    {
        $socketResources = [];
        if ($this->clientSockets) {
            foreach ($this->clientSockets as $clientSocket) {
                if (!empty($clientSocket->writeBuffer)
                    || $clientSocket->connecting
                ) {
                    $socketResources[] = $clientSocket->socketResource;
                }
            }
        }
        if ($this->serverSockets) {
            foreach ($this->serverSockets as $serverSocket) {
                if (!empty($serverSocket->writeBuffer)) {
                    $socketResources[] = $serverSocket->socketResource;
                }
            }
        }
        return $this->filterSocketResources($socketResources);
    }

    /**
     * @return LegacySocketAbstract[]
     */
    protected function getExceptionSocketResources(): array
    {
        $socketResources = [];
        if ($this->clientSockets) {
            foreach ($this->clientSockets as $clientSocket) {
                $socketResources[] = $clientSocket->socketResource;
            }
        }
        if ($this->serverSockets) {
            foreach ($this->serverSockets as $serverSocket) {
                $socketResources[] = $serverSocket->socketResource;
            }
        }
        return $this->filterSocketResources($socketResources);
    }

    protected function filterSocketResources(array $socketResources): array
    {
        return array_filter(
            $socketResources,
            static function ($socketResource) {
                return SocketHelper::new()->getSocketFd($socketResource) > 0;
            }
        );
    }

    protected function cleanSockets(): void
    {
        if ($this->clientSockets) {
            foreach ($this->clientSockets as $clientSocket) {
                if (
                    $clientSocket->disconnected
                    || !$clientSocket->socketResource instanceof Socket
                ) {
                    if (isset($this->clientSockets[SocketHelper::new()->getSocketFd($clientSocket->socketResource)])) {
                        unset($this->clientSockets[SocketHelper::new()->getSocketFd($clientSocket->socketResource)]);
                    }
                }
            }
        }
    }

    /**
     * @param LegacySocketAbstract $socketResource
     * @return LegacySocketClient|LegacyServer
     * @throws LegacySocketException
     */
    protected function findSocket($socketResource)
    {
        $socketFd = SocketHelper::new()->getSocketFd($socketResource);
        if (isset($this->clientSockets[$socketFd])) {
            return $this->clientSockets[$socketFd];
        }

        if (isset($this->serverSockets[$socketFd])) {
            return $this->serverSockets[$socketFd];
        }

        throw new LegacySocketException("Could not locate socket class for fd: {$socketFd}");
    }

    /**
     * @throws LegacySocketException
     */
    public function process()
    {
        // if LegacySocketClient is in write set, and $socket->connecting === true, set connecting to false and call on_connect
        $readSocketResources = $this->getReadSocketResources();
        $writeSocketResources = $this->getWriteSocketResources();
        $exceptionSocketResources = $this->getExceptionSocketResources();
        $eventTime = time();
        while (($events = @socket_select($readSocketResources, $writeSocketResources, $exceptionSocketResources, 1)) !== false) {
            if ($events > 0) {
                foreach ($readSocketResources as $readSocketResource) {
                    $socketServer = $this->findSocket($readSocketResource);
                    if ($socketServer instanceof LegacyServer) {
                        /** @var LegacyServer $socketServer */
                        /** @var LegacySocketClient $clientSocket */
                        $clientSocket = $socketServer->accept();
                        $this->clientSockets[SocketHelper::new()->getSocketFd($clientSocket->socketResource)] = $clientSocket;
                    } elseif ($socketServer instanceof LegacySocketClient) {
                        // regular on_read event
                        $socketServer->read();
                    }
                }
                foreach ($writeSocketResources as $writeSocketResource) {
                    $clientSocket = $this->findSocket($writeSocketResource);
                    if ($clientSocket instanceof LegacySocketClient) {
                        if ($clientSocket->connecting) {
                            $clientSocket->onConnect();
                            $clientSocket->connecting = false;
                        }
                        $clientSocket->doWrite();
                    }
                }
                foreach ($exceptionSocketResources as $exceptionSocketResource) {
                    $socketClient = $this->findSocket($exceptionSocketResource);
                    if ($socketClient instanceof LegacySocketClient) {
                        $socketClient->onDisconnect();
                        $socketClientFd = SocketHelper::new()->getSocketFd($socketClient->socketResource);
                        if (isset($this->clientSockets[$socketClientFd])) {
                            unset($this->clientSockets[$socketClientFd]);
                        }
                    }
                }
            }
            if (time() - $eventTime > 1) {
                // only do this if more then a second passed, else we'd keep looping this for every bit received
                foreach ($this->clientSockets as $clientSocket) {
                    $clientSocket->onTimer();
                }
                $eventTime = time();
            }

            $this->cleanSockets();
            $readSocketResources = $this->getReadSocketResources();
            $writeSocketResources = $this->getWriteSocketResources();
            $exceptionSocketResources = $this->getExceptionSocketResources();
        }
    }
}
