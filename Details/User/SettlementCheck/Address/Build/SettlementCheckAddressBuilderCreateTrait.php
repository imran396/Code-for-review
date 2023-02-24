<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\SettlementCheck\Address\Build;

/**
 * Trait SettlementCheckAddressBuilderCreateTrait
 * @package Sam\Details\User\SettlementCheck\Address\Build
 */
trait SettlementCheckAddressBuilderCreateTrait
{
    protected ?SettlementCheckAddressBuilder $settlementCheckAddressBuilder = null;

    protected function createSettlementCheckAddressBuilder(): SettlementCheckAddressBuilder
    {
        return $this->settlementCheckAddressBuilder ?: SettlementCheckAddressBuilder::new();
    }

    /**
     * @internal
     */
    public function setSettlementCheckAddressBuilder(SettlementCheckAddressBuilder $settlementCheckAddressBuilder): static
    {
        $this->settlementCheckAddressBuilder = $settlementCheckAddressBuilder;
        return $this;
    }
}
