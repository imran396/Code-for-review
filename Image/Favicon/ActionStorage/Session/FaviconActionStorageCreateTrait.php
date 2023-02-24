<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Favicon\ActionStorage\Session;


trait FaviconActionStorageCreateTrait
{
    /**
     * @var FaviconActionStorage|null
     */
    protected ?FaviconActionStorage $faviconActionStorage = null;

    /**
     * @return FaviconActionStorage
     */
    protected function createFaviconActionStorage(): FaviconActionStorage
    {
        return $this->faviconActionStorage ?: FaviconActionStorage::new();
    }

    /**
     * @param FaviconActionStorage $faviconActionStorage
     * @return $this
     * @internal
     */
    public function setFaviconActionStorage(FaviconActionStorage $faviconActionStorage): static
    {
        $this->faviconActionStorage = $faviconActionStorage;
        return $this;
    }
}
