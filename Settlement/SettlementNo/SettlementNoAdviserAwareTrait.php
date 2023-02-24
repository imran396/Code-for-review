<?php
/**
 * SAM-4366: Settlement No Adviser class
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

namespace Sam\Settlement\SettlementNo;

/**
 * Trait SettlementNoAdviserAwareTrait
 * @package Sam\Settlement\SettlementNo
 */
trait SettlementNoAdviserAwareTrait
{
    protected ?SettlementNoAdviser $settlementNoAdviser = null;

    /**
     * @return SettlementNoAdviser
     */
    protected function getSettlementNoAdviser(): SettlementNoAdviser
    {
        if ($this->settlementNoAdviser === null) {
            $this->settlementNoAdviser = SettlementNoAdviser::new();
        }
        return $this->settlementNoAdviser;
    }

    /**
     * @param SettlementNoAdviser $settlementNoAdviser
     * @return static
     * @internal
     */
    public function setSettlementNo(SettlementNoAdviser $settlementNoAdviser): static
    {
        $this->settlementNoAdviser = $settlementNoAdviser;
        return $this;
    }
}
