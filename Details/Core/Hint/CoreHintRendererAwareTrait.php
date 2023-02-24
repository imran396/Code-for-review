<?php
/**
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core\Hint;

/**
 * Trait CoreHintRendererCreateTrait
 * @package Sam\Details\Core\Hint
 */
trait CoreHintRendererAwareTrait
{
    /**
     * @var CoreHintRenderer|null
     */
    protected ?CoreHintRenderer $coreHintRenderer = null;

    protected function getCoreHintRenderer(): CoreHintRenderer
    {
        if ($this->coreHintRenderer === null) {
            $this->coreHintRenderer = CoreHintRenderer::new();
        }
        return $this->coreHintRenderer;
    }

    /**
     * @internal
     */
    public function setCoreHintRenderer(CoreHintRenderer $coreHintRenderer): static
    {
        $this->coreHintRenderer = $coreHintRenderer;
        return $this;
    }
}
