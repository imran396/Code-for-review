<?php
/**
 * SAM-9561: Refactor support logger
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Support\Internal\Content;

/**
 * Trait ContentBuilderCreateTrait
 * @package Sam\Log\Support\Internal\Content
 */
trait ContentBuilderCreateTrait
{
    protected ?ContentBuilder $contentBuilder = null;

    /**
     * @return ContentBuilder
     */
    protected function createContentBuilder(): ContentBuilder
    {
        return $this->contentBuilder ?: ContentBuilder::new();
    }

    /**
     * @param ContentBuilder $contentBuilder
     * @return $this
     * @internal
     */
    public function setContentBuilder(ContentBuilder $contentBuilder): static
    {
        $this->contentBuilder = $contentBuilder;
        return $this;
    }
}
