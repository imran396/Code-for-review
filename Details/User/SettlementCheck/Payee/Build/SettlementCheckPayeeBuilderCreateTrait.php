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

namespace Sam\Details\User\SettlementCheck\Payee\Build;

/**
 * Trait SettlementCheckPayeeBuilderCreateTrait
 * @package Sam\Details\User\SettlementCheck\Payee\Build
 */
trait SettlementCheckPayeeBuilderCreateTrait
{
    /**
     * @var SettlementCheckPayeeBuilder|null
     */
    protected ?SettlementCheckPayeeBuilder $settlementCheckPayeeBuilder = null;

    protected function createSettlementCheckPayeeBuilder(): SettlementCheckPayeeBuilder
    {
        return $this->settlementCheckPayeeBuilder ?: SettlementCheckPayeeBuilder::new();
    }

    /**
     * @internal
     */
    public function setSettlementCheckPayeeBuilder(SettlementCheckPayeeBuilder $settlementCheckPayeeBuilder): static
    {
        $this->settlementCheckPayeeBuilder = $settlementCheckPayeeBuilder;
        return $this;
    }
}
