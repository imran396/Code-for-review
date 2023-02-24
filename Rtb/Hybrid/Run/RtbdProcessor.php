<?php
/**
 * Hybrid auction rtbd processing
 *
 * SAM-3775: Rtbd improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 01, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * setClients($clients) - defines connected sockets
 */

namespace Sam\Rtb\Hybrid\Run;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Hybrid\Run\Base\HybridResponseSenderAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonAwareTrait;

/**
 * Class RtbdProcessor
 * @package Sam\Rtb\Hybrid\Run
 */
class RtbdProcessor extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use HybridResponseSenderAwareTrait;
    use RtbDaemonAwareTrait;

    /**
     * Last run timestamp
     */
    protected int $runTs = 0;
    /**
     * Process execution interval
     * Not necessary currently, because we select sockets with 1 second interval in caller method RtbDaemon::process()
     * @var int Seconds
     */
    protected int $timeout = 1;
    protected ?RunningAuction\Processor $runningAuctionProcessor = null;
    protected ?ReadyStartAuction\Processor $readyStartAuctionProcessor = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Main running method
     */
    public function process(): void
    {
        if (!$this->cfg()->get('core->auction->hybrid->enabled')) {
            return;
        }

        if ($this->timeoutExpired()) {
            $this->runTs = time();
            $this->getRunningAuctionProcessor()->process();
            $this->getReadyStartAuctionProcessor()->process();
        }
    }

    /**
     * Set sockets currently are connected to rtbd
     * @param array $clients
     * @return static
     */
    public function setClients(array $clients): static
    {
        $this->getHybridResponseSender()->construct($clients);
        return $this;
    }

    /**
     * @return RunningAuction\Processor
     */
    public function getRunningAuctionProcessor(): RunningAuction\Processor
    {
        if ($this->runningAuctionProcessor === null) {
            $this->runningAuctionProcessor = RunningAuction\Processor::new()
                ->setHybridResponseSender($this->getHybridResponseSender())
                ->setRtbDaemon($this->getRtbDaemon());
        }
        return $this->runningAuctionProcessor;
    }

    /**
     * @param RunningAuction\Processor $runningAuctionProcessor
     * @return static
     * @noinspection PhpUnused
     */
    public function setRunningAuctionProcessor(RunningAuction\Processor $runningAuctionProcessor): static
    {
        $this->runningAuctionProcessor = $runningAuctionProcessor;
        return $this;
    }

    /**
     * @return ReadyStartAuction\Processor
     */
    public function getReadyStartAuctionProcessor(): ReadyStartAuction\Processor
    {
        if ($this->readyStartAuctionProcessor === null) {
            $this->readyStartAuctionProcessor = ReadyStartAuction\Processor::new()
                ->setHybridResponseSender($this->getHybridResponseSender())
                ->setRtbDaemon($this->getRtbDaemon());
        }
        return $this->readyStartAuctionProcessor;
    }

    /**
     * @param ReadyStartAuction\Processor $readyStartAuctionProcessor
     * @return static
     * @noinspection PhpUnused
     */
    public function setReadyStartAuctionProcessor(ReadyStartAuction\Processor $readyStartAuctionProcessor): static
    {
        $this->readyStartAuctionProcessor = $readyStartAuctionProcessor;
        return $this;
    }

    /**
     * Check if running timeout expired and rtbd processor can execute
     * @return bool
     */
    protected function timeoutExpired(): bool
    {
        $isExpired = (time() - $this->runTs >= $this->timeout);
        return $isExpired;
    }
}
