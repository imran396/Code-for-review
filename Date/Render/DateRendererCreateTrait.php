<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Date\Render;

/**
 * Trait DateRendererCreateTrait
 * @package Sam\Date\Render
 */
trait DateRendererCreateTrait
{
    /**
     * @var DateRenderer|null
     */
    protected ?DateRenderer $dateRenderer = null;

    /**
     * @return DateRenderer
     */
    protected function createDateRenderer(): DateRenderer
    {
        return $this->dateRenderer ?: DateRenderer::new();
    }

    /**
     * @param DateRenderer $dateRenderer
     * @return $this
     * @internal
     */
    public function setDateRenderer(DateRenderer $dateRenderer): static
    {
        $this->dateRenderer = $dateRenderer;
        return $this;
    }
}
