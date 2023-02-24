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

namespace Sam\Email\Build\AbsenteeBid;

use Auction;
use LotItem;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\ReservePrice\LotReservePriceChecker;
use Sam\Bidding\ReservePrice\LotReservePriceCheckerCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotReservePriceEmailMessage
 * @package Sam\Email\Build\AbsenteeBid
 */
class LotReservePriceEmailMessage extends CustomizableClass
{
    use EditorUserAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use LotReservePriceCheckerCreateTrait;
    use DataProviderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $msg
     * @return string
     */
    public function getMessage(string $msg): string
    {
        $auctionLot = $this->getDataProvider()->getAuctionLot();
        $auction = $this->getDataProvider()->getAuction();
        $lotItem = $this->getDataProvider()->getLotItem();
        $highAbsenteeBid = $this->createHighAbsenteeBidDetector()
            ->detectFirstHigh($auctionLot->LotItemId, $auctionLot->AuctionId);
        if (
            $highAbsenteeBid
            && Floating::gt($highAbsenteeBid->MaxBid, 0)
            && !$auction->isAbsenteeBidsDisplaySetAsDoNotDisplay()
        ) {
            if (
                $highAbsenteeBid->UserId === $this->getEditorUserId()
                && $auction->ReserveNotMetNotice
            ) {
                $msg = $this->replaceReserveMetVariable($msg, $auction, $lotItem);
            } else {
                $msg = $this->removeReserveMetVariables($msg);
            }
        } else {
            $msg = $this->removeReserveMetVariables($msg);
        }
        return $msg;
    }

    /**
     * @param string $msg
     * @param Auction $auction
     * @param LotItem $lotItem
     * @return string
     */
    protected function replaceReserveMetVariable(string $msg, Auction $auction, LotItem $lotItem): string
    {
        $reserveCheckResult = $this->createLotReservePriceChecker()
            ->setLotItem($lotItem)
            ->setAuction($auction)
            ->check();

        if ($reserveCheckResult === LotReservePriceChecker::IS_MET) {
            $msg = preg_replace('/{reserve_not_met}.*{\/reserve_not_met}/is', '', $msg);
            $msg = preg_replace('/({reserve_met})|({\/reserve_met})/i', '', $msg);
        } elseif ($reserveCheckResult === LotReservePriceChecker::NOT_MET) {
            $msg = preg_replace('/{reserve_met}.*{\/reserve_met}/is', '', $msg);
            $msg = preg_replace('/({reserve_not_met})|({\/reserve_not_met})/i', '', $msg);
        } else {
            $msg = $this->removeReserveMetVariables($msg);
        }
        return $msg;
    }

    /**
     * @param string $msg
     * @return string
     */
    protected function removeReserveMetVariables(string $msg): string
    {
        $msg = preg_replace('/{reserve_met}.*{\/reserve_met}/is', '', $msg);
        $msg = preg_replace('/{reserve_not_met}.*{\/reserve_not_met}/is', '', $msg);
        return $msg;
    }
}
