<?php
/**
 * SAM-4274: Remote image fetching improvements using ETag and expires and cache-control header to determine changes rather than last modified
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Cache;

/**
 * Trait ImageCacheManagerCreateTrait
 * @package Sam\Image\Cache
 */
trait ImageCacheManagerCreateTrait
{
    /**
     * @var ImageCacheManager|null
     */
    protected ?ImageCacheManager $imageCacheManager = null;

    /**
     * @return ImageCacheManager
     */
    protected function createImageCacheManager(): ImageCacheManager
    {
        return $this->imageCacheManager ?: ImageCacheManager::new();
    }

    /**
     * @param ImageCacheManager $imageCacheManager
     * @return $this
     * @internal
     */
    public function setImageCacheManager(ImageCacheManager $imageCacheManager): static
    {
        $this->imageCacheManager = $imageCacheManager;
        return $this;
    }
}
