<?php
/**
 * SAM-11607: Custom favicon
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 14, 2023
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Favicon\ActionStorage\Session;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Storage\PhpSessionStorageCreateTrait;


class FaviconActionStorage extends CustomizableClass
{
    use PhpSessionStorageCreateTrait;

    private const UPLOAD_FAVICON_CACHE_KEY = 'upload_favicon_cache_key';
    private const REMOVE_FAVICON_ACTION = 'remove_favicon_action';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    public function hasUploadFaviconCacheKey(): bool
    {
        return $this->has(self::UPLOAD_FAVICON_CACHE_KEY);
    }

    public function getUploadFaviconCacheKey(): string
    {
        return $this->get(self::UPLOAD_FAVICON_CACHE_KEY);
    }

    public function setUploadFaviconCacheKey(string $cacheKey): void
    {
        $this->set(self::UPLOAD_FAVICON_CACHE_KEY, $cacheKey);
    }

    public function deleteUploadFaviconCacheKey(): void
    {
        $this->delete(self::UPLOAD_FAVICON_CACHE_KEY);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    public function hasRemoveFaviconAction(): bool
    {
        return $this->has(self::REMOVE_FAVICON_ACTION);
    }

    public function setRemoveFaviconAction(): void
    {
        $this->set(self::REMOVE_FAVICON_ACTION, true);
    }

    public function deleteRemoveFaviconAction(): void
    {
        $this->delete(self::REMOVE_FAVICON_ACTION);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    protected function has(string $key): bool
    {
        return $this->createPhpSessionStorage()->has($key);
    }

    protected function get(string $key): mixed
    {
        return $this->createPhpSessionStorage()->get($key);
    }

    protected function set(string $key, mixed $value): void
    {
        $this->createPhpSessionStorage()->set($key, $value);
    }

    protected function delete(string $key): void
    {
        $this->createPhpSessionStorage()->delete($key);
    }
}
