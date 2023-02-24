<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           June 1, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed\Handle;

trait SettlementFailedCallbackResponseHandlerCreateTrait
{
    /**
     * @var SettlementFailedCallbackResponseHandler|null
     */
    protected ?SettlementFailedCallbackResponseHandler $settlementFailedCallbackResponseHandler = null;

    /**
     * @return SettlementFailedCallbackResponseHandler
     */
    protected function createSettlementFailedCallbackResponseHandler(): SettlementFailedCallbackResponseHandler
    {
        return $this->settlementFailedCallbackResponseHandler ?: SettlementFailedCallbackResponseHandler::new();
    }

    /**
     * @param SettlementFailedCallbackResponseHandler $handler
     * @return static
     * @internal
     */
    public function setSettlementFailedCallbackResponseHandler(SettlementFailedCallbackResponseHandler $handler): static
    {
        $this->settlementFailedCallbackResponseHandler = $handler;
        return $this;
    }
}
