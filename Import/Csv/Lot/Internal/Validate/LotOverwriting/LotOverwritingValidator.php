<?php
/**
 * SAM-9462: Lot CSV import - fix item# and lot# uniqueness check
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\Validate\LotOverwriting;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\Common\LotItemIdDetectionResult;
use Sam\Import\Csv\Lot\Internal\Validate\LotOverwriting\LotOverwritingValidationResult as Result;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class LotOverwritingValidator
 * @package Sam\Import\Csv\Lot\Internal\Validate\Row
 */
class LotOverwritingValidator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use ConfigRepositoryAwareTrait;
    use LotRendererAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check absence of existing lots in input that can be overwritten in case when overwriting is denied.
     *
     * @param LotItemIdDetectionResult $lotItemIdDetectionResult
     * @param LotItemMakerInputDto $lotItemInputDto
     * @return LotOverwritingValidationResult
     */
    public function validateLotItem(
        LotItemIdDetectionResult $lotItemIdDetectionResult,
        LotItemMakerInputDto $lotItemInputDto
    ): Result {
        $result = $this->constructResult();

        /**
         * When overwriting is disabled, then we cannot detect existing lot item by item#,
         * thus input shouldn't contain its id that was found by item# earlier.
         */
        if ($lotItemIdDetectionResult->isFoundByItemNo()) {
            $itemNo = $this->takeItemNo($lotItemInputDto);
            $result->addLotItemOverwritingError($itemNo);
        }

        return $result;
    }

    /**
     * Check absence of existing auction lots in input that can be overwritten in case when overwriting is denied.
     *
     * @param LotItemIdDetectionResult $lotItemIdDetectionResult
     * @param AuctionLotMakerInputDto $auctionLotInputDto
     * @return LotOverwritingValidationResult
     */
    public function validateAuctionLot(
        LotItemIdDetectionResult $lotItemIdDetectionResult,
        AuctionLotMakerInputDto $auctionLotInputDto
    ): Result {
        $result = $this->constructResult();

        /**
         * When overwriting is disabled, then we cannot detect existing auction lot by lot#,
         * thus input shouldn't contain its id that was found by lot# earlier.
         */
        if ($lotItemIdDetectionResult->isFoundByLotNo()) {
            $lotNo = $this->takeLotNo($auctionLotInputDto);
            $result->addAuctionLotOverwritingError($lotNo);
        }

        return $result;
    }

    /**
     * Determine full item# from csv input
     *
     * @param LotItemMakerInputDto $lotItemInputDto
     * @return string
     */
    protected function takeItemNo(LotItemMakerInputDto $lotItemInputDto): ?string
    {
        return $this->cfg()->get('core->lot->itemNo->concatenated')
            ? $lotItemInputDto->itemFullNum
            : $this->getLotRenderer()->makeItemNo($lotItemInputDto->itemNum, $lotItemInputDto->itemNumExt);
    }

    /**
     * Determine full lot# from csv input
     *
     * @param AuctionLotMakerInputDto $auctionLotInputDto
     * @return string
     */
    protected function takeLotNo(AuctionLotMakerInputDto $auctionLotInputDto): ?string
    {
        return $this->cfg()->get('core->lot->lotNo->concatenated')
            ? $auctionLotInputDto->lotFullNum
            : $this->getLotRenderer()->makeLotNo(
                $auctionLotInputDto->lotNum,
                $auctionLotInputDto->lotNumExt,
                $auctionLotInputDto->lotNumPrefix
            );
    }

    protected function constructResult(): Result
    {
        $translator = $this->getAdminTranslator();
        $result = Result::new();
        $result->construct(
            [
                Result::ERR_LOT_ITEM_OVERWRITING => static function (ResultStatus $resultStatus) use ($translator, $result): string {
                    return $translator->trans(
                        'import.csv.lot_item.invalid_overwrite_attempt',
                        [
                            'itemNo' => $result->extractItemNo($resultStatus)
                        ],
                        'admin_validation'
                    );
                },
                Result::ERR_AUCTION_LOT_OVERWRITING => static function (ResultStatus $resultStatus) use ($translator, $result): string {
                    return $translator->trans(
                        'import.csv.auction_lot.invalid_overwrite_attempt',
                        [
                            'lotNo' => $result->extractLotNo($resultStatus)
                        ],
                        'admin_validation'
                    );
                }
            ]
        );

        return $result;
    }
}
