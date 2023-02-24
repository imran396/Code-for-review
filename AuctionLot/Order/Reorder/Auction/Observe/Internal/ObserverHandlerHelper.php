<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder\Auction\Observe\Internal;

use Auction;
use Sam\Core\Service\CustomizableClass;

/**
 * Helper methods for determining the need to reorder auction lots
 *
 * Class ObserverHandlerHelper
 * @package Sam\AuctionLot\Order\Reorder\Auction\Observe
 * @internal
 */
class ObserverHandlerHelper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Auction $auction
     * @param int $orderType
     * @return bool
     */
    public function hasAuctionLotOrderType(Auction $auction, int $orderType): bool
    {
        $auctionLotOrderTypes = [
            $auction->LotOrderPrimaryType,
            $auction->LotOrderSecondaryType,
            $auction->LotOrderTertiaryType,
            $auction->LotOrderQuaternaryType,
        ];

        return in_array($orderType, $auctionLotOrderTypes, true);
    }

    /**
     * @param Auction $auction
     * @param int $custFieldId
     * @return bool
     */
    public function hasAuctionLotOrderCustFieldId(Auction $auction, int $custFieldId): bool
    {
        $auctionLotOrderCustomFieldsIds = [
            $auction->LotOrderPrimaryCustFieldId,
            $auction->LotOrderSecondaryCustFieldId,
            $auction->LotOrderTertiaryCustFieldId,
            $auction->LotOrderQuaternaryCustFieldId,
        ];

        return in_array($custFieldId, $auctionLotOrderCustomFieldsIds, true);
    }
}
