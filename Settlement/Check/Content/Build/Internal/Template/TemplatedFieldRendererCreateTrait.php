<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Template;

/**
 * Trait TemplatedFieldBuilderCreateTrait
 * @package Sam\Settlement\Check
 */
trait TemplatedFieldRendererCreateTrait
{
    protected ?TemplatedFieldRenderer $templatedFieldRenderer = null;

    /**
     * @return TemplatedFieldRenderer
     */
    protected function createTemplatedFieldRenderer(): TemplatedFieldRenderer
    {
        return $this->templatedFieldRenderer ?: TemplatedFieldRenderer::new();
    }

    /**
     * @param TemplatedFieldRenderer $templatedFieldRenderer
     * @return $this
     * @internal
     */
    public function setTemplatedFieldRenderer(TemplatedFieldRenderer $templatedFieldRenderer): static
    {
        $this->templatedFieldRenderer = $templatedFieldRenderer;
        return $this;
    }
}
