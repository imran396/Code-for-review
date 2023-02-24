<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\Payment;


use Payment_Opayo;

trait OpayoGateManagerCreateTrait
{
    /**
     * @var Payment_Opayo|null
     */
    protected ?Payment_Opayo $gateManager = null;


    protected function createOpayoGateManager(): Payment_Opayo
    {
        return $this->gateManager ?: Payment_Opayo::new();
    }

    public function setOpayoGateManager(Payment_Opayo $gateManager): static
    {
        $this->gateManager = $gateManager;
        return $this;
    }
}
