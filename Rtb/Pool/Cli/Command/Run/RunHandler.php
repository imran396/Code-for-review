<?php
/**
 * Update command handler, it refreshes auction bindings to rtbd instances in pool
 *
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Cli\Command\Run;

use InvalidArgumentException;
use Sam\Core\Cli\HandlerBase;
use Sam\Infrastructure\OutputBuffer\OutputBuffer;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Sam\Rtb\Pool\Instance\RtbdDescriptor;
use Sam\Rtb\Pool\Instance\RtbdNameAwareTrait;
use Sam\Core\Constants;
use Sam\Rtb\Server\Daemon\RtbDaemonEvent;
use Sam\Rtb\Server\Daemon\RtbDaemonLegacy;

error_reporting(E_ALL | E_STRICT);
OutputBuffer::new()->completeEndFlush();
set_time_limit(0);

/**
 * Class RunHandler
 * @package Sam\Rtb\Pool\Cli\Command\Update
 */
class RunHandler extends HandlerBase
{
    use ConfigRepositoryAwareTrait;
    use RtbdNameAwareTrait;
    use RtbdPoolConfigManagerAwareTrait;

    protected int $resultCode = Constants\Cli::EXIT_SUCCESS;
    private ?RtbdDescriptor $descriptor = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->initFileLog();

        $descriptor = $this->getDescriptor();
        $bindHost = $descriptor->getBindHost();
        $bindPort = $descriptor->getBindPort();
        $logData = $descriptor->toArray();
        $logData['log'] = $this->getDescriptor()->getLogFileName();
        $logData['event'] = $this->cfg()->get('core->rtb->server->eventLoop');
        $logData['php'] = PHP_VERSION;
        $this->output('Starting rtbd instance from pool' . composeSuffix($logData));

        if ($this->cfg()->get('core->rtb->server->eventLoop')) {
            $daemon = RtbDaemonEvent::new();
        } else {
            $daemon = RtbDaemonLegacy::new();
        }
        $daemon->construct($bindHost, $bindPort);
        $daemon->setName($this->getRtbdName());
        $daemon->setPidFileName($this->getDescriptor()->getPidFileName());
        $daemon->process();
    }

    /**
     * @return RtbdDescriptor
     */
    public function getDescriptor(): RtbdDescriptor
    {
        if ($this->descriptor === null) {
            $this->descriptor = $this->getRtbdPoolConfigManager()->getDescriptorByName($this->getRtbdName());
            if ($this->descriptor === null) {
                // JIC check. It should be impossible situation, because rtbd name availability is checked in RunCommand
                throw new InvalidArgumentException(
                    'Cannot load rtbd descriptor by name'
                    . composeSuffix(['rtbd name' => $this->getRtbdName()])
                );
            }
        }
        return $this->descriptor;
    }

    /**
     * @param RtbdDescriptor $descriptor
     * @return static
     * @noinspection PhpUnused
     */
    public function setDescriptor(RtbdDescriptor $descriptor): static
    {
        $this->descriptor = $descriptor;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getResultCode(): ?int
    {
        return $this->resultCode;
    }

    protected function initFileLog(): void
    {
        ini_set('error_log', path()->log() . '/' . $this->getDescriptor()->getLogFileName());
    }
}
