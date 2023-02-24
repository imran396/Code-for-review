<?php
/**
 * DB data loader for on-increment bid validation and calculation.
 *
 * SAM-6909: Refactor on-increment bid validator for v3.6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\OnIncrementBid\Internal\Load;

use Exception;
use Sam\Bidding\AskingBid\AskingBidDetector;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;

/**
 * Class DataProvider
 * @package Sam\Bidding\OnIncrementBid\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return Dto
     */
    public function loadDto(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): Dto
    {
        $select = [
            'ali.account_id',
            'alic.current_bid',
            'alic.starting_bid_normalized',
            'alic.asking_bid',
            'a.reverse',
        ];
        $row = AuctionLotItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuction()
            ->joinAuctionLotItemCache()
            ->joinLotItem()
            ->orderByCreatedOn(false) // JIC
            ->select($select)
            ->loadRow();
        return Dto::new()->fromRow($row);
    }

    /**
     * @param float $amount
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function detectQuantizedLowBid(float $amount, int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): float
    {
        try {
            return AskingBidDetector::new()->detectQuantizedBid($amount, false, $lotItemId, $auctionId, null, $isReadOnlyDb);
        } catch (Exception $e) {
            $logData = ['li' => $lotItemId, 'a' => $auctionId];
            log_error($e->getMessage() . composeSuffix($logData));
        }
        return 0.;
    }

    /**
     * @param float $amount
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function detectQuantizedHighBid(float $amount, int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): float
    {
        try {
            return AskingBidDetector::new()->detectQuantizedBid($amount, true, $lotItemId, $auctionId, null, $isReadOnlyDb);
        } catch (Exception $e) {
            $logData = ['li' => $lotItemId, 'a' => $auctionId];
            log_error($e->getMessage() . composeSuffix($logData));
        }
        return 0.;
    }
}
