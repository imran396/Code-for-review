<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Unique;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Container for item No and lot No validation errors
 *
 * Class ItemNoLotNoUniquenessValidationResult
 * @package Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Unique
 */
class ItemNoLotNoUniquenessValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_ITEM_NO_DUPLICATED_IN_INPUT = 1;
    public const ERR_ITEM_NO_DUPLICATED_IN_DB = 2;
    public const ERR_REPEATEDLY_IDENTIFIED_ITEM = 3;

    public const WARN_LOT_NO_DUPLICATED_IN_INPUT = 21;
    public const WARN_LOT_NO_DUPLICATED_IN_DB = 22;
    public const WARN_REPEATEDLY_IDENTIFIED_ITEM = 23;

    protected const PAYLOAD_ROW_INDEX = 'rowIndex';
    protected const PAYLOAD_ITEM_NO = 'itemNo';
    protected const PAYLOAD_LOT_NO = 'lotNo';
    protected const PAYLOAD_REPEATED_AT_ROWS = 'repeatedAtRows';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_ITEM_NO_DUPLICATED_IN_INPUT => 'Item no duplicated in input',
                self::ERR_ITEM_NO_DUPLICATED_IN_DB => 'Item no duplicated in DB',
                self::ERR_REPEATEDLY_IDENTIFIED_ITEM => 'Repeatedly identifier item',
            ],
            [],
            [
                self::WARN_LOT_NO_DUPLICATED_IN_INPUT => 'Lot no duplicated in input',
                self::WARN_LOT_NO_DUPLICATED_IN_DB => 'Lot no duplicated in DB',
            ]
        );
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasWarning(): bool
    {
        return $this->getResultStatusCollector()->hasWarning();
    }

    public function getErrorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    public function addItemNoDuplicationError(int $rowIndex, string $itemNo, bool $inInput): void
    {
        $this->getResultStatusCollector()->addError(
            $inInput ? self::ERR_ITEM_NO_DUPLICATED_IN_INPUT : self::ERR_ITEM_NO_DUPLICATED_IN_DB,
            null,
            [
                self::PAYLOAD_ROW_INDEX => $rowIndex,
                self::PAYLOAD_ITEM_NO => $itemNo,
            ]
        );
    }

    public function addLotNoDuplicationWarning(int $rowIndex, string $lotNo, bool $inInput): void
    {
        $this->getResultStatusCollector()->addWarning(
            $inInput ? self::WARN_LOT_NO_DUPLICATED_IN_INPUT : self::WARN_LOT_NO_DUPLICATED_IN_DB,
            null,
            [
                self::PAYLOAD_ROW_INDEX => $rowIndex,
                self::PAYLOAD_LOT_NO => $lotNo,
            ]
        );
    }

    public function addRepeatedlyIdentifiedItemError(int $rowIndex, array $duplicationRowIndexes): void
    {
        $this->getResultStatusCollector()->addError(
            self::ERR_REPEATEDLY_IDENTIFIED_ITEM,
            null,
            [
                self::PAYLOAD_ROW_INDEX => $rowIndex,
                self::PAYLOAD_REPEATED_AT_ROWS => implode(', ', $duplicationRowIndexes),
            ]
        );
    }

    public function addRepeatedlyIdentifiedItemWarning(int $rowIndex, array $duplicationRowIndexes): void
    {
        $this->getResultStatusCollector()->addWarning(
            self::WARN_REPEATEDLY_IDENTIFIED_ITEM,
            null,
            [
                self::PAYLOAD_ROW_INDEX => $rowIndex,
                self::PAYLOAD_REPEATED_AT_ROWS => implode(', ', $duplicationRowIndexes),
            ]
        );
    }

    public function extractRowIndex(ResultStatus $resultStatus): ?int
    {
        return $resultStatus->getPayload()[self::PAYLOAD_ROW_INDEX] ?? null;
    }

    public function extractDuplicatedItemNo(ResultStatus $resultStatus): string
    {
        return $resultStatus->getPayload()[self::PAYLOAD_ITEM_NO] ?? '';
    }

    public function extractDuplicatedLotNo(ResultStatus $resultStatus): string
    {
        return $resultStatus->getPayload()[self::PAYLOAD_LOT_NO] ?? '';
    }

    public function extractRepeatedAtRowsIndexes(ResultStatus $resultStatus): array
    {
        return $resultStatus->getPayload()[self::PAYLOAD_REPEATED_AT_ROWS] ?? [];
    }

    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    public function getWarningStatuses(): array
    {
        return $this->getResultStatusCollector()->getWarningStatuses();
    }
}
