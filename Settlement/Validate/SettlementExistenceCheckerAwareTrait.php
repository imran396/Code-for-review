<?php
/**
 *
 * SAM-4557: Settlement management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/12/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Validate;

/**
 * Trait SettlementExistenceCheckerAwareTrait
 * @package Sam\Settlement\Validate
 */
trait SettlementExistenceCheckerAwareTrait
{
    protected ?SettlementExistenceChecker $settlementExistenceChecker = null;

    /**
     * @return SettlementExistenceChecker
     */
    protected function getSettlementExistenceChecker(): SettlementExistenceChecker
    {
        if ($this->settlementExistenceChecker === null) {
            $this->settlementExistenceChecker = SettlementExistenceChecker::new();
        }
        return $this->settlementExistenceChecker;
    }

    /**
     * @param SettlementExistenceChecker $settlementExistenceChecker
     * @return static
     * @internal
     */
    public function setSettlementExistenceChecker(SettlementExistenceChecker $settlementExistenceChecker): static
    {
        $this->settlementExistenceChecker = $settlementExistenceChecker;
        return $this;
    }
}
