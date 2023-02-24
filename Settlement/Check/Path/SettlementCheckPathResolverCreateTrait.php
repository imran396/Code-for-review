<?php
/**
 * SAM-7984: Adjustments for Settlement printable with responsive layout [dev only]
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           06-13, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Path;

/**
 * Trait SettlementCheckPathResolverCreateTrait
 * @package Sam\Settlement\Check
 */
trait SettlementCheckPathResolverCreateTrait
{
    protected ?SettlementCheckPathResolver $settlementCheckPathResolver = null;

    /**
     * @return SettlementCheckPathResolver
     */
    protected function createSettlementCheckPathResolver(): SettlementCheckPathResolver
    {
        return $this->settlementCheckPathResolver ?: SettlementCheckPathResolver::new();
    }

    /**
     * @param SettlementCheckPathResolver $settlementCheckPathResolver
     * @return $this
     * @internal
     */
    public function setSettlementCheckPathResolver(SettlementCheckPathResolver $settlementCheckPathResolver): static
    {
        $this->settlementCheckPathResolver = $settlementCheckPathResolver;
        return $this;
    }
}
