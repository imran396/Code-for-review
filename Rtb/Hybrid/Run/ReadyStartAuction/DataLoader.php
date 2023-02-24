<?php
/**
 * Data loader for ready to start auctions finding
 * SAM-3775: Rtbd improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 24, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Hybrid\Run\ReadyStartAuction;

use Auction;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Rtb\Hybrid\Run\ReadyStartAuction
 */
class DataLoader extends CustomizableClass
{
    use AuctionReadRepositoryCreateTrait;
    use CurrentDateTrait;

    protected int $readyStartAuctionsRunTime = 0;
    protected int $readyStartAuctionsTimeout = 15;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load auctions ready for auto-start. They are cached in memory with some ttl
     * @param string $rtbdName we expect empty string '' when rtb pooling is disabled
     * @return Auction[]
     */
    public function loadReadyStartAuctions(string $rtbdName = ''): array
    {
        $auctions = [];
        if ($this->isReadyStartAuctionsTimeoutExpired()) {
            $auctions = $this->loadReadyStartAuctionsFromDb($rtbdName);
            $this->readyStartAuctionsRunTime = time();
        }
        return $auctions;
    }

    /**
     * @return int
     */
    public function getReadyStartAuctionsTimeout(): int
    {
        return $this->readyStartAuctionsTimeout;
    }

    /**
     * @param int $readyStartAuctionsTimeout
     * @return static
     * @noinspection PhpUnused
     */
    public function setReadyStartAuctionsTimeout(int $readyStartAuctionsTimeout): static
    {
        $this->readyStartAuctionsTimeout = $readyStartAuctionsTimeout;
        return $this;
    }

    /**
     * @return bool
     */
    protected function isReadyStartAuctionsTimeoutExpired(): bool
    {
        $isExpired = (time() - $this->readyStartAuctionsRunTime >= $this->getReadyStartAuctionsTimeout());
        return $isExpired;
    }

    /**
     * @param string $rtbdName we expect empty string '' when rtb pooling is disabled
     * @return Auction[]   auctions are ready to start
     */
    protected function loadReadyStartAuctionsFromDb(string $rtbdName = ''): array
    {
        $now = $this->getCurrentDateUtc();
        $auctionRepository = $this->createAuctionReadRepository()
            ->filterAuctionType(Constants\Auction::HYBRID)
            ->filterAuctionStatusId(Constants\Auction::AS_ACTIVE)
            ->filterStartClosingDateLessOrEqual($now->format(Constants\Date::ISO))
            ->innerJoinRtbCurrent()
            ->joinAccountFilterActive(true)
            ->joinAuctionCacheFilterTotalLotsGreater(0);
        if ($rtbdName) {
            // Filter auctions by their reference to running rtbd instance
            $auctionRepository->joinAuctionRtbdFilterRtbdName($rtbdName);
        }
        $auctions = $auctionRepository->loadEntities();
        return $auctions;
    }
}
