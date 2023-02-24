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

namespace Sam\Image\Favicon\Path;

use Sam\Core\Path\PathResolver;
use Sam\Core\Service\CustomizableClass;

class FaviconImagePathResolver extends CustomizableClass
{
    protected const BASE_FAVICON = 'favicon.png';
    protected const MEDIUM_FAVICON = '32x32ico.png';
    protected const SMALL_FAVICON = '16x16ico.png';
    protected const APPLE_TOUCH_FAVICON = 'apple-touch-icon.png';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function makeBaseFaviconFileRootPath(int $accountId): string
    {
        return sprintf('%s/%s', $this->makeBaseFaviconRootPath($accountId), self::BASE_FAVICON);
    }

    public function makeBaseFaviconRootPath(int $accountId): string
    {
        return sprintf('%s/%s', path()->sysRoot() . PathResolver::UPLOAD_SETTING, $accountId);
    }

    public function makeBaseFaviconFilePath(int $accountId): string
    {
        return sprintf('%s/%s', $this->makeBaseFaviconPath($accountId), self::BASE_FAVICON);
    }

    public function makeBaseFaviconPath(int $accountId): string
    {
        return sprintf('%s/%s', PathResolver::UPLOAD_SETTING, $accountId);
    }

    public function makeMediumFaviconFilePath(int $accountId): string
    {
        return sprintf('%s/%s', $this->makeGeneratedFaviconPath($accountId), self::MEDIUM_FAVICON);
    }

    public function makeMediumFaviconFileRootPath(int $accountId): string
    {
        return sprintf('%s/%s', path()->sysRoot() . $this->makeGeneratedFaviconPath($accountId), self::MEDIUM_FAVICON);
    }

    public function makeSmallFaviconFilePath(int $accountId): string
    {
        return sprintf('%s/%s', $this->makeGeneratedFaviconPath($accountId), self::SMALL_FAVICON);
    }

    public function makeAppleFaviconFilePath(int $accountId): string
    {
        return sprintf('%s/%s', $this->makeGeneratedFaviconPath($accountId), self::APPLE_TOUCH_FAVICON);
    }

    public function makeGeneratedFaviconFileRootPath(int $accountId): string
    {
        return sprintf('%s/%s', path()->sysRoot() . PathResolver::DOCROOT . PathResolver::DEFAULT_ACCOUNT_IMAGES, $accountId);
    }

    public function makeGeneratedFaviconPath(int $accountId): string
    {
        return sprintf('%s/%s', PathResolver::DOCROOT . PathResolver::DEFAULT_ACCOUNT_IMAGES, $accountId);
    }

    public function makeRenderMediumFaviconPath(int $accountId): string
    {
        return $this->makeRenderFaviconPath($accountId, self::MEDIUM_FAVICON);
    }

    public function makeRenderSmallFaviconPath(int $accountId): string
    {
        return $this->makeRenderFaviconPath($accountId, self::SMALL_FAVICON);
    }

    public function makeRenderAppleFaviconPath(int $accountId): string
    {
        return $this->makeRenderFaviconPath($accountId, self::APPLE_TOUCH_FAVICON);
    }

    public function makeRenderFaviconPath(int $accountId, string $fileName): string
    {
        return sprintf('%s/%s/%s', PathResolver::DEFAULT_ACCOUNT_IMAGES, $accountId, $fileName);
    }
}
