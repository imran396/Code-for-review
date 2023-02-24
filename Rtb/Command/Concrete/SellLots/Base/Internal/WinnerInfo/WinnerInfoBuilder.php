<?php
/**
 * Parent handler for SellLots command coming from any console
 *
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Base\Internal\WinnerInfo;

use Auction;
use AuctionBidder;
use BidTransaction;
use Sam\Bidder\BidderInfo\BidderInfoRendererAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class WinnerInfoBuilder
 * @package Sam\Rtb
 */
class WinnerInfoBuilder extends CustomizableClass
{
    use BidderInfoRendererAwareTrait;
    use BidderNumPaddingAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * $winnerUserId may differ to $currentBidTransaction->UserId, when bid was placed by agent
     * and then lot was sold to his buyer, who didn't place this winning bid, but becomes a winner.
     * @param AuctionBidder|null $winnerAuctionBidder
     * @param BidTransaction $currentBidTransaction
     * @param int|null $winnerUserId
     * @param Auction $auction
     * @return WinnerInfoDto
     */
    public function buildWinnerInfoDto(
        ?AuctionBidder $winnerAuctionBidder,
        BidTransaction $currentBidTransaction,
        ?int $winnerUserId,
        Auction $auction
    ): WinnerInfoDto {
        $isFloor = !$winnerAuctionBidder
            || $currentBidTransaction->FloorBidder;

        /**
         * Make infoPublic, infoAdmin
         */
        $infoAdmin = $infoPublic = $isFloor
            ? $this->getTranslator()->translateForRtb('BIDDERCLIENT_FLOORBIDDER', $auction)
            : $this->getTranslator()->translateForRtb('BIDDERCLIENT_INTERNETBIDDER', $auction);
        $bidderInfo = null;
        if (
            $winnerUserId
            && $winnerAuctionBidder
        ) {
            $infoAdmin .= ' ' . $this->getBidderInfoRenderer()->renderForAdminRtb($winnerUserId, $auction->Id);
            $isHideBidderNumber = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::HIDE_BIDDER_NUMBER, $auction->AccountId);
            if (!$isHideBidderNumber) {
                $infoPublic .= ' ' . $this->getBidderInfoRenderer()->renderForPublicRtb($winnerUserId, $auction->Id);
            }
        }

        /**
         * Make bidderName
         */
        $bidderName = $this->makeBidderName(
            $winnerAuctionBidder,
            $auction,
            ['floor' => $isFloor, 'bidderInfo' => $bidderInfo]
        );

        /**
         * Make username, $ownerBidderNums
         */
        $username = '';
        $ownerBidderNums = [];
        if ($winnerAuctionBidder) {
            $username = $this->getUserLoader()
                ->load($winnerUserId)
                ->Username;
            $ownerBidderNums[$winnerUserId] = $this->getBidderNumberPadding()->clear($winnerAuctionBidder->BidderNum);
        }

        $winnerInfo = WinnerInfoDto::new()->construct(
            $infoPublic,
            $infoAdmin,
            $bidderName,
            $username,
            $ownerBidderNums
        );
        return $winnerInfo;
    }


    /**
     * @param AuctionBidder|null $auctionBidder null for floor bidder
     * @param Auction $auction
     * @param array $optionals = [
     *  'floor' => bool, (false)
     *  'bidderInfo' => string
     * ]
     * @return string
     */
    public function makeBidderName(
        ?AuctionBidder $auctionBidder,
        Auction $auction,
        array $optionals = []
    ): string {
        $isFloor = $optionals['floor'] ?? false;
        $parts[] = $isFloor ? 'floor bidder' : 'bidder';
        if ($auctionBidder) {
            $userId = $auctionBidder->UserId;
            $bidderInfo = $optionals['bidderInfo']
                ?? $this->getBidderInfoRenderer()->renderForAdminRtb($userId, $auction->Id);
            $displayBidderInfo = (string)$this->getSettingsManager()
                ->get(Constants\Setting::DISPLAY_BIDDER_INFO, $auction->AccountId);
            if ($displayBidderInfo !== Constants\SettingAuction::DBI_NUMBER) {
                $parts[] = $bidderInfo;
                $parts[] = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            } else {
                $parts[] = $bidderInfo;
            }
            if ($userId > 0) {
                $parts[] = "({$userId})";
            }
        }
        $output = implode(' ', $parts);
        return $output;
    }
}
