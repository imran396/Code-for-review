<?php
/**
 * SAM-5651: Refactor Lot No auto filling service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Fill\Save;

use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\LotNo\Calculate\LotNoAdviserCreateTrait;

/**
 * Class LotNoByAutoIncrementProducer
 * @package Sam\AuctionLot\LotNo\Fill\Save
 */
class LotNoByAutoIncrementProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LotNoAdviserCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Provides auction lot num that was not used yet.
     * Returns empty array if auto-populating is disabled.
     *
     * @param AuctionLotItem $auctionLot
     * @param bool $checkAutoPopulateEmptyLotNumOption
     * @return array ('LotNum' => x, 'LotNumExt' => x, 'LotNumPrefix' => x);
     */
    public function produce(AuctionLotItem $auctionLot, bool $checkAutoPopulateEmptyLotNumOption = false): array
    {
        if (!$this->isAuctionAvailableAndApplicable($auctionLot->AuctionId, $checkAutoPopulateEmptyLotNumOption)) {
            return [];
        }

        return $this->detectLotNoParts($auctionLot);
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return array('LotNum' => x, 'LotNumExt' => x, 'LotNumPrefix' => x)
     */
    private function detectLotNoParts(AuctionLotItem $auctionLot): array
    {
        $lotNum = $this->createLotNoAdviser()->suggest($auctionLot->AuctionId);
        return [
            'LotNum' => $lotNum,
            'LotNumExt' => $auctionLot->LotNumExt,
            'LotNumPrefix' => $auctionLot->LotNumPrefix,
        ];
    }

    /**
     * @param int $auctionId
     * @param bool $checkAutoPopulateEmptyLotNumOption
     * @return bool
     */
    private function isAuctionAvailableAndApplicable(int $auctionId, bool $checkAutoPopulateEmptyLotNumOption = false): bool
    {
        $auction = $this->getAuctionLoader()->load($auctionId, true);
        if (!$auction) {
            log_error('Available auction not found in lot# auto filler' . composeSuffix(['a' => $auctionId]));
            return false;
        }

        if ($checkAutoPopulateEmptyLotNumOption && !$auction->AutoPopulateEmptyLotNum) {
            return false;
        }

        return true;
    }
}
