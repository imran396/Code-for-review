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

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\RangesValidationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Provides translation of bid increment range validation errors
 *
 * Class RangesValidationResultTranslator
 * @package Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\Translate
 */
class RangesValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        RangesValidationResult::ERR_ABSENT_ZERO_AMOUNT => 'import.csv.bid_increment.collection.absent_zero_amount',
        RangesValidationResult::ERR_DUPLICATE_RANGE => 'import.csv.bid_increment.collection.duplicate_range',
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
        return $this->getAdminTranslator()->trans($translationKey, [], self::TRANSLATION_DOMAIN);
    }
}
