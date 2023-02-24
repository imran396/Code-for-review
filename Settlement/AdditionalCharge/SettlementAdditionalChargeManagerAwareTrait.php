<?php
/**
 *
 * SAM-4557: Settlement management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-02
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\AdditionalCharge;

/**
 * Trait SettlementAdditionalChargeManagerAwareTrait
 * @package Sam\Settlement\AdditionalCharge
 */
trait SettlementAdditionalChargeManagerAwareTrait
{
    protected ?SettlementAdditionalChargeManager $settlementAdditionalChargeManager = null;

    /**
     * @return SettlementAdditionalChargeManager
     */
    protected function getSettlementAdditionalChargeManager(): SettlementAdditionalChargeManager
    {
        if ($this->settlementAdditionalChargeManager === null) {
            $this->settlementAdditionalChargeManager = SettlementAdditionalChargeManager::new();
        }
        return $this->settlementAdditionalChargeManager;
    }

    /**
     * @param SettlementAdditionalChargeManager $settlementAdditionalChargeManager
     * @return static
     * @internal
     */
    public function setSettlementAdditionalChargeManager(SettlementAdditionalChargeManager $settlementAdditionalChargeManager): static
    {
        $this->settlementAdditionalChargeManager = $settlementAdditionalChargeManager;
        return $this;
    }
}
