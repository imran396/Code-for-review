<?php
/**
 * SAM-7764: Refactor \Auction_Access class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Access\Auction\Internal;

/**
 * Trait ResourceTypeColumnNameProviderCreateTrait
 * @package Sam\Application\Access\Auction\Internal
 * @internal
 */
trait ResourceTypeColumnNameProviderCreateTrait
{
    protected ?ResourceTypeColumnNameProvider $resourceTypeColumnNameProvider = null;

    /**
     * @return ResourceTypeColumnNameProvider
     */
    protected function createResourceTypeColumnNameProvider(): ResourceTypeColumnNameProvider
    {
        return $this->resourceTypeColumnNameProvider ?: ResourceTypeColumnNameProvider::new();
    }

    /**
     * @param ResourceTypeColumnNameProvider $resourceTypeColumnNameProvider
     * @return static
     * @internal
     */
    public function setResourceTypeColumnNameProvider(ResourceTypeColumnNameProvider $resourceTypeColumnNameProvider): static
    {
        $this->resourceTypeColumnNameProvider = $resourceTypeColumnNameProvider;
        return $this;
    }
}
