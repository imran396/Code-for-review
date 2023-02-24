<?php
/**
 * SAM-10339: Fetch US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE from entity accounts
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Render\Amount;

/**
 * Trait LotAmountRendererFactoryCreateTrait
 * @package Sam\Lot\Render\Amount
 */
trait LotAmountRendererFactoryCreateTrait
{
    protected ?LotAmountRendererFactory $lotAmountRendererFactory = null;

    /**
     * @return LotAmountRendererFactory
     */
    protected function createLotAmountRendererFactory(): LotAmountRendererFactory
    {
        return $this->lotAmountRendererFactory ?: LotAmountRendererFactory::new();
    }

    /**
     * @param LotAmountRendererFactory $lotAmountRendererFactory
     * @return $this
     * @internal
     */
    public function setLotAmountRendererFactory(LotAmountRendererFactory $lotAmountRendererFactory): static
    {
        $this->lotAmountRendererFactory = $lotAmountRendererFactory;
        return $this;
    }
}
