<?php
/**
 * Auction Bidder related checks
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 24, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Auction\Bidder;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class Checker
 * @package Sam\Core\Auction\Bidder
 */
class AuctionBidderPureChecker extends CustomizableClass
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
     * @param bool $isChkNaa
     * @param int|null $userFlag
     * @param string $bidderNum
     * @return bool
     */
    public function isApproved(
        bool $isChkNaa,
        ?int $userFlag,
        string $bidderNum
    ): bool {
        if (
            $isChkNaa
            && $userFlag === Constants\User::FLAG_NOAUCTIONAPPROVAL
        ) {
            $isApproved = false;
        } else {
            $isApproved = is_numeric($bidderNum)
                ? (int)$bidderNum > 0
                : $bidderNum !== '';
        }
        return $isApproved;
    }
}
