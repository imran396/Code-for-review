<?php

namespace Sam\Rtb\Server\SocketBase\Event;

use Event;
use EventBase;
use EventBufferEvent;
use EventDnsBase;
use EventUtil;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class SocketClient
 */
class EventSocketClient extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    public string $readBuffer = '';
    public EventBufferEvent $bev;
    public EventBase $base;
    public ?int $fd = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * socket constructor.
     * @param EventBase $base
     * @return static
     */
    public function construct(EventBase $base): static
    {
        $this->base = $base;
        $this->bev = new EventBufferEvent(
            $base,
            null,
            EventBufferEvent::OPT_CLOSE_ON_FREE | EventBufferEvent::OPT_DEFER_CALLBACKS,
            [$this, "readCb"],
            [$this, "writeCb"],
            [$this, "eventCb"]
        );

        if (!isset($this->bev)) { // @phpstan-ignore-line
            $msg = "Failed to create EventBufferEvent";
            log_errorBackTrace($msg);
            throw new EventSocketException($msg);
        }

        $this->bev->enable(Event::READ | Event::WRITE);
        return $this;
    }

    public function __destruct()
    {
        if (!isset($this->bev)) {
            // log_errorBackTrace("EventBufferEvent not initialized when __destruct() is called");
            return;
        }

        $this->close();
    }

    /**
     * @param string $remoteAddress
     * @param int $remotePort
     * @throws EventSocketException
     */
    public function connect(string $remoteAddress, int $remotePort): void
    {
        if (filter_var($remoteAddress, FILTER_VALIDATE_IP)) {
            $connectionString = $remoteAddress . ($remotePort ? ':' . $remotePort : '');
            if (!$this->bev->connect($connectionString)) {
                $message = "Could not connect" . composeSuffix(['address' => $connectionString, 'error' => $this->getSocketError()]);
                log_error($message);
                throw new EventSocketException($message);
            }
        } else {
            $dnsBase = new EventDnsBase($this->base, true);

            if (!$this->bev->connectHost($dnsBase, $remoteAddress, $remotePort)) {
                $message = "Could not connect" . composeSuffix(['address' => "{$remoteAddress}:{$remotePort}", 'error' => $this->getSocketError()]);
                log_error($message);
                throw new EventSocketException($message);
            }
        }
    }

    /**
     * @param EventBufferEvent $bev
     * @param mixed $arg
     */
    public function readCb(EventBufferEvent $bev, mixed $arg = null): void
    {
        $this->read();
        $this->onRead();
    }

    /**
     * @param EventBufferEvent $bev
     * @param mixed $arg
     */
    public function writeCb(EventBufferEvent $bev, mixed $arg = null): void
    {
        $this->onWrite();
    }

    /**
     * @param EventBufferEvent $bev
     * @param int $events
     * @param mixed $arg
     */
    public function eventCb(EventBufferEvent $bev, int $events, mixed $arg = null): void
    {
        if ($events & (EventBufferEvent::EOF | EventBufferEvent::ERROR)) {
            if ($events & EventBufferEvent::ERROR) {
                $msg = "Error from bufferevent - " . $this->getSocketError();
                log_error($msg);
            }
            $this->close();
            $this->onDisconnect();
        }

        if ($events & EventBufferEvent::CONNECTED) {
            $this->fd = $this->bev->fd;
            log_debug('Connected' . composeSuffix(['socket fd' => $this->fd]));
        }
    }

    /**
     * @param int $length
     * @return string
     */
    public function read(int $length = 8192): string
    {
        try {
            $read = $this->doRead($length);
            $this->readBuffer .= $read;
            $this->onRead();
            return $read;
        } catch (EventSocketException) {
            $this->close();
            $this->onDisconnect();
            return '';
        }
    }

    /**
     * @param int $length
     * @return string
     * @throws EventSocketException
     */
    public function doRead(int $length = 8192): string
    {
        if (!($this->bev->getEnabled() & Event::READ)) {
            $message = "READ not enabled";
            log_error($message);
            throw new EventSocketException($message);
        }

        $ret = $this->bev->read($length);
        log_trace(static fn() => mb_check_encoding($ret, 'UTF-8') ? $ret : bin2hex($ret));
        return $ret;
    }

    /**
     * @param string $request
     * @param int $length
     * @return int
     * @throws EventSocketException
     */
    public function write(string $request, int $length = 8192): int
    {
        if (!($this->bev->getEnabled() & Event::WRITE)) {
            $message = "WRITE not enabled";
            log_error($message);
            throw new EventSocketException($message);
        }

        log_debug("Writing to output buffer: " . $request);
        $ret = $this->bev->output->add($request);
        if ($ret === false) {
            $message = "Failed to add to write buffer";
            log_error($message);
            throw new EventSocketException($message);
        }
        $ret = strlen($request);

        return $ret;
    }

    public function close(): void
    {
        if (!isset($this->bev)) {
            //log_errorBackTrace("EventBufferEvent not initialized when closing connection");
            return;
        }

        log_trace('Closing socket' . composeSuffix(['Socket fd' => $this->fd]));
        $this->bev->close();
        $this->bev->free();
        unset($this->bev);
    }

    /**
     * @return string
     */
    public function getSocketError(): string
    {
        return sprintf(
            "ERROR %d (%s)",
            @EventUtil::getLastSocketErrno($this->fd),
            @EventUtil::getLastSocketError($this->fd)
        );
    }

    /**
     * Return log data as array
     * @return array
     */
    public function logData(): array
    {
        return ['Socket fd' => $this->fd];
    }

    /**
     * Return log information as string
     * @return string
     */
    public function logInfo(): string
    {
        return composeLogData($this->logData());
    }

    public function onConnect(): void
    {
    }

    public function onDisconnect(): void
    {
    }

    public function onRead(): void
    {
    }

    public function onWrite(): void
    {
    }

    public function onTimer(): void
    {
    }
}
