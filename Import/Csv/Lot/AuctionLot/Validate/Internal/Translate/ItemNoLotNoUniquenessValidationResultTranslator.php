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

namespace Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Translate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Unique\ItemNoLotNoUniquenessValidationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Provides translation of lot No and Item no uniqueness validation errors
 *
 * Class ItemNoLotNoUniquenessValidationResultTranslator
 * @package Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Translate
 * @internal
 */
class ItemNoLotNoUniquenessValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        ItemNoLotNoUniquenessValidationResult::ERR_ITEM_NO_DUPLICATED_IN_INPUT => 'import.csv.lot_item.item_no_exist_in_input',
        ItemNoLotNoUniquenessValidationResult::ERR_ITEM_NO_DUPLICATED_IN_DB => 'import.csv.lot_item.item_no_exist_in_db',
        ItemNoLotNoUniquenessValidationResult::WARN_LOT_NO_DUPLICATED_IN_INPUT => 'import.csv.auction_lot.lot_no_exist_in_input',
        ItemNoLotNoUniquenessValidationResult::WARN_LOT_NO_DUPLICATED_IN_DB => 'import.csv.auction_lot.lot_no_exist_in_db',
        ItemNoLotNoUniquenessValidationResult::ERR_REPEATEDLY_IDENTIFIED_ITEM => 'import.csv.lot_item.repeatedly_identified_item'
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
