<?php
/**
 * SAM-4446: Apply Admin Landing Page Detector
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Landing;

use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

/**
 * Class AdminLandingPageDetector
 * @package Sam\Application\Landing
 */
class AdminLandingPageDetector extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get landing page url type (Auction_Link) for manage-auctions controller based on actions permission restrictions
     * IF ADMIN HAS ACCESS TO , LOTS ACTION WILL BE USED BY DEFAULT
     * @param int $auctionId
     * @param string $auctionType
     * @param int|null $editorUserId
     * @return AbstractUrlConfig|null
     */
    public function detect(int $auctionId, string $auctionType, ?int $editorUserId): ?AbstractUrlConfig
    {
        $adminPrivilegeChecker = $this->getAdminPrivilegeChecker()->initByUserId($editorUserId);
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();

        if ($adminPrivilegeChecker->hasSubPrivilegeForLots()) {
            return AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LOT_LIST, $auctionId);
        }

        if ($adminPrivilegeChecker->hasSubPrivilegeForInformation()) {
            return AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_EDIT, $auctionId);
        }

        if ($adminPrivilegeChecker->hasSubPrivilegeForBidders()) {
            return AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_BIDDER_LIST, $auctionId);
        }

        if ($auctionStatusPureChecker->isLiveOrHybrid($auctionType)) {
            if ($adminPrivilegeChecker->hasSubPrivilegeForRunLiveAuction()) {
                return AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_RUN, $auctionId);
            }

            if ($adminPrivilegeChecker->hasSubPrivilegeForAuctioneerScreen()) {
                return AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_AUCTIONEER, $auctionId);
            }

            if ($adminPrivilegeChecker->hasSubPrivilegeForProjector()) {
                return AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_PROJECTOR, $auctionId);
            }
        }

        if ($adminPrivilegeChecker->hasSubPrivilegeForBidIncrements()) {
            return AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_BID_INCREMENT, $auctionId);
        }

        if ($adminPrivilegeChecker->hasSubPrivilegeForBuyersPremium()) {
            return AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_BUYERS_PREMIUM, $auctionId);
        }

        if ($adminPrivilegeChecker->hasSubPrivilegeForPermissions()) {
            return AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_PERMISSION, $auctionId);
        }

        return null;
    }
}
