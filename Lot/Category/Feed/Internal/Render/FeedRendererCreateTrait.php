<?php
/**
 * SAM-9677: Refactor \Feed\CategoryGet
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Feed\Internal\Render;

/**
 * Trait FeedRendererCreateTrait
 * @package Sam\Lot\Category\Feed\Internal
 */
trait FeedRendererCreateTrait
{
    /**
     * @var FeedRenderer|null
     */
    protected ?FeedRenderer $feedRenderer = null;

    /**
     * @return FeedRenderer
     */
    protected function createFeedRenderer(): FeedRenderer
    {
        return $this->feedRenderer ?: FeedRenderer::new();
    }

    /**
     * @param FeedRenderer $feedRenderer
     * @return static
     * @internal
     */
    public function setFeedRenderer(FeedRenderer $feedRenderer): static
    {
        $this->feedRenderer = $feedRenderer;
        return $this;
    }
}
