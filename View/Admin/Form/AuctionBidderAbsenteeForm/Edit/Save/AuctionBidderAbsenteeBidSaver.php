<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Save;

use Sam\Bidding\AbsenteeBid\Place\AbsenteeBidManagerCreateTrait;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Common\AuctionBidderAbsenteeBidEditingInput;
use Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Save\Internal\Load\DataProviderCreateTrait;

/**
 * Class AuctionBidderAbsenteeBidSaver
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Save
 */
class AuctionBidderAbsenteeBidSaver extends CustomizableClass
{
    use AbsenteeBidManagerCreateTrait;
    use DataProviderCreateTrait;
    use DateHelperAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionBidderAbsenteeBidEditingInput $input
     */
    public function update(AuctionBidderAbsenteeBidEditingInput $input): void
    {
        $currentDateUtc = $input->getCurrentDateUtc();
        $editorUserId = $input->editorUserId;
        $bidUserId = $input->userId;
        $maxBid = $this->getNumberFormatter()->parse($input->maxBid);
        $placedOn = $this->getDateHelper()->convertSysToUtc($input->getBidDateUtc());

        $dataProvider = $this->createDataProvider();
        $lotNoParsed = LotNoParsed::new()->construct(Cast::toInt($input->lotNum), $input->lotNumExt, $input->lotNumPrefix);
        $auctionLotRow = $dataProvider->loadSelectedByLotNoParsed(
            ['ali.lot_item_id', 'ali.auction_id'],
            $lotNoParsed,
            $input->auctionId,
            true
        );
        if (!$auctionLotRow) {
            $lotNo = $this->getLotRenderer()->makeLotNoByParsed($lotNoParsed);
            log_error(
                "Available auction lot not found by lot#, when saving absentee bid"
                . composeSuffix(['a' => $input->auctionId, 'lot#' => $lotNo])
            );
            return;
        }

        $absenteeBidManager = $this->createAbsenteeBidManager();
        if ($input->absenteeBidId) {
            $absenteeBid = $dataProvider->loadAbsenteeBidById($input->absenteeBidId, true);
            $absenteeBidManager->setAbsenteeBid($absenteeBid);
        } else {
            $absenteeBidManager
                ->setAuctionId((int)$auctionLotRow['auction_id'])
                ->setCreatedOn($currentDateUtc)
                ->setLotItemId((int)$auctionLotRow['lot_item_id'])
                ->setUserId($bidUserId);
        }

        $absenteeBidManager
            ->enableAddToWatchlist(true)
            ->enableNotifyUsers($input->isNotify)
            ->setBidType($input->bidType)
            ->setEditorUserId($editorUserId)
            ->setMaxBid($maxBid)
            ->setPlacedOn($placedOn);
        $absenteeBidManager->place();
    }
}
