<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\Translate;

use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\RowValidationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Provides translation of bid increment CSV file row validation errors
 *
 * Class RowValidationResultTranslator
 * @package Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\Translate
 */
class RowValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    /**
     * @var array
     */
    protected array $columnNames;

    protected const TRANSLATION_KEYS = [
        RowValidationResult::ERR_AMOUNT_INVALID => 'import.csv.bid_increment.amount.invalid',
        RowValidationResult::ERR_AMOUNT_WITH_THOUSAND_SEPARATOR => 'import.csv.bid_increment.amount.with_thousand_separator',
        RowValidationResult::ERR_INCREMENT_INVALID => 'import.csv.bid_increment.increment.invalid',
        RowValidationResult::ERR_INCREMENT_WITH_THOUSAND_SEPARATOR => 'import.csv.bid_increment.increment.with_thousand_separator',
    ];

    protected const COLUMNS = [
        RowValidationResult::ERR_AMOUNT_INVALID => Constants\Csv\BidIncrement::AMOUNT,
        RowValidationResult::ERR_AMOUNT_WITH_THOUSAND_SEPARATOR => Constants\Csv\BidIncrement::AMOUNT,
        RowValidationResult::ERR_INCREMENT_INVALID => Constants\Csv\BidIncrement::INCREMENT,
        RowValidationResult::ERR_INCREMENT_WITH_THOUSAND_SEPARATOR => Constants\Csv\BidIncrement::INCREMENT,
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

    public function construct(array $columnNames): static
    {
        $this->columnNames = $columnNames;
        return $this;
    }

    /**
     * Translate error message for the result status error code
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

        $column = self::COLUMNS[$code];
        $payload = $resultStatus->getPayload();
        $payload['columnName'] = $this->columnNames[$column] ?? '';

        return $this->getAdminTranslator()->trans(
            $translationKey,
            $this->makeTranslationParameters($payload),
            self::TRANSLATION_DOMAIN
        );
    }

    protected function makeTranslationParameters(array $resultStatusPayload): array
    {
        $parameters = array_filter($resultStatusPayload, 'is_scalar');
        return $parameters;
    }
}
