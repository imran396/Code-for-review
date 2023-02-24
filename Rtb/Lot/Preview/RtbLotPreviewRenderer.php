<?php
/**
 * SAM-5750: Rtb lot preview data builder
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Lot\Preview;

use AuctionLotItem;
use Sam\Core\Constants\Responsive\RtbConsoleConstants;
use Sam\Core\Service\CustomizableClass;

/**
 * This class contains methods for rendering lot preview
 *
 * Class RtbLotPreviewRenderer
 * @package Sam\Rtb\Lot\Preview
 */
class RtbLotPreviewRenderer extends CustomizableClass
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
     * @param AuctionLotItem|null $auctionLot
     * @return string
     */
    public function renderNextAuctionLotLink(AuctionLotItem $auctionLot = null): string
    {
        return $auctionLot
            ? ' <a href="javascript:void(0);" class="' . RtbConsoleConstants::CLASS_LNK_RTB_LOT_PREVIEW . '" data-auction="' . $auctionLot->AuctionId . '" data-lot="' . $auctionLot->LotItemId . '">>>></a> '
            : $this->getEmptyLinkReplacement();
    }

    /**
     * @param AuctionLotItem|null $auctionLot
     * @return string
     */
    public function renderPrevAuctionLotLink(AuctionLotItem $auctionLot = null): string
    {
        return $auctionLot
            ? ' <a href="javascript:void(0);" class="' . RtbConsoleConstants::CLASS_LNK_RTB_LOT_PREVIEW . '" data-auction="' . $auctionLot->AuctionId . '" data-lot="' . $auctionLot->LotItemId . '"><<<</a> '
            : $this->getEmptyLinkReplacement();
    }

    /**
     * @param string $lotNo
     * @param string $lotName
     * @param string $prevLink
     * @param string $nextLink
     * @param string $imageUrl
     * @return string
     */
    public function renderLotPreviewHtml(
        string $lotNo,
        string $lotName,
        string $prevLink,
        string $nextLink,
        string $imageUrl
    ): string {
        return '<p>' . $prevLink . $lotNo . $nextLink . '</p>' .
            '<p>' . '<img id="al-img" src="' . $imageUrl . '" alt="" />' . '</p>' .
            '<p>' . $lotName . '</p>';
    }

    /**
     * @return string
     */
    public function renderLotNotFoundMessage(): string
    {
        return '<p>Lot not found</p>';
    }

    /**
     * @return string
     */
    private function getEmptyLinkReplacement(): string
    {
        return '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
}
