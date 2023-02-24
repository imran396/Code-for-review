<?php

namespace Sam\Rtb\Server\SocketBase\Legacy;

/**
 * Class socketClient
 */
class LegacySocketClient extends LegacySocketAbstract
{
    /** @var bool */
    public $connecting = false;
    /** @var bool */
    public $disconnected = false;

    /**
     * @param string $remoteAddress
     * @param int $remotePort
     */
    public function connect($remoteAddress, $remotePort)
    {
        $this->connecting = true;
        try {
            parent::connect($remoteAddress, $remotePort);
        } catch (LegacySocketException $e) {
            log_error(composeLogData(["Caught exception" . $e->getMessage()]));
        }
    }

    /**
     * @param $buffer
     * @param int $length
     * @return void
     */
    public function write($buffer, $length = 8192)
    {
        $this->writeBuffer .= $buffer;
        $this->doWrite();
    }

    /**
     * @return bool
     */
    public function doWrite()
    {
        $length = strlen($this->writeBuffer);
        try {
            $written = parent::write($this->writeBuffer, $length);
            if ($written < $length) {
                $this->writeBuffer = substr($this->writeBuffer, $written);
            } else {
                $this->writeBuffer = '';
            }
            $this->onWrite();
            return true;
        } catch (LegacySocketException) {
            $this->close();
            $this->socketResource = null;
            $this->disconnected = true;
            $this->onDisconnect();
            return false;
        }
    }

    /**
     * @param int $length
     * @return void
     */
    public function read($length = 8192)
    {
        try {
            $this->readBuffer .= parent::read($length);
            $this->onRead();
        } catch (LegacySocketException) {
            // $oldSocketResource = SocketHelper::new()->getSocketFd($this->socketResource);
            $this->close();
            // $this->socketResource = $oldSocketResource;
            $this->socketResource = null;
            $this->disconnected = true;
            $this->onDisconnect();
        }
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
