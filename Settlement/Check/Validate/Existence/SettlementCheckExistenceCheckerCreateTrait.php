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

namespace Sam\Settlement\Check\Validate\Existence;

/**
 * Trait SettlementCheckExistenceCheckerCreateTrait
 * @package Sam\Settlement\Check
 */
trait SettlementCheckExistenceCheckerCreateTrait
{
    protected ?SettlementCheckExistenceChecker $settlementCheckExistenceChecker = null;

    /**
     * @return SettlementCheckExistenceChecker
     */
    protected function createSettlementCheckExistenceChecker(): SettlementCheckExistenceChecker
    {
        return $this->settlementCheckExistenceChecker ?: SettlementCheckExistenceChecker::new();
    }

    /**
     * @param SettlementCheckExistenceChecker $settlementCheckExistenceChecker
     * @return $this
     * @internal
     */
    public function setSettlementCheckExistenceChecker(SettlementCheckExistenceChecker $settlementCheckExistenceChecker): static
    {
        $this->settlementCheckExistenceChecker = $settlementCheckExistenceChecker;
        return $this;
    }
}
