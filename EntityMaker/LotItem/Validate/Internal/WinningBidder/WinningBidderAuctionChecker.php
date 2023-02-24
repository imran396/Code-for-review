<?php
/**
 * SAM-10106: Supply lot winning info correspondence for winning auction and winning bidder fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\WinningBidder;

use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\LotItem\Common\WinningAuction\WinningAuctionIdDetectorCreateTrait;
use Sam\EntityMaker\LotItem\Common\WinningAuction\WinningAuctionInput;
use Sam\EntityMaker\LotItem\Common\WinningBidder\WinningBidderIdDetectorCreateTrait;
use Sam\EntityMaker\LotItem\Common\WinningBidder\WinningBidderInput;

/**
 * Class WinningBidderAuctionChecker
 * @package Sam\EntityMaker\LotItem
 */
class WinningBidderAuctionChecker extends CustomizableClass
{
    use AuctionBidderCheckerAwareTrait;
    use WinningAuctionIdDetectorCreateTrait;
    use WinningBidderIdDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isNotRegisteredInAuctionSold(
        WinningBidderInput $winningBidderInput,
        ?int $currentWinningBidderId,
        WinningAuctionInput $auctionSoldInput,
        ?int $currentAuctionSoldId,
        ?int $syncNamespaceId,
        int $accountId,
        bool $isReadOnlyDb = false
    ): bool {
        $winningBidderId = $winningBidderInput->isSet()
            ? $this->createWinningBidderIdDetector()->detectFromInput($winningBidderInput, $syncNamespaceId, $accountId, $isReadOnlyDb)
            : $currentWinningBidderId;
        if (!$winningBidderId) {
            return false;
        }

        $winningAuctionId = $auctionSoldInput->isSet()
            ? $this->createAuctionSoldIdDetector()->detectFromInput($auctionSoldInput, $syncNamespaceId, $accountId, $isReadOnlyDb)
            : $currentAuctionSoldId;
        if (!$winningAuctionId) {
            return false;
        }

        return !$this->getAuctionBidderChecker()->isAuctionRegistered($winningBidderId, $winningAuctionId, $isReadOnlyDb);
    }
}
