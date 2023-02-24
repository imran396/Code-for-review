<?php
/**
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 * SAM-5400: Rtb state update refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Clerk\Render\Factory;

/**
 * Trait ClerkCatalogRendererFactoryCreateTrait
 * @package Sam\Rtb\Catalog
 */
trait ClerkCatalogRendererFactoryCreateTrait
{
    protected ?ClerkCatalogRendererFactory $clerkCatalogRendererFactory = null;

    /**
     * @return ClerkCatalogRendererFactory
     */
    protected function createClerkCatalogRendererFactory(): ClerkCatalogRendererFactory
    {
        return $this->clerkCatalogRendererFactory ?: ClerkCatalogRendererFactory::new();
    }

    /**
     * @param ClerkCatalogRendererFactory $clerkCatalogRendererFactory
     * @return static
     * @internal
     */
    public function setClerkCatalogRendererFactory(ClerkCatalogRendererFactory $clerkCatalogRendererFactory): static
    {
        $this->clerkCatalogRendererFactory = $clerkCatalogRendererFactory;
        return $this;
    }
}
