<?php
/**
 * SAM-9389: Move all <controllerName>Validator classes to ./Validate namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Auction\Validate\Registration\Internal\Load;

use Auction;
use Sam\Account\DomainAuctionVisibility\VisibilityChecker;
use Sam\Account\Validate\AccountExistenceChecker;
use Sam\Auction\Load\AuctionLoader;
use Sam\Auction\Validate\AuctionStatusChecker;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderChecker;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManager;
use Sam\User\Privilege\Validate\RoleChecker;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Responsive\Auction\Validate\Registration\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use EditorUserAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
    }

    public function existAuctionAccount(?int $accountId, bool $isReadOnlyDb = false): bool
    {
        return AccountExistenceChecker::new()->existById($accountId, $isReadOnlyDb);
    }

    public function isAllowedForAuction(Auction $auction): bool
    {
        return VisibilityChecker::new()->isAllowedForAuction($auction);
    }

    /**
     * Check if page requires from user to be not registered in auction yet.
     *
     * @param int|null $editorUserId
     * @param int $auctionId
     * @return bool
     */
    public function isAuctionRegistered(?int $editorUserId, int $auctionId): bool
    {
        return AuctionBidderChecker::new()->isAuctionRegistered($editorUserId, $auctionId);
    }

    /**
     * Check if user is a bidder
     *
     * @param int|null $editorUserId
     * @return bool
     */
    public function hasBidderRole(?int $editorUserId): bool
    {
        return RoleChecker::new()->isBidder($editorUserId);
    }

    public function isAppVerifyEmailEnabledForMainAccount(): bool
    {
        return (bool)SettingsManager::new()->getForMain(Constants\Setting::VERIFY_EMAIL);
    }

    /**
     * Check if start register date come
     *
     * @param Auction $auction
     * @return bool
     */
    public function detectIfRegistrationActiveByDatesRange(Auction $auction): bool
    {
        $is = AuctionStatusChecker::new()->detectIfRegistrationActiveByDatesRange(
            $auction->StartRegisterDate,
            $auction->EndRegisterDate
        );
        return $is;
    }
}
