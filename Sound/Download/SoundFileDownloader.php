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

namespace Sam\Sound\Download;

use finfo;
use QApplication;
use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Net\HttpCacheManager;
use Sam\Sound\LiveSale\Path\LiveSaleSoundFilePathResolver;
use Sam\Sound\RtbMessage\Path\RtbMessageSoundFilePathResolver;

/**
 * Class SoundFileDownloader
 * @package Sam\Sound\Download
 */
class SoundFileDownloader extends CustomizableClass
{
    use AccountExistenceCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $class
     * @param int $resourceId sound or rtb message id
     * @param int $accountId
     * @return bool
     */
    public function download(string $class, int $resourceId, int $accountId): bool
    {
        if (!$this->getAccountExistenceChecker()->existById($accountId, true)) {
            return false;
        }

        $soundPath = $this->detectSoundFileRootPath($class, $resourceId, $accountId);
        if (
            !$soundPath
            || !is_file($soundPath)
        ) {
            return false;
        }

        $this->sendSoundFile($soundPath);
        return true;
    }

    protected function detectSoundFileRootPath(string $class, int $resourceId, int $accountId): string
    {
        $soundPath = '';
        if ($class === "auction") {
            $soundPath = LiveSaleSoundFilePathResolver::new()->detectFileRootPath($resourceId, $accountId);
        } elseif ($class === "rtbmessage") {
            $soundPath = RtbMessageSoundFilePathResolver::new()->detectFileRootPath($resourceId, $accountId);
        }
        return $soundPath;
    }

    /**
     * @param string $file
     */
    protected function sendSoundFile(string $file): void
    {
        HttpCacheManager::new()->sendHeadersAndExitIfNotModified(@filemtime($file));
        QApplication::$CacheControl = "public, max-age=1800";

        header("Content-Type: " . $this->getFileMimeType($file));
        header("Content-Length: " . filesize($file));
        header("Content-Transfer-Encoding: binary");
        echo file_get_contents($file);
    }

    /**
     * @param string $fileRootPath
     * @return string
     */
    protected function getFileMimeType(string $fileRootPath): string
    {
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        $contentType = $fileInfo->file($fileRootPath);
        $contentType = $contentType ?: "application/octet-stream";
        return $contentType;
    }
}
