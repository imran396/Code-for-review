<?php
/**
 * Auction lot note's saver
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jan 20, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Lot\Note\Save;

use Auction;
use AuctionLotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Class LotInfoService
 * @package Sam\Rtb
 */
class LotNoteSaver extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use BidderNumPaddingAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update General note of Auction lot
     *
     * @param string $note
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     * @param string $winnerBidderNo
     * @return AuctionLotItem|null
     */
    public function save(
        string $note,
        int $lotItemId,
        int $auctionId,
        int $editorUserId,
        string $winnerBidderNo = ''
    ): ?AuctionLotItem {
        $note = trim($note);
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when saving lot note for rtb"
                . composeSuffix(['a' => $auctionId, 'li' => $lotItemId])
            );
            return null;
        }
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auction->Id);
        if ($auctionLot) {
            if ($auctionLot->isAmongAvailableLotStatuses()) {
                $note = $this->updateNoteForOfflinePaddle($note, $auction, $winnerBidderNo);
                $auctionLot->GeneralNote = $note;
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                return $auctionLot;
            }
        }
        return null;
    }

    /**
     * @param string $note
     * @param Auction $auction
     * @param string $winnerBidderNo
     * @return string
     */
    protected function updateNoteForOfflinePaddle(string $note, Auction $auction, string $winnerBidderNo): string
    {
        if ($this->hasOfflinePaddleInfo($auction, $winnerBidderNo)) {
            $note .= "\n" . $this->getOfflinePaddleInfo($auction, $winnerBidderNo);
        }
        return $note;
    }

    /**
     * @param Auction $auction
     * @param string $winnerBidderNo
     * @return bool
     */
    protected function hasOfflinePaddleInfo(Auction $auction, string $winnerBidderNo): bool
    {
        if (!$winnerBidderNo) {
            return false;
        }

        $isFloorBiddersFromDropdown = $this->getSettingsManager()
            ->get(Constants\Setting::FLOOR_BIDDERS_FROM_DROPDOWN, $auction->AccountId);
        if ($isFloorBiddersFromDropdown) {
            $winnerBidderNo = preg_replace('/[^a-zA-Z0-9]/', '', $winnerBidderNo);
        }
        $bidWinPad = $this->getBidderNumberPadding()->add($winnerBidderNo);
        $bidder = $this->getAuctionBidderLoader()->loadByBidderNum($bidWinPad, $auction->Id, true);
        if (!$bidder) {
            return true;
        }
        return false;
    }

    /**
     * @param Auction $auction
     * @param string $winnerBidderNo
     * @return string
     */
    protected function getOfflinePaddleInfo(Auction $auction, string $winnerBidderNo): string
    {
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
        $info = "This lot was sold to offline paddle ({$winnerBidderNo}) in sale ({$saleNo}).";
        return $info;
    }
}
