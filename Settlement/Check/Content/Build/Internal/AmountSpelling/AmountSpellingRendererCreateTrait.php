<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\AmountSpelling;

/**
 * Trait AmountSpellingRendererCreateTrait
 * @package Sam\Settlement\Check
 */
trait AmountSpellingRendererCreateTrait
{
    protected ?AmountSpellingRenderer $amountSpellingRenderer = null;

    /**
     * @return AmountSpellingRenderer
     */
    protected function createAmountSpellingRenderer(): AmountSpellingRenderer
    {
        return $this->amountSpellingRenderer ?: AmountSpellingRenderer::new();
    }

    /**
     * @param AmountSpellingRenderer $amountSpellingRenderer
     * @return $this
     * @internal
     */
    public function setAmountSpellingRenderer(AmountSpellingRenderer $amountSpellingRenderer): static
    {
        $this->amountSpellingRenderer = $amountSpellingRenderer;
        return $this;
    }
}
