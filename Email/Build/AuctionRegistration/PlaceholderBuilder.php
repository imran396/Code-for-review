<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 1, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build\AuctionRegistration;

use Auction;
use InvalidArgumentException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Register\AuctionRegistrationManagerAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Email\Build\PlaceholdersAbstractBuilder;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use User;

/**
 * Class PlaceholdersBuilder
 * @package Sam\Email\Build\Test
 */
class PlaceholderBuilder extends PlaceholdersAbstractBuilder
{
    use AuctionLoaderAwareTrait;
    use AuctionRegistrationManagerAwareTrait;
    use AuctionRendererAwareTrait;
    use DateHelperAwareTrait;
    use TimezoneLoaderAwareTrait;

    public const AVAILABLE_PLACEHOLDERS = [
        'first_name',
        'last_name',
        'username',
        'reset_password_url',
        'auction_name',
        'auction_date',
        'auction_url',
        'auction_id',
        'sale_no',
        'auction_bidder_options',
        'phone',
        'shipping_address',
        'shipping_address2',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_country',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function build(): array
    {
        $auctionBidder = $this->getDataConverter()->getAuctionBidder();
        $editorUserId = $this->getDataConverter()->getEditorUserId();
        $user = $this->getUserLoader()->load($auctionBidder->UserId);
        if (!$user) {
            log_error(
                "Available user not found for auction bidder"
                . composeSuffix(['u' => $auctionBidder->UserId, 'a' => $auctionBidder->AuctionId, 'ab' => $auctionBidder->Id])
            );
            return [];
        }
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
        $userShipping = $this->getUserLoader()->loadUserShippingOrCreate($user->Id, true);
        $auction = $this->getAuction();
        $dateFormatted = $this->generateAuctionDate($auction);
        $catalogUrl = $this->buildResponsiveCatalogUrl($auction);
        $auctionBidderInfo = $this->generateAuctionBidderInfo($user, $auction);
        $newPlaceholders = [
            'first_name' => $userInfo->FirstName,
            'last_name' => $userInfo->LastName,
            'username' => $user->Username,
            'auction_name' => $this->getAuctionRenderer()->renderName($auction),
            'auction_date' => $dateFormatted,
            'auction_url' => $catalogUrl,
            'auction_id' => $auction->Id,
            'sale_no' => $this->getAuctionRenderer()->renderSaleNo($auction),
            'auction_bidder_options' => $auctionBidderInfo,
            'phone' => $userInfo->Phone,
            'shipping_address' => $userShipping->Address,
            'shipping_address2' => $userShipping->Address2,
            'shipping_city' => $userShipping->City,
            'shipping_state' => $userShipping->State,
            'shipping_zip' => $userShipping->Zip,
            'shipping_country' => $userShipping->Country,
            'reset_password_url' => $this->buildResetPasswordUrl($auctionBidder->UserId, $auction->AccountId, $editorUserId),
        ];
        $placeholders = array_merge($this->getDefaultPlaceholders(), $newPlaceholders);
        return $placeholders;
    }

    /**
     * @param Auction $auction
     * @return string
     */
    private function generateAuctionDate(Auction $auction): string
    {
        $dateFormatted = '';
        $dateHelper = $this->getDateHelper();
        if (
            $auction->isLiveOrHybrid()
            || $auction->isTimedScheduled()
        ) {
            $auctionTzLocation = $this->getTimezoneLoader()->load($auction->TimezoneId)->Location ?? null;
            $dateFormatted = $dateHelper->formatUtcDate($auction->detectScheduledStartDate(), $this->accountId, $auctionTzLocation);
        }
        if ($auction->isTimedScheduled()) {
            $auctionTzLocation = $this->getTimezoneLoader()->load($auction->TimezoneId)->Location ?? null;
            $dateFormatted .= '- <br />' . $dateHelper->formatUtcDate($auction->EndDate, $this->accountId, $auctionTzLocation);
        }

        return $dateFormatted;
    }

    /**
     * @param User $user
     * @param Auction $auction
     * @return string
     */
    private function generateAuctionBidderInfo(User $user, Auction $auction): string
    {
        $auctionRegistrationManger = $this->getAuctionRegistrationManager();
        $auctionRegistrationManger->construct($user->Id, (int)$auction->Id, $user->Id);
        $biddersOptions = $auctionRegistrationManger->getBiddersOptions();
        $auctionBidderInfo = '';
        foreach ($biddersOptions as [$name, $option]) {
            $accepted = $option ? 'Accepted' : 'Not Accepted';
            $auctionBidderInfo .= "{$name} ( {$accepted} ) |";
        }
        return $auctionBidderInfo;
    }

    /**
     * @return Auction
     */
    private function getAuction(): Auction
    {
        $auctionBidder = $this->getDataConverter()->getAuctionBidder();
        $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
        if (!$auction) {
            throw new InvalidArgumentException(
                "Available auction not found "
                . "(a: {$auctionBidder->AuctionId}, email: {$this->getEmailKey()})"
            );
        }
        return $auction;
    }
}
