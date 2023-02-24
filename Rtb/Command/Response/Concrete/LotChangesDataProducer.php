<?php
/**
 * SAM-6459: Rtbd response - lot data producers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use Auction;
use Sam\Core\Service\CustomizableClass;
use LotItem;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Transform\Text\NewLineRemover;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class LotChangesDataProducer
 * @package Sam\Rtb\Command\Response\Concrete
 */
class LotChangesDataProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LotItemLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_LOT_CHANGES => string,
     * ]
     */
    public function produceData(RtbCurrent $rtbCurrent): array
    {
        $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
        $data = $this->composeData($lotItem, $auction);
        return $data;
    }

    /**
     * @param LotItem|null $lotItem
     * @param Auction|null $auction
     * @return array
     */
    public function composeData(?LotItem $lotItem, ?Auction $auction): array
    {
        $lotChanges = '';
        if (
            $auction
            && $auction->RequireLotChangeConfirmation
            && $lotItem
            && $lotItem->Changes !== ''
        ) {
            $lotChanges = NewLineRemover::new()->replaceWithSpace($lotItem->Changes);
        }
        $data[Constants\Rtb::RES_LOT_CHANGES] = ee($lotChanges); // TODO: html-encode at js side
        return $data;
    }
}
