<?php
/**
 * SAM-5760: Refactor rtbd communication to avoid POST requests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 31, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use RtbCurrent;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Transform\Text\NewLineRemover;
use Sam\Rtb\RtbGeneralHelperAwareTrait;

/**
 * This class contains methods for preparing note data for display
 *
 * Class GeneralNoteDataProducer
 * @package Sam\Rtb\Command\Response\Concrete
 */
class NoteDataProducer extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use RtbGeneralHelperAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_CLERK_NOTE => string,
     *  Constants\Rtb::RES_GENERAL_NOTE => string,
     * ]
     */
    public function produceData(RtbCurrent $rtbCurrent): array
    {
        $auctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        return $this->composeData($auctionLot);
    }

    /**
     * @param AuctionLotItem|null $auctionLot If NULL return empty notes
     * @return array
     */
    public function composeData(?AuctionLotItem $auctionLot = null): array
    {
        $data = [
            Constants\Rtb::RES_CLERK_NOTE => $this->getClerkNote($auctionLot),
            Constants\Rtb::RES_GENERAL_NOTE => $this->getGeneralNote($auctionLot),
        ];
        return $data;
    }

    /**
     * @param AuctionLotItem|null $auctionLot
     * @return string
     */
    private function getGeneralNote(?AuctionLotItem $auctionLot = null): string
    {
        if (!$auctionLot) {
            return '';
        }

        return NewLineRemover::new()->replaceWithSpace($auctionLot->GeneralNote);
    }

    /**
     * @param AuctionLotItem|null $auctionLot
     * @return string
     */
    private function getClerkNote(?AuctionLotItem $auctionLot = null): string
    {
        if (!$auctionLot) {
            return '';
        }

        return NewLineRemover::new()->replaceWithSpace($auctionLot->NoteToClerk);
    }
}
