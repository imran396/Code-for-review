<?php
/**
 * Start auctions
 * SAM-3775: Rtbd improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/24/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Hybrid\Run\ReadyStartAuction;

use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Hybrid\StartAuction;
use Sam\Rtb\Hybrid\Run\Base\HybridResponseSenderAwareTrait;
use Sam\Rtb\Log\Logger;
use Sam\Rtb\Server\Daemon\RtbDaemonAwareTrait;

/**
 * Class Processor
 * @package Sam\Rtb\Hybrid\Run\ReadyStartAuction
 */
class Processor extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use HybridResponseSenderAwareTrait;
    use RtbDaemonAwareTrait;

    protected int $runTs = 0;
    protected int $timeout = 60; // sec
    protected ?DataLoader $dataLoader = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if we have auction, that are ready to start and start them
     */
    public function process(): void
    {
        if (!$this->timeoutExpired()) {
            return;
        }
        $this->runTs = time();
        $rtbDaemon = $this->getRtbDaemon();
        $readyStartAuctions = $this->getDataLoader()->loadReadyStartAuctions($rtbDaemon->getName());
        foreach ($readyStartAuctions as $auction) {
            $logData = [
                'a' => $auction->Id,
                'name' => $this->getAuctionRenderer()->renderName($auction),
            ];
            log_info("HYBRID: Starting auction" . composeSuffix($logData));
            $command = StartAuction::new()
                ->setAuction($auction)
                ->setLogger($this->getLogger($auction->Id))
                ->setRtbDaemon($rtbDaemon)
                ->setUserType(Constants\Rtb::UT_SYSTEM);
            $command->execute();
            if ($command->isRunning()) {
                $responses = $command->getResponses();
                $this->getHybridResponseSender()->send($responses, $auction->Id);
            }
        }
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new();
        }
        return $this->dataLoader;
    }

    /**
     * @param DataLoader $dataLoader
     * @return static
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }

    /**
     * @param int $auctionId
     * @return Logger
     */
    protected function getLogger(int $auctionId): Logger
    {
        $logger = Logger::new()->setAuctionId($auctionId);
        return $logger;
    }

    /**
     * @return bool
     */
    protected function timeoutExpired(): bool
    {
        $isExpired = (time() - $this->runTs >= $this->timeout);
        return $isExpired;
    }
}
