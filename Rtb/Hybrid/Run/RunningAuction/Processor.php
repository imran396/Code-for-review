<?php
/**
 * Single iteration of running auctions processing
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
 */

namespace Sam\Rtb\Hybrid\Run\RunningAuction;

use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Hybrid\Run\Base\HybridResponseSenderAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonAwareTrait;

/**
 * Class Processor
 * @package Sam\Rtb\Hybrid\Run\RunningAuction
 */
class Processor extends CustomizableClass
{
    use HybridResponseSenderAwareTrait;
    use RtbDaemonAwareTrait;

    protected ?DataLoader $dataLoader = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Process running auctions
     */
    public function process(): void
    {
        $rtbDaemon = $this->getRtbDaemon();
        $auctions = $this->getDataLoader()->loadRunningAuctions($rtbDaemon->getName());
        $auctionCacheData = $this->getDataLoader()->loadAuctionCacheData($auctions);
        foreach ($auctions as $auction) {
            $totalActiveLots = (int)($auctionCacheData[$auction->Id]['total_active_lots'] ?? 0);
            SingleAuctionProcessor::new()
                ->setHybridResponseSender($this->getHybridResponseSender())
                ->setRtbDaemon($rtbDaemon)
                ->process($auction, $totalActiveLots);
        }
    }

    /**
     * @return DataLoader
     */
    protected function getDataLoader(): DataLoader
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
}
