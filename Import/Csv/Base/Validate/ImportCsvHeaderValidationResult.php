<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Base\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Container for CSV columns headers validation
 *
 * Class ImportCsvHeaderValidationResult
 * @package Sam\Import\Csv\Base\Validate
 */
class ImportCsvHeaderValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_COLUMN_CAN_NOT_BE_MAPPED = 1;
    public const ERR_ABSENT_REQUIRED_COLUMN = 2;
    public const ERR_COLUMN_NOT_UNIQUE = 3;

    protected const PAYLOAD_COLUMN_NAME = 'columnName';

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
                self::ERR_COLUMN_CAN_NOT_BE_MAPPED => 'Column can not be mapped',
                self::ERR_ABSENT_REQUIRED_COLUMN => 'Required column is absent',
                self::ERR_COLUMN_NOT_UNIQUE => 'Column is not unique',
            ]
        );
        return $this;
    }

    public function addError(int $errorCode, string $columnName): void
    {
        $this->getResultStatusCollector()->addError(
            $errorCode,
            null,
            [
                self::PAYLOAD_COLUMN_NAME => $columnName
            ]
        );
    }

    /**
     * Extract error column name from payload
     *
     * @param ResultStatus $resultStatus
     * @return string
     */
    public function extractColumnName(ResultStatus $resultStatus): string
    {
        return $resultStatus->getPayload()[self::PAYLOAD_COLUMN_NAME] ?? '';
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function getErrorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    public function getErrorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
