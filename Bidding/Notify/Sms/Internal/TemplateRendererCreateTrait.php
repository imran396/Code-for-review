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

namespace Sam\Bidding\Notify\Sms\Internal;

/**
 * Trait TemplateRendererCreateTrait
 * @package Sam\Bidding\Notify\Sms\Internal
 */
trait TemplateRendererCreateTrait
{
    /**
     * @var TemplateRenderer|null
     */
    protected ?TemplateRenderer $templateRenderer = null;

    /**
     * @return TemplateRenderer
     */
    protected function createTemplateRenderer(): TemplateRenderer
    {
        return $this->templateRenderer ?: TemplateRenderer::new();
    }

    /**
     * @param TemplateRenderer $templateRenderer
     * @return static
     * @internal
     */
    public function setTemplateRenderer(TemplateRenderer $templateRenderer): static
    {
        $this->templateRenderer = $templateRenderer;
        return $this;
    }
}
