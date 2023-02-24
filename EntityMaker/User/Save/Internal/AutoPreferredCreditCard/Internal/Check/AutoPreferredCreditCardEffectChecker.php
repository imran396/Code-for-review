<?php
/**
 * Check conditions that should lead to assigning "Preferred Bidder" privilege
 * Assign "Preferred Bidder" privilege, when
 * - CC Info is modified,
 * - Target user has Bidder role,
 * - System parameter "AUTO_PREFERRED_CREDIT_CARD" is On
 *
 * SAM-6853: Settings > System Parameters > User options - "Auto assign Preferred bidder privileges upon credit card update" condition is not working properly
 *
 * TODO: Must be applied for all input context (SAM-9836)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check\AutoPreferredCreditCardEffectCheckingInput as Input;
use Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check\AutoPreferredCreditCardEffectCheckingResult as Result;
use Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check\Internal\Load\DataProviderCreateTrait;

/**
 * Class EffectChecker
 * @package Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check
 */
class AutoPreferredCreditCardEffectChecker extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check conditions that should lead to assigning "Preferred Bidder" privilege
     * @param Input $input
     * @return Result
     */
    public function check(Input $input): Result
    {
        $dataProvider = $this->createDataProvider();
        $result = Result::new()->construct();

        if (!$input->mode->isWebResponsive()) {
            return $result->addInfo(Result::INFO_MODE_IS_NOT_WEB_RESPONSIVE);
        }

        $isAutoPreferredCreditCard = $dataProvider->loadAutoPreferredCreditCard();
        if (!$isAutoPreferredCreditCard) {
            return $result->addInfo(Result::INFO_AUTO_PREFERRED_CREDIT_CARD_DISABLED);
        }

        if (!$input->willTargetUserHaveBidderRole) {
            return $result->addInfo(Result::INFO_TARGET_USER_WITHOUT_BIDDER_ROLE);
        }

        if (
            $input->isSetCcNumber
            && (string)$input->inputCcNumber !== ''
        ) {
            $ccHash = $dataProvider->createHash($input->inputCcNumber);
            if ($input->actualCcNumberHash !== $ccHash) {
                return $result->addSuccess(Result::OK_CC_NUMBER_MODIFIED);
            }
        }

        if (
            $input->isSetCcExpDate
            && $input->actualCcExpDate !== (string)$input->inputCcExpDate
        ) {
            return $result->addSuccess(Result::OK_CC_EXP_DATE_MODIFIED);
        }

        if ($input->isSetCcType) {
            $creditCardId = $dataProvider->loadCreditCardIdByName((string)$input->inputCcType);
            if ($creditCardId !== $input->actualCcType) {
                return $result->addSuccess(Result::OK_CC_TYPE_MODIFIED);
            }
        }

        return $result->addInfo(Result::INFO_CC_INFO_NOT_MODIFIED);
    }
}
