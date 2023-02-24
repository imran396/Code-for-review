<?php

namespace Sam\Rtb\Server\SocketBase\Legacy;

use Sam\Rtb\Server\Daemon\RtbDaemonEvent;
use Sam\Rtb\Server\Daemon\RtbDaemonLegacy;

class LegacyServer extends LegacySocketAbstract
{
    protected string $clientClass;

    /**
     * socketServer constructor.
     * @param string $clientClass
     * @param string $bindAddress
     * @param int $bindPort
     * @param int $domain
     * @param int $type
     * @param int $protocol
     * @param RtbDaemonLegacy|RtbDaemonEvent $daemon
     * @throws LegacySocketException
     */
    public function __construct(
        string $clientClass,
        $bindAddress = null,
        $bindPort = null,
        $domain = AF_INET,
        $type = SOCK_STREAM,
        $protocol = SOL_TCP,
        $daemon = null
    ) {
        parent::__construct($bindAddress, $bindPort, $domain, $type, $protocol);
        $this->setRtbDaemon($daemon);
        $this->clientClass = $clientClass;
        $this->listen();
    }

    /**
     * @return LegacySocketServerClient|LegacySocketClient|LegacySocketAbstract
     * @throws LegacySocketException
     */
    public function accept()
    {
        $client = new $this->clientClass(parent::accept(), $this->getRtbDaemon());
        if (!$client instanceof LegacySocketServerClient) {
            throw new LegacySocketException("Invalid serverClient class specified! Has to be a subclass of LegacySocketServerClient");
        }
        $this->onAccept($client);
        return $client;
    }

    // override if desired

    /**
     * @param LegacySocketServerClient $client
     */
    public function onAccept(LegacySocketServerClient $client): void
    {
    }
}
