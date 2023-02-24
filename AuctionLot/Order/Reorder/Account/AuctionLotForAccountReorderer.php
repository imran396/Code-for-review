<?php
/**
 * SAM-5659: Auction Lot in Account reorderer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder\Account;

use Auction;
use Generator;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;

/**
 * Uses for reordering lots in account
 *
 * Class AuctionLotForAccountReorderer
 * @package Sam\AuctionLot\Order\Reorder
 */
class AuctionLotForAccountReorderer extends CustomizableClass
{
    use AuctionLotReordererAwareTrait;
    use AuctionReadRepositoryCreateTrait;
    use OutputBufferCreateTrait;

    /**
     * @var bool
     */
    private bool $isProgressOutputEnabled = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Refresh lot order numbers for all auctions of account
     * @param int $accountId
     * @param int $editorUserId
     */
    public function reorder(int $accountId, int $editorUserId): void
    {
        $auctions = $this->yieldAuctionsByAccountId($accountId);
        $total = $this->countAuctionsByAccountId($accountId);
        log_debug('Lot order number updating started.' . composeSuffix(['Auction count' => $total]));
        foreach ($auctions as $index => $auction) {
            $this->reorderAuctionLots($auction, $editorUserId);
            $this->outputProgress($accountId, $index + 1, $total);
        }
        log_debug('Lot order number updating finished.' . composeSuffix(['Auction count' => $total]));
    }

    /**
     * @param int $accountId
     * @param int $processed
     * @param int $total
     */
    private function outputProgress(int $accountId, int $processed, int $total): void
    {
        if (
            $this->isProgressOutputEnabled
            && (
                $processed % 100 === 0
                || $processed === $total
            )
        ) {
            printf("Auctions processed: %d (of %d) for account id: %d\n", $processed, $total, $accountId);
        }
    }

    /**
     * @param Auction $auction
     * @param int $editorUserId
     */
    private function reorderAuctionLots(Auction $auction, int $editorUserId): void
    {
        $this->getAuctionLotReorderer()->reorder($auction, $editorUserId);
    }

    /**
     * @param int $accountId
     * @return Generator
     */
    private function yieldAuctionsByAccountId(int $accountId): Generator
    {
        return $this->prepareAuctionRepository($accountId)->yieldEntities();
    }

    /**
     * @param int $accountId
     * @return int
     */
    private function countAuctionsByAccountId(int $accountId): int
    {
        return $this->prepareAuctionRepository($accountId)->count();
    }

    /**
     * @param int $accountId
     * @return AuctionReadRepository
     */
    private function prepareAuctionRepository(int $accountId): AuctionReadRepository
    {
        return $this->createAuctionReadRepository()
            ->filterAccountId($accountId)
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->setChunkSize(100);
    }

    /**
     * @return void
     */
    private function turnOffOutputBuffering(): void
    {
        if ($this->isProgressOutputEnabled) {
            $this->createOutputBuffer()->endFlush();
        }
    }

    /**
     * @return AuctionLotForAccountReorderer
     */
    public function enableProgressOutput(): AuctionLotForAccountReorderer
    {
        $this->turnOffOutputBuffering();
        $this->isProgressOutputEnabled = true;
        return $this;
    }

    /**
     * @return static
     */
    public function disableProgressOutput(): static
    {
        $this->isProgressOutputEnabled = false;
        return $this;
    }
}
