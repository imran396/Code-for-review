<?php

namespace Sam\Rtb\Server\SocketBase\Legacy;

use Socket;

abstract class LegacySocketServerClient extends LegacySocketClient
{
    /** @var Socket|null */
    public $socketResource;
    /** @var string */
    public $remoteHost;
    /** @var int */
    public $remotePort;
    /** @var string */
    public $localAddress;
    /** @var int */
    public $localPort;

    /**
     * // TODO: signature does not correspond parent's socket::__construct()
     * socketServerClient constructor.
     * @param Socket $socketResource
     * @throws LegacySocketException
     */
    public function __construct($socketResource)
    {
        $this->socketResource = $socketResource;
        if (!$this->socketResource instanceof Socket) {
            throw new LegacySocketException("Invalid socket or resource");
        }

        if (!@socket_getsockname($this->socketResource, $this->localAddress, $this->localPort)) {
            throw new LegacySocketException(
                "Could not retrieve local address & port: "
                . socket_strerror(socket_last_error($this->socketResource))
            );
        }

        if (!@socket_getpeername($this->socketResource, $this->remoteHost, $this->remotePort)) {
            throw new LegacySocketException(
                "Could not retrieve remote address & port: "
                . socket_strerror(socket_last_error($this->socketResource))
            );
        }
        $this->enableNonBlockingMode();
        $this->onConnect();
    }
}
