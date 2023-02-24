<?php
/**
 * SAM-9373: Refactor play sound to avoid client side caching of stale files
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sound\LiveSale\Path;

use Sam\Core\Path\PathResolver;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Sound\LiveSale\Path\Detect\LiveSaleSoundFilePathDetectorCreateTrait;

/**
 * Class SoundPathResolver
 * @package Sam\Sound\Path
 */
class LiveSaleSoundFilePathResolver extends CustomizableClass
{
    use LiveSaleSoundFilePathDetectorCreateTrait;
    use LocalFileManagerCreateTrait;
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
     * Make relative path without file name that starts with slash
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

    /**
     * Make relative path to file that start with slash
     * @param int $accountId
     * @param string $fileName
     * @return string
     */
    public function makeFilePath(int $accountId, string $fileName): string
    {
        return sprintf('%s/%s', $this->makePath($accountId), $fileName);
    }

    public function makeFileRootPath(int $accountId, string $fileName): string
    {
        return sprintf('%s/%s', $this->makeRootPath($accountId), $fileName);
    }

    /**
     * @param int $soundId
     * @param int $accountId
     * @return string
     */
    public function detectFileRootPath(int $soundId, int $accountId): string
    {
        $fileRootPath = $this->createLiveSaleSoundFilePathDetector()->detect($soundId, $accountId);
        return $fileRootPath;
    }

    /**
     * Check sound file existence.
     * @param int $accountId
     * @param string $fileName
     * @return bool
     */
    public function exist(int $accountId, string $fileName): bool
    {
        $filePath = $this->makeFilePath($accountId, $fileName);
        return $this->createLocalFileManager()->exist($filePath);
    }

    /**
     * Check default static sound file existence.
     * @param string $fileName
     * @return bool
     */
    public function existDefault(string $fileName): bool
    {
        $filePath = sprintf("%s/%s", PathResolver::SOUND, $fileName);
        return $this->createLocalFileManager()->exist($filePath);
    }

    /**
     * Produce file root path for default static sound file.
     * @param string $fileName
     * @return string
     */
    public function makeDefaultFileRootPath(string $fileName): string
    {
        $fileRootPath = sprintf("%s/%s", $this->path()->sound(), $fileName);
        return $fileRootPath;
    }
}
