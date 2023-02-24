<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Amount;

/**
 * Trait AmountRendererCreateTrait
 * @package Sam\Settlement\Check
 */
trait AmountRendererCreateTrait
{
    protected ?AmountRenderer $amountRenderer = null;

    /**
     * @return AmountRenderer
     */
    protected function createAmountRenderer(): AmountRenderer
    {
        return $this->amountRenderer ?: AmountRenderer::new();
    }

    /**
     * @param AmountRenderer $amountRenderer
     * @return $this
     * @internal
     */
    public function setAmountRenderer(AmountRenderer $amountRenderer): static
    {
        $this->amountRenderer = $amountRenderer;
        return $this;
    }
}
