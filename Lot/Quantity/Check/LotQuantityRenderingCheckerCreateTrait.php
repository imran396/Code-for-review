<?php
/**
 * SAM-9424: Disabled 'Display lot quantity in catalog' does not make effect at Compact view
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Quantity\Check;

/**
 * Trait LotQuantityRenderingCheckerCreateTrait
 * @package Sam\Lot\Quantity\Validate
 */
trait LotQuantityRenderingCheckerCreateTrait
{
    /**
     * @var LotQuantityChecker|null
     */
    protected ?LotQuantityChecker $lotQuantityRenderingChecker = null;

    /**
     * @return LotQuantityChecker
     */
    protected function createLotQuantityRenderingChecker(): LotQuantityChecker
    {
        return $this->lotQuantityRenderingChecker ?: LotQuantityChecker::new();
    }

    /**
     * @param LotQuantityChecker $lotQuantityRenderingChecker
     * @return $this
     * @internal
     */
    public function setLotQuantityRenderingChecker(LotQuantityChecker $lotQuantityRenderingChecker): static
    {
        $this->lotQuantityRenderingChecker = $lotQuantityRenderingChecker;
        return $this;
    }
}
