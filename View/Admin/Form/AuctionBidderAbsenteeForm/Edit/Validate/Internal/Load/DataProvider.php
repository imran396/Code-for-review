<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Validate\Internal\Load;

use Sam\AuctionLot\Validate\AuctionLotExistenceCheckerAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionBidderCheckerAwareTrait;
    use AuctionLotExistenceCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $userId null leads to false result
     * @param int|null $auctionId null leads to false result
     * @return bool
     */
    public function isAuctionRegistered(?int $userId, ?int $auctionId): bool
    {
        return $this->getAuctionBidderChecker()->isAuctionRegistered($userId, $auctionId);
    }

    /**
     * @param int|null $userId null leads to false result
     * @param int|null $auctionId null leads to false result
     * @param array $optionals
     * @return bool
     */
    public function isAuctionApproved(?int $userId, ?int $auctionId, array $optionals): bool
    {
        return $this->getAuctionBidderChecker()->isAuctionApproved($userId, $auctionId, $optionals);
    }

    /**
     * @param int $auctionId
     * @param int $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByLotNo(
        int $auctionId,
        int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        bool $isReadOnlyDb = false
    ): bool {
        return $this->getAuctionLotExistenceChecker()->existByLotNo($auctionId, $lotNum, $lotNumExt, $lotNumPrefix, [], $isReadOnlyDb);
    }
}
