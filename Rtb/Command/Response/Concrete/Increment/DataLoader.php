<?php
/**
 * Produce rtb response data
 *
 * SAM-5201: Apply constants for response data and keys
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete\Increment;

use BidIncrement;
use JetBrains\PhpStorm\ArrayShape;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Validate\BidIncrementExistenceCheckerAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BidIncrement\BidIncrementReadRepositoryCreateTrait;

/**
 * Class ResponseDataProducer
 * @package Sam\Rtb\Command\Response
 */
class DataLoader extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use BidIncrementExistenceCheckerAwareTrait;
    use BidIncrementLoaderAwareTrait;
    use BidIncrementReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns increment values necessary at console (current running increment, restore increment, previous/next increments)
     *
     * @param int|null $auctionId
     * @param float|null $currentBid
     * @param float|null $increment
     * @param int|null $lotItemId
     * @return array
     */
    #[ArrayShape([0 => 'float', 1 => 'float', 2 => 'float[]'])]
    public function loadIncrementsForSimpleClerking(
        ?int $auctionId,
        ?float $currentBid,
        ?float $increment,
        ?int $lotItemId
    ): array {
        $currentIncrement = $restoreIncrement = 0.;
        $nextIncrements = [];
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error(
                "Available auction not found for rtbd updating asking bid"
                . composeSuffix(['a' => $auctionId])
            );
            return [$currentIncrement, $restoreIncrement, $nextIncrements];
        }

        $lotIncrement = $this->getBidIncrementLoader()->loadAvailable(
            $currentBid,
            $auction->AccountId,
            $auction->AuctionType,
            $auctionId,
            $lotItemId
        );

        if (Floating::neq($increment, 0)) { // Has current increment stored
            $currentIncrement = $increment;
            $restoreIncrement = $lotIncrement->Increment ?? 0.;
        } else {
            $currentIncrement = $lotIncrement->Increment ?? 0.;
        }

        $bidIncrements = $this->loadAvailableOrderedByAmount(
            $currentBid,
            $currentIncrement,
            false,
            $auction->AccountId,
            $auction->AuctionType,
            $auctionId,
            $lotItemId,
            2
        );
        $nextIncrements[1] = isset($bidIncrements[1]) ? $bidIncrements[1]->Increment : null;
        $nextIncrements[2] = isset($bidIncrements[0]) ? $bidIncrements[0]->Increment : null;

        $bidIncrements = $this->loadAvailableOrderedByAmount(
            $currentBid,
            $currentIncrement,
            true,
            $auction->AccountId,
            $auction->AuctionType,
            $auctionId,
            $lotItemId,
            2
        );

        $nextIncrements[3] = isset($bidIncrements[0]) ? $bidIncrements[0]->Increment : null;
        $nextIncrements[4] = isset($bidIncrements[1]) ? $bidIncrements[1]->Increment : null;
        $increments = [$currentIncrement, $restoreIncrement, $nextIncrements];
        return $increments;
    }

    /**
     * Load available ordered by increment
     * @param float|null $currentBid current bid amount
     * @param float|null $increment current increment should be skipped
     * @param bool $isAsc
     * @param int|null $accountId
     * @param string|null $auctionType
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param int|null $limit
     * @param bool $isReadOnlyDb
     * @return BidIncrement[]
     */
    public function loadAvailableOrderedByAmount(
        ?float $currentBid,
        ?float $increment,
        bool $isAsc = true,
        ?int $accountId = null,
        ?string $auctionType = null,
        ?int $auctionId = null,
        ?int $lotItemId = null,
        ?int $limit = null,
        bool $isReadOnlyDb = false
    ): array {
        $bidIncrementRepository = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->skipIncrement($increment)
            ->limit($limit);

        if ($isAsc) {
            $bidIncrementRepository->filterAmountGreater($currentBid);
        } else {
            $bidIncrementRepository->filterAmountLessOrEqual($currentBid);
        }

        if (
            $lotItemId > 0
            && $this->getBidIncrementExistenceChecker()->existForLot($lotItemId)
        ) {
            $bidIncrementRepository->filterLotItemId($lotItemId);
        } elseif (
            $auctionId > 0
            && $this->getBidIncrementExistenceChecker()->existForAuction($auctionId)
        ) {
            $bidIncrementRepository->filterAuctionId($auctionId);
        } else {
            $bidIncrementRepository
                ->filterAccountId($accountId)
                ->filterAuctionType($auctionType);
        }

        $bidIncrements = $bidIncrementRepository
            ->orderByAmount($isAsc)
            ->loadEntities();
        return $bidIncrements;
    }
}
