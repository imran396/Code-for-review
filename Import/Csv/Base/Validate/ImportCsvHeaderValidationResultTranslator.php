<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Base\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Responsible for making a message for CSV columns headers validation error codes
 *
 * Class ImportCsvHeaderValidationResultTranslator
 * @package Sam\Import\Csv\Base\Validate
 */
class ImportCsvHeaderValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        ImportCsvHeaderValidationResult::ERR_COLUMN_CAN_NOT_BE_MAPPED => 'import.csv.general.column_cannot_be_mapped',
        ImportCsvHeaderValidationResult::ERR_ABSENT_REQUIRED_COLUMN => 'import.csv.general.required_column_absent',
        ImportCsvHeaderValidationResult::ERR_COLUMN_NOT_UNIQUE => 'import.csv.general.columns_not_unique',
    ];
    protected const TRANSLATION_DOMAIN = 'admin_validation';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Translate error message for result status
     *
     * @param ResultStatus $resultStatus
     * @return string
     */
    public function trans(ResultStatus $resultStatus): string
    {
        $code = $resultStatus->getCode();
        $translationKey = self::TRANSLATION_KEYS[$code] ?? '';
        if (!$translationKey) {
            log_error("Can't find translation for error with code {$code}");
            return '';
        }
        return $this->getAdminTranslator()->trans(
            $translationKey,
            $this->makeTranslationParameters($resultStatus->getPayload()),
            self::TRANSLATION_DOMAIN
        );
    }

    protected function makeTranslationParameters(array $resultStatusPayload): array
    {
        $parameters = array_filter($resultStatusPayload, 'is_scalar');
        return $parameters;
    }
}
