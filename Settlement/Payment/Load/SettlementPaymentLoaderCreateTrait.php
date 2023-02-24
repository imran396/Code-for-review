<?php
/**
 * SAM-9960: Check Printing for Settlements: Payment List management at the "Settlement Edit" page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Payment\Load;

/**
 * Trait SettlementPaymentLoaderCreateTrait
 * @package Sam\Settlement\Payment\Load
 */
trait SettlementPaymentLoaderCreateTrait
{
    protected ?SettlementPaymentLoader $settlementPaymentLoader = null;

    /**
     * @return SettlementPaymentLoader
     */
    protected function createSettlementPaymentLoader(): SettlementPaymentLoader
    {
        return $this->settlementPaymentLoader ?: SettlementPaymentLoader::new();
    }

    /**
     * @param SettlementPaymentLoader $settlementPaymentLoader
     * @return static
     * @internal
     */
    public function setSettlementPaymentLoader(SettlementPaymentLoader $settlementPaymentLoader): static
    {
        $this->settlementPaymentLoader = $settlementPaymentLoader;
        return $this;
    }
}
