<?php
/**
 * SAM-9458: Path resolving for logo images of pages layout
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Location\Image\Path;

use Sam\Core\Path\PathResolver;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LocationLogoPathResolver
 * @package
 */
class LocationLogoPathResolver extends CustomizableClass
{
    use PathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @return string
     * #[Pure]
     */
    public function makePath(int $accountId): string
    {
        return sprintf('%s/%s', PathResolver::UPLOAD_SETTING, $accountId);
    }

    public function makeRootPath(int $accountId): string
    {
        return sprintf('%s%s', $this->path()->sysRoot(), $this->makePath($accountId));
    }

    public function makeFilePath(int $accountId, string $fileName): string
    {
        return sprintf('%s/%s', $this->makePath($accountId), $fileName);
    }

    public function makeFileRootPath(int $accountId, string $fileName): string
    {
        return sprintf('%s/%s', $this->makeRootPath($accountId), $fileName);
    }

}
