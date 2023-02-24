<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           6/3/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\User\Connected\Render;

/**
 * Trait RendererCreateTrait
 * @package Sam\Rtb\User\Connected\Render
 */
trait RendererCreateTrait
{
    /**
     * @var Renderer|null
     */
    protected ?Renderer $renderer = null;

    /**
     * @return Renderer
     */
    protected function createRenderer(): Renderer
    {
        return $this->renderer ?: Renderer::new();
    }

    /**
     * @param Renderer $renderer
     * @return $this
     * @internal
     */
    public function setRenderer(Renderer $renderer): static
    {
        $this->renderer = $renderer;
        return $this;
    }
}
