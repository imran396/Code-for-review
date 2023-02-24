<?php
/**
 * SAM-7984: Adjustments for Settlement printable with responsive layout [dev only]
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           05-01, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\Translation;

/**
 * Trait SettlementTranslatorCreateTrait
 * @package Sam\Settlement\Printable\Internal\Translation
 */
trait SettlementTranslatorCreateTrait
{
    protected ?SettlementTranslator $settlementTranslator = null;

    /**
     * @return SettlementTranslator
     */
    protected function createSettlementTranslator(): SettlementTranslator
    {
        return $this->settlementTranslator ?: SettlementTranslator::new();
    }

    /**
     * @param SettlementTranslator $settlementTranslator
     * @return $this
     * @internal
     */
    public function setSettlementTranslator(SettlementTranslator $settlementTranslator): static
    {
        $this->settlementTranslator = $settlementTranslator;
        return $this;
    }
}
