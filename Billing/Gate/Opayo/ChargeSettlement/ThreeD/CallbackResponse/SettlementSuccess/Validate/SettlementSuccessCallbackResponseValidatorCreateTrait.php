<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Validate;

trait SettlementSuccessCallbackResponseValidatorCreateTrait
{
    /**
     * @var SettlementSuccessCallbackResponseValidator|null
     */
    protected ?SettlementSuccessCallbackResponseValidator $settlementSuccessCallbackResponseValidator = null;

    /**
     * @return SettlementSuccessCallbackResponseValidator
     */
    protected function createSettlementSuccessCallbackResponseValidator(): SettlementSuccessCallbackResponseValidator
    {
        return $this->settlementSuccessCallbackResponseValidator ?: SettlementSuccessCallbackResponseValidator::new();
    }

    /**
     * @param SettlementSuccessCallbackResponseValidator $validator
     * @return static
     * @internal
     */
    public function setSettlementSuccessCallbackResponseValidator(SettlementSuccessCallbackResponseValidator $validator): static
    {
        $this->settlementSuccessCallbackResponseValidator = $validator;
        return $this;
    }
}
