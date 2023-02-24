<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\LotItem;

/**
 * Trait LotItemPlaceholderRendererCreateTrait
 * @package Sam\Sms\Template\Placeholder\LotItem
 */
trait LotItemPlaceholderRendererCreateTrait
{
    protected ?LotItemPlaceholderRenderer $lotItemPlaceholderRenderer = null;

    /**
     * @return LotItemPlaceholderRenderer
     */
    protected function createLotItemPlaceholderRenderer(): LotItemPlaceholderRenderer
    {
        return $this->lotItemPlaceholderRenderer ?: LotItemPlaceholderRenderer::new();
    }

    /**
     * @param LotItemPlaceholderRenderer $lotItemPlaceholderRenderer
     * @return static
     * @internal
     */
    public function setLotItemPlaceholderRenderer(LotItemPlaceholderRenderer $lotItemPlaceholderRenderer): static
    {
        $this->lotItemPlaceholderRenderer = $lotItemPlaceholderRenderer;
        return $this;
    }
}
