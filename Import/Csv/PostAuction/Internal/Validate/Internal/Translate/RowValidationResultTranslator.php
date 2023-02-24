<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
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

namespace Sam\Import\Csv\PostAuction\Internal\Validate\Internal\Translate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\PostAuction\Internal\Validate\Internal\ValidateRow\RowValidationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Provides translation of post auction CSV file row validation errors
 *
 * Class RowValidationResultTranslator
 * @package Sam\Import\Csv\PostAuction\Internal\Validate\Internal\Translate
 */
class RowValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        RowValidationResult::ERR_LOT_NUMBER_INVALID => 'import.csv.post_auction.lot_number.invalid',
        RowValidationResult::ERR_HAMMER_PRICE_INVALID => 'import.csv.post_auction.hammer_price.invalid',
        RowValidationResult::ERR_HAMMER_PRICE_REQUIRED => 'import.csv.post_auction.hammer_price.required',
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
