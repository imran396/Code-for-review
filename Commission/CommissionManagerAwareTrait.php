<?php
/**
 * Trait for CommissionManager
 *
 * SAM-4989: User Entity Maker
 *
 * @author        Victor Pautoff
 * @since         Jan 21, 2020
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Commission;

/**
 * Trait CommissionManagerAwareTrait
 * @package Sam\Commission
 */
trait CommissionManagerAwareTrait
{
    /**
     * @var CommissionManager|null
     */
    protected ?CommissionManager $commissionManager = null;

    /**
     * @return CommissionManager
     */
    protected function getCommissionManager(): CommissionManager
    {
        if ($this->commissionManager === null) {
            $this->commissionManager = CommissionManager::new();
        }
        return $this->commissionManager;
    }

    /**
     * @param CommissionManager $commissionManager
     * @return static
     * @internal
     */
    public function setCommissionManager(CommissionManager $commissionManager): static
    {
        $this->commissionManager = $commissionManager;
        return $this;
    }
}
