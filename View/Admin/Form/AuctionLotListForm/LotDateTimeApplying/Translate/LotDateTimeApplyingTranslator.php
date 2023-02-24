<?php
/**
 * SAM-10180: Extract logic of date and time assignment to auction lots collection from the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Translate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Validate\LotDateTimeApplyingValidationResult;

/**
 * Class LotDateTimeApplyingTranslator
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Translate
 */
class LotDateTimeApplyingTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const VALIDATION_ERROR_TRANSLATION_MAP = [
        LotDateTimeApplyingValidationResult::ERR_DATE_ASSIGNMENT_STRATEGY_INVALID => 'lot.list_form.lot_date_time_applying.date_assignment_strategy_invalid',
        LotDateTimeApplyingValidationResult::ERR_START_DATE_REQUIRED => 'lot.list_form.lot_date_time_applying.start_date_required',
        LotDateTimeApplyingValidationResult::ERR_START_DATE_INVALID_FORMAT => 'lot.list_form.lot_date_time_applying.start_date_invalid_format',
        LotDateTimeApplyingValidationResult::ERR_START_CLOSING_DATE_REQUIRED => 'lot.list_form.lot_date_time_applying.start_closing_date_required',
        LotDateTimeApplyingValidationResult::ERR_START_CLOSING_DATE_INVALID_FORMAT => 'lot.list_form.lot_date_time_applying.start_closing_date_invalid_format',
        LotDateTimeApplyingValidationResult::ERR_TIMEZONE_INVALID => 'lot.list_form.lot_date_time_applying.timezone_invalid',
        LotDateTimeApplyingValidationResult::ERR_START_DATE_GREATER_THAN_START_CLOSING_DATE => 'lot.list_form.lot_date_time_applying.start_date_greater_than_start_closing_date',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function translateValidationError(int $errorCode, int $adminDateFormat, string $language): string
    {
        $id = self::VALIDATION_ERROR_TRANSLATION_MAP[$errorCode] ?? '';
        if (!$id) {
            return '';
        }
        $parameters = [
            'dateFormat' => $adminDateFormat === Constants\Date::ADF_US ? 'mm/dd/yyyy' : 'dd-mm-yyyy'
        ];
        return $this->getAdminTranslator()->trans($id, $parameters, 'admin_validation', $language);
    }

    public function makeSuccessMessage(int $lotCount, string $language): string
    {
        return $this->getAdminTranslator()->trans('lot.list_form.lot_date_time_applying.success', ['lotCount' => $lotCount], 'admin_message', $language);
    }
}
