<?php
/**
 * SAM-6459: Rtbd response - lot data producers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use Sam\Core\Service\CustomizableClass;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class GameStatusDataProducer
 * @package Sam\Rtb\Command\Response\Concrete
 */
class GameStatusDataProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use TranslatorAwareTrait;

    private const NAME_LENGTH_LIMIT = 120;

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
     *  Constants\Rtb::RES_STATUS => string
     * ]
     */
    public function produceData(RtbCurrent $rtbCurrent): array
    {
        $data[Constants\Rtb::RES_STATUS] = '';
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
        $auctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        if (
            !$auction
            || !$auctionLot
            || !in_array($auction->AuctionStatusId, Constants\Auction::$openAuctionStatuses, true)
        ) {
            return $data;
        }

        $tr = $this->getTranslator();
        $langStatus = '';
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        if ($auction->isActive()) {
            $langAuctionNotStarted = $tr->translateForRtb('BIDDERCLIENT_MSG_AUCNOTSTARTED', $auction);
            $langStatus = sprintf($langAuctionNotStarted, $lotNo);
        } elseif ($auction->isStarted()) {
            if ($rtbCurrent->isStartedOrPausedLot()) {
                $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
                $lotName = $lotItem ? $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction) : '';
                $lotNameNormal = $this->normalizeLotName($lotName);
                $langLotActivityStatusTpl = $rtbCurrent->isStartedLot()
                    ? $tr->translateForRtb('BIDDERCLIENT_MSG_LOTSTARTED', $auction)
                    : $tr->translateForRtb('BIDDERCLIENT_MSG_LOTPAUSED', $auction);
                $langStatus = sprintf($langLotActivityStatusTpl, $lotNo, ee($lotNameNormal));
            } else {
                $langLotNotStarted = $tr->translateForRtb('BIDDERCLIENT_MSG_LOTNOTSTARTED', $auction);
                $langStatus = sprintf($langLotNotStarted, $lotNo);
            }
        } elseif ($auction->isPaused()) {
            $langAuctionPaused = $tr->translateForRtb('BIDDERCLIENT_MSG_AUCPAUSED', $auction);
            $langStatus = sprintf($langAuctionPaused, $lotNo);
        }

        $data[Constants\Rtb::RES_STATUS] = $langStatus;
        return $data;
    }

    /**
     * Html-encode and trim to length limit
     * @param string $lotName
     * @return string
     */
    public function normalizeLotName(string $lotName): string
    {
        return TextTransformer::new()->cut($lotName, self::NAME_LENGTH_LIMIT);
    }
}
