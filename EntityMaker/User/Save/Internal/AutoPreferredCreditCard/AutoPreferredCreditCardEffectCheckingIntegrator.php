<?php
/**
 * Check conditions that should lead to assigning "Preferred Bidder" privilege
 *
 * SAM-6853: Settings > System Parameters > User options - "Auto assign Preferred bidder privileges upon credit card update" condition is not working properly
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

namespace Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check\AutoPreferredCreditCardEffectChecker;
use Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check\AutoPreferredCreditCardEffectCheckingInput;
use Sam\EntityMaker\User\Save\UserMakerProducer;

/**
 * Class AutoPreferredCreditCardCheckingIntegrator
 * @package Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard
 */
class AutoPreferredCreditCardEffectCheckingIntegrator extends CustomizableClass
{
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
     * @param UserMakerProducer $userMakerProducer
     * @return bool
     */
    public function hasEffect(UserMakerProducer $userMakerProducer): bool
    {
        $inputDto = $userMakerProducer->getInputDto();
        $configDto = $userMakerProducer->getConfigDto();
        $willTargetUserHaveBidderRole = $userMakerProducer->getUserMakerAccessChecker()->willTargetUserHaveBidderRole();
        $userBilling = $userMakerProducer->getUserBillingOrCreate();
        $input = AutoPreferredCreditCardEffectCheckingInput::new()->construct(
            $configDto->mode,
            $willTargetUserHaveBidderRole,
            $userBilling->CcNumberHash,
            $userBilling->CcExpDate,
            $userBilling->CcType,
            $inputDto->billingCcNumber,
            $inputDto->billingCcExpDate,
            $inputDto->billingCcType,
            isset($inputDto->billingCcNumber),
            isset($inputDto->billingCcExpDate),
            isset($inputDto->billingCcType)
        );
        $result = AutoPreferredCreditCardEffectChecker::new()->check($input);
        log_trace($result->logMessage());
        return $result->isEffect();
    }
}
