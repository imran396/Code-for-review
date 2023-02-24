<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\PostAuction\Internal\Process\Internal\DetectModification\ModificationWayDetector;
use Sam\Import\Csv\PostAuction\Internal\Process\Internal\Load\DataProviderCreateTrait;
use Sam\Import\Csv\PostAuction\Internal\Process\Internal\Save\DataSaverCreateTrait;
use Sam\Import\Csv\PostAuction\Internal\Process\RowProcessResult as Result;
use Sam\Import\Csv\PostAuction\Internal\Dto\RowInput as Input;

/**
 * Class RowProcessor
 */
class RowProcessor extends CustomizableClass
{
    use DataProviderCreateTrait;
    use DataSaverCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Modify auction lot status and additional auction-related data
     *
     * @param Input $input
     * @param int $auctionId
     * @param float $bidderPremium
     * @param int $editorUserId
     * @param bool $isUnassignUnsold
     * @return Result
     */
    public function process(
        Input $input,
        int $auctionId,
        float $bidderPremium,
        int $editorUserId,
        bool $isUnassignUnsold
    ): Result {
        $logData = $input->lotNoParsed->logData() + ['a' => $auctionId];
        $dataProvider = $this->createDataProvider();
        $dataSaver = $this->createDataSaver();

        $auctionLot = $dataProvider->loadAuctionLotByLotNoParsed($input->lotNoParsed, $auctionId, true);
        if (!$auctionLot) {
            log_error('Available auction lot not found for post csv upload' . composeSuffix($logData));
            return Result::new()->construct()
                ->addError(Result::ERR_AUCTION_LOT_NOT_FOUND);
        }

        $lotItem = $dataProvider->loadLotItem($auctionLot->LotItemId, true);
        if (!$lotItem) {
            $logData += [
                'li' => $auctionLot->LotItemId,
                'ali' => $auctionLot->Id
            ];
            log_error('Available lot item not found for post csv upload' . composeSuffix($logData));
            return Result::new()->construct()
                ->addError(Result::ERR_LOT_ITEM_NOT_FOUND);
        }

        $modificationWayDetector = ModificationWayDetector::new();
        $result = $modificationWayDetector->detect(
            $input->userInputDto->email,
            Cast::toFloat($input->hammerPrice),
            $lotItem->WinningBidderId,
            $lotItem->HammerPrice,
            $isUnassignUnsold
        );
        log_debug($result->statusMessage() . composeSuffix($logData));

        if ($result->hasError()) {
            log_error('Unexpected input data error - ' . $result->errorMessage());
            return Result::new()->construct()
                ->addError(Result::ERR_WRONG_INPUT_ABSENT_HP_BUT_PRESENT_WB);
        }

        if ($result->shouldNotTouch()) {
            return Result::new()->construct($lotItem, $auctionLot);
        }

        if ($input->generalNote) {
            $auctionLot->GeneralNote = $input->generalNote;
        }

        if ($result->shouldUnsellAndUnassign()) {
            $dataSaver->deleteAuctionLot($auctionLot, $editorUserId);
            $lotItem->wipeOutSoldInfo();
            $dataSaver->saveLotItem($lotItem, $editorUserId);
            return Result::new()->construct($lotItem, $auctionLot);
        }

        if ($result->shouldUnsell()) {
            $lotItem->wipeOutSoldInfo();
            $auctionLot->toUnsold();
        }

        $user = null;
        if ($result->shouldSell()) {
            if ($input->internetBid === 'N') {
                $isInternetBid = false;
            } elseif ($input->internetBid === 'Y') {
                $isInternetBid = true;
            } else {
                $isInternetBid = $lotItem->InternetBid;
            }

            $winningBidderId = null;
            if ($result->shouldSellWithWinner()) {
                $user = $dataSaver->updateWinningUser(
                    $input->userInputDto,
                    $input->userConfigDto,
                    $auctionId,
                    $bidderPremium
                );
                $winningBidderId = $user->Id;
            }

            $lotItem->assignSoldInfo(
                $auctionId,
                $dataProvider->detectCurrentDateUtc(),
                Cast::toFloat($input->hammerPrice),
                $isInternetBid,
                $winningBidderId
            );
            $auctionLot->toSold();
        }

        $dataSaver->saveLotItem($lotItem, $editorUserId);
        $dataSaver->saveAuctionLot($auctionLot, $editorUserId);

        return Result::new()->construct($lotItem, $auctionLot, $user);
    }
}
