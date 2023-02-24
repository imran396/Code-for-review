<?php
/**
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Validate\Translate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\View\Admin\Form\BuyersPremiumForm\Validate\BuyersPremiumValidationResult;

/**
 * Class BuyersPremiumValidationErrorTranslator
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Validate\Translate
 */
class BuyersPremiumValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        BuyersPremiumValidationResult::ERR_NAME_REQUIRED => 'buyers_premium.name.required',
        BuyersPremiumValidationResult::ERR_SHORT_NAME_REQUIRED => 'buyers_premium.short_name.required',
        BuyersPremiumValidationResult::ERR_SHORT_NAME_EXIST => 'buyers_premium.short_name.exist',
        BuyersPremiumValidationResult::ERR_CALCULATION_METHOD_INVALID => 'buyers_premium.calculation_method.invalid',
        BuyersPremiumValidationResult::ERR_ADDITIONAL_BP_INTERNET_INVALID => 'buyers_premium.additional_bp_internet.invalid',
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
