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

namespace Sam\Email\Build\AuctionApproved;

use Auction;
use InvalidArgumentException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Register\AuctionRegistrationManagerAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Email\Build\PlaceholdersAbstractBuilder;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

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
        'bidder_number',
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
        $user = $this->getUserLoader()->load($auctionBidder->UserId);
        if (!$user) {
            log_error(
                "Available user not found"
                . composeSuffix(['u' => $auctionBidder->UserId, 'a' => $auctionBidder->AuctionId, 'email' => $this->getEmailKey()])
            );
            return [];
        }

        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
        $userShipping = $this->getUserLoader()->loadUserShippingOrCreate($user->Id, true);

        $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
        if (!$auction) {
            throw new InvalidArgumentException(
                "Available auction not found"
                . composeSuffix(['a' => $auctionBidder->AuctionId, 'email' => $this->getEmailKey()])
            );
        }

        $editorUserId = $this->getDataConverter()->getEditorUserId();

        $dateFormatted = $this->generateAuctionDate($auction);

        $catalogUrl = $this->buildResponsiveCatalogUrl($auction);
        $newPlaceholders = [
            'first_name' => $userInfo->FirstName,
            'last_name' => $userInfo->LastName,
            'username' => $user->Username,
            'auction_name' => $this->getAuctionRenderer()->renderName($auction),
            'auction_date' => $dateFormatted,
            'auction_url' => $catalogUrl,
            'auction_id' => $auction->Id,
            'sale_no' => $this->getAuctionRenderer()->renderSaleNo($auction),
            'bidder_number' => $this->getBidderInfo($user->Id, $auction->Id),
            'phone' => $userInfo->Phone,
            'shipping_address' => $userShipping->Address,
            'shipping_address2' => $userShipping->Address2,
            'shipping_city' => $userShipping->City,
            'shipping_state' => $userShipping->State,
            'shipping_zip' => $userShipping->Zip,
            'shipping_country' => $userShipping->Country,
            'reset_password_url' => $this->buildResetPasswordUrl($user->Id, $auction->AccountId, $editorUserId),
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
}
