<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\ApplyPayment\Multiple\Validate;

/**
 * Trait MultipleSettlementCheckPaymentApplyingValidatorCreateTrait
 * @package Sam\Settlement\Check
 */
trait MultipleSettlementCheckPaymentApplyingValidatorCreateTrait
{
    protected ?MultipleSettlementCheckPaymentApplyingValidator $multipleSettlementCheckPaymentApplyingValidator = null;

    /**
     * @return MultipleSettlementCheckPaymentApplyingValidator
     */
    protected function createMultipleSettlementCheckPaymentApplyingValidator(): MultipleSettlementCheckPaymentApplyingValidator
    {
        return $this->multipleSettlementCheckPaymentApplyingValidator ?: MultipleSettlementCheckPaymentApplyingValidator::new();
    }

    /**
     * @param MultipleSettlementCheckPaymentApplyingValidator $multipleSettlementCheckPaymentApplyingValidator
     * @return $this
     * @internal
     */
    public function setMultipleSettlementCheckPaymentApplyingValidator(MultipleSettlementCheckPaymentApplyingValidator $multipleSettlementCheckPaymentApplyingValidator): static
    {
        $this->multipleSettlementCheckPaymentApplyingValidator = $multipleSettlementCheckPaymentApplyingValidator;
        return $this;
    }
}
