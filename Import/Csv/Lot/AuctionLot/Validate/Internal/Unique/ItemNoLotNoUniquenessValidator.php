<?php
/**
 * Service checks item# and lot# from csv input for duplication among other values in the same input,
 * and among values in DB in context of auction, when auction is known.
 * If auction context isn't known, then we can't validate uniqueness according DB side,
 * because we identify by existing item# the record that should be updated.
 *
 * SAM-9462: Lot CSV import - fix item# and lot# uniqueness check
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Unique;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\Import\Csv\Lot\AuctionLot\Internal\Dto\Row;
use Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Unique\Internal\Load\DataProviderCreateTrait;
use Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Unique\ItemNoLotNoUniquenessValidationResult as Result;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class ItemNoLotNoUniquenessValidator
 * @package Sam\Import\Csv\Lot\AuctionLot
 * @internal
 */
class ItemNoLotNoUniquenessValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
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
     * Check if lot No and item No are not related to another lot
     *
     * @param Row[] $rows
     * @param int|null $auctionId null means we import csv either in inventory or when creating new auction
     * @return Result
     */
    public function validate(array $rows, ?int $auctionId): Result
    {
        [
            $duplicatedItemNosInDb,
            $duplicatedLotNosInDb,
            $duplicatedItemNosInInput,
            $duplicatedLotNosInInput
        ] = $this->detectDuplicatedLotNumbers($rows, $auctionId);

        $rowIndexesPerLotItemIds = [];
        foreach ($rows as $rowIndex => $row) {
            $rowIndexesPerLotItemIds[$row->lotItemIdDetectionResult->lotItemId][] = $rowIndex;
        }

        $result = Result::new()->construct();
        foreach ($rows as $rowIndex => $row) {
            $isErrorInThisRow = false;
            /**
             * Add item# duplication errors.
             */
            $itemNo = $this->takeItemNo($row->lotItemInputDto);
            if (in_array($itemNo, $duplicatedItemNosInInput, true)) {
                $result->addItemNoDuplicationError($rowIndex, $itemNo, true);
                $isErrorInThisRow = true;
            }
            if (in_array($itemNo, $duplicatedItemNosInDb, true)) {
                /**
                 * This is impossible case. When item# presents in csv input and it presents in DB,
                 * then we should identify existing lot item by this item# without consideration of lot#,
                 * thus it is impossible to duplicate with DB records by item#.
                 */
                $result->addItemNoDuplicationError($rowIndex, $itemNo, false);
                $isErrorInThisRow = true;
            }

            /**
             * Add lot# duplication errors.
             */
            $lotNo = $this->takeLotNo($row->auctionLotInputDto);
            if (in_array($lotNo, $duplicatedLotNosInInput, true)) {
                $result->addLotNoDuplicationWarning($rowIndex, $lotNo, true);
            }
            if (in_array($lotNo, $duplicatedLotNosInDb, true)) {
                $result->addLotNoDuplicationWarning($rowIndex, $lotNo, false);
            }

            /**
             * Add error for repeatedly identified item, if another error status wasn't registered earlier.
             */
            $id = Cast::toInt($row->lotItemInputDto->id);
            if (
                !$isErrorInThisRow
                && $id
                && count($rowIndexesPerLotItemIds[$id]) > 1
            ) {
                $result->addRepeatedlyIdentifiedItemError($rowIndex, $rowIndexesPerLotItemIds[$id]);
            }
        }

        return $result;
    }

    /**
     * @param Row[] $rows
     * @param int|null $auctionId
     * @return array
     */
    protected function detectDuplicatedLotNumbers(array $rows, ?int $auctionId): array
    {
        [
            $pairsByItemNoInInput,
            $pairsByLotNoInInput,
            $duplicatedItemNosInInput,
            $duplicatedLotNosInInput
        ] = $this->collectItemNoAndLotNoPairsFromInput($rows);

        // Overwrite DB data by CSV input data
        if ($auctionId) {
            $dbPairs = $this->collectItemNoAndLotNoPairsFromDb($auctionId);
            $pairsByItemNo = array_replace($dbPairs, $pairsByItemNoInInput);
            $pairsByLotNo = array_replace(array_flip($dbPairs), $pairsByLotNoInInput);
        } else {
            $pairsByItemNo = $pairsByItemNoInInput;
            $pairsByLotNo = $pairsByLotNoInInput;
        }

        $pairsByItemNo = $this->filterEmpty($pairsByItemNo);
        $pairsByLotNo = $this->filterEmpty($pairsByLotNo);

        $duplicatedLotNosInDb = array_diff_assoc($pairsByItemNo, array_unique($pairsByItemNo));
        $duplicatedItemNosInDb = array_diff_assoc($pairsByLotNo, array_unique($pairsByLotNo));

        return [
            $duplicatedItemNosInDb,
            $duplicatedLotNosInDb,
            $duplicatedItemNosInInput,
            $duplicatedLotNosInInput
        ];
    }

    /**
     * Parse item-full-number, lot-full-number pairs from item/lot Dto fields
     * @param Row[] $rows
     * @return array
     */
    protected function collectItemNoAndLotNoPairsFromInput(array $rows): array
    {
        $pairsForLotsIdentifiedByItemNo = [];
        $pairsForLotsIdentifiedByLotNo = [];

        $allInputItemNos = $allInputLotNos = [];
        foreach ($rows as $rowIndex => $row) {
            $allInputItemNos[$rowIndex] = $this->takeItemNo($row->lotItemInputDto);
            $allInputLotNos[$rowIndex] = $this->takeLotNo($row->auctionLotInputDto);
        }
        $duplicatedItemNos = array_diff_assoc($allInputItemNos, array_unique($allInputItemNos));
        $duplicatedItemNos = array_unique($duplicatedItemNos);
        $duplicatedItemNos = $this->filterEmpty($duplicatedItemNos);
        $duplicatedLotNos = array_diff_assoc($allInputLotNos, array_unique($allInputLotNos));
        $duplicatedLotNos = array_unique($duplicatedLotNos);
        $duplicatedLotNos = $this->filterEmpty($duplicatedLotNos);

        foreach ($rows as $rowIndex => $row) {
            $itemNo = $allInputItemNos[$rowIndex];
            $lotNo = $allInputLotNos[$rowIndex];
            if (
                $itemNo !== ''
                && $lotNo !== ''
            ) {
                if (
                    in_array($itemNo, $duplicatedItemNos, true)
                    || in_array($lotNo, $duplicatedLotNos, true)
                ) {
                    // duplicated item#s and lot#s are skipped from the following checks
                    // (i.e. comparison with DB persisted values)
                    continue;
                }

                if ($row->lotItemIdDetectionResult->isFoundByLotNo()) {
                    $pairsForLotsIdentifiedByLotNo[$lotNo] = $itemNo;
                } else {
                    $pairsForLotsIdentifiedByItemNo[$itemNo] = $lotNo;
                }
            }
        }

        return [
            $pairsForLotsIdentifiedByItemNo,
            $pairsForLotsIdentifiedByLotNo,
            $duplicatedItemNos,
            $duplicatedLotNos
        ];
    }

    /**
     * Determine full item# from csv input
     * @param LotItemMakerInputDto $lotItemInputDto
     * @return string
     */
    protected function takeItemNo(LotItemMakerInputDto $lotItemInputDto): string
    {
        return $this->cfg()->get('core->lot->itemNo->concatenated')
            ? (string)$lotItemInputDto->itemFullNum
            : $this->getLotRenderer()->makeItemNo($lotItemInputDto->itemNum, $lotItemInputDto->itemNumExt);
    }

    /**
     * Determine full lot# from csv input
     * @param AuctionLotMakerInputDto $auctionLotInputDto
     * @return string
     */
    protected function takeLotNo(AuctionLotMakerInputDto $auctionLotInputDto): string
    {
        return $this->cfg()->get('core->lot->lotNo->concatenated')
            ? (string)$auctionLotInputDto->lotFullNum
            : $this->getLotRenderer()->makeLotNo(
                $auctionLotInputDto->lotNum,
                $auctionLotInputDto->lotNumExt,
                $auctionLotInputDto->lotNumPrefix
            );
    }

    /**
     * Load item and lot numbers pairs
     * {@internal Intended to be used in CSV lot importing module only}}
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    protected function collectItemNoAndLotNoPairsFromDb(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $result = [];
        $lotRenderer = $this->getLotRenderer();
        $dtos = $this->createDataProvider()->loadItemNoLotNoDtos($auctionId, $isReadOnlyDb);
        foreach ($dtos as $dto) {
            $itemNum = $lotRenderer->makeItemNoByParsed($dto->itemNoParsed);
            $result[$itemNum] = $lotRenderer->makeLotNoByParsed($dto->lotNoParsed);
        }
        return $result;
    }

    protected function filterEmpty(array $values): array
    {
        $filterFn = static function ($value) {
            return (string)$value !== '';
        };
        return array_filter($values, $filterFn);
    }
}
