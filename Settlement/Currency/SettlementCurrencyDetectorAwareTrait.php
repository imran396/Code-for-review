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

namespace Sam\Settlement\Currency;

/**
 * Trait SettlementCurrencyDetectorAwareTrait
 * @package Sam\Settlement\Currency
 */
trait SettlementCurrencyDetectorAwareTrait
{
    protected ?SettlementCurrencyDetector $settlementCurrencyDetector = null;

    /**
     * @return SettlementCurrencyDetector
     */
    protected function getSettlementCurrencyDetector(): SettlementCurrencyDetector
    {
        if ($this->settlementCurrencyDetector === null) {
            $this->settlementCurrencyDetector = SettlementCurrencyDetector::new();
        }
        return $this->settlementCurrencyDetector;
    }

    /**
     * @param SettlementCurrencyDetector $settlementCurrencyDetector
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setSettlementCurrencyDetector(SettlementCurrencyDetector $settlementCurrencyDetector): static
    {
        $this->settlementCurrencyDetector = $settlementCurrencyDetector;
        return $this;
    }
}
