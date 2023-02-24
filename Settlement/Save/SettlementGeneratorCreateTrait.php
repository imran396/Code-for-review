<?php
/**
 * SAM-4557: Settlement management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/11/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Save;

/**
 * Trait SettlementGeneratorCreateTrait
 * @package Sam\Settlement\Save
 */
trait SettlementGeneratorCreateTrait
{
    protected ?SettlementGenerator $settlementGenerator = null;

    /**
     * @return SettlementGenerator
     */
    protected function createSettlementGenerator(): SettlementGenerator
    {
        return $this->settlementGenerator ?: SettlementGenerator::new();
    }

    /**
     * @param SettlementGenerator $settlementGenerator
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setSettlementGenerator(SettlementGenerator $settlementGenerator): static
    {
        $this->settlementGenerator = $settlementGenerator;
        return $this;
    }
}
