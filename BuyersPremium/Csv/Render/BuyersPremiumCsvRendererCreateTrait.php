<?php
/**
 * SAM-10464: Separate BP manager to services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Csv\Render;

/**
 * Trait BuyersPremiumCsvRendererCreateTrait
 * @package Sam\BuyersPremium\Csv\Render
 */
trait BuyersPremiumCsvRendererCreateTrait
{
    protected ?BuyersPremiumCsvRenderer $buyersPremiumCsvRenderer = null;

    /**
     * @return BuyersPremiumCsvRenderer
     */
    protected function createBuyersPremiumCsvRenderer(): BuyersPremiumCsvRenderer
    {
        return $this->buyersPremiumCsvRenderer ?: BuyersPremiumCsvRenderer::new();
    }

    /**
     * @param BuyersPremiumCsvRenderer $buyersPremiumCsvRenderer
     * @return $this
     * @internal
     */
    public function setBuyersPremiumCsvRenderer(BuyersPremiumCsvRenderer $buyersPremiumCsvRenderer): static
    {
        $this->buyersPremiumCsvRenderer = $buyersPremiumCsvRenderer;
        return $this;
    }
}
