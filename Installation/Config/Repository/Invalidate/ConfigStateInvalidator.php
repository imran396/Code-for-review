<?php
/**
 * SAM-7758: Dynamic reconfiguration of rtbd state
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Repository\Invalidate;

use Sam\Core\Path\PathResolver;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class ConfigStateInvalidator
 * @package Sam\Installation\Config\Repository\Invalidate
 */
class ConfigStateInvalidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LocalFileManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function invalidate(): void
    {
        $loadedConfigs = $this->cfg()->detectLoadedConfigs();
        $lastModificationTime = $this->detectLastConfigFileModificationTime($loadedConfigs);
        $initializedAt = $this->cfg()->getInitializedAt();

        if ($initializedAt < $lastModificationTime) {
            $this->cfg()->reload();
            log_debug(
                'Installation config reloaded. ' . composeLogData(
                    compact('initializedAt', 'lastModificationTime')
                )
            );
            return;
        }

        log_debug(
            'Config files not modified. ' . composeLogData(
                compact('initializedAt', 'lastModificationTime')
            )
        );
    }

    protected function detectLastConfigFileModificationTime(array $configNames): int
    {
        $maxConfigModificationTime = 0;

        foreach ($configNames as $configName) {
            $globalFile = $this->makeGlobalConfigFileRelativePath($configName, false);
            $localFile = $this->makeLocalConfigFileRelativePath($configName, false);
            $customGlobalFile = $this->makeGlobalConfigFileRelativePath($configName, true);
            $customLocalFile = $this->makeLocalConfigFileRelativePath($configName, true);

            $localFileManager = $this->createLocalFileManager();
            $configModificationTime = max(
                $localFileManager->exist($globalFile) ? $localFileManager->getMTime($globalFile) : 0,
                $localFileManager->exist($localFile) ? $localFileManager->getMTime($localFile) : 0,
                $localFileManager->exist($customGlobalFile) ? $localFileManager->getMTime($customGlobalFile) : 0,
                $localFileManager->exist($customLocalFile) ? $localFileManager->getMTime($customLocalFile) : 0,
            );
            if ($maxConfigModificationTime < $configModificationTime) {
                $maxConfigModificationTime = $configModificationTime;
            }
        }

        return $maxConfigModificationTime;
    }

    protected function makeGlobalConfigFileRelativePath(string $configName, bool $isCustom): string
    {
        $path = PathResolver::CONFIGURATION . '/' . $configName . '.php';
        if ($isCustom) {
            $path = PathResolver::CUSTOM_DIR . '/' . $path;
        }
        return $path;
    }

    protected function makeLocalConfigFileRelativePath(string $configName, bool $isCustom): string
    {
        $path = PathResolver::CONFIGURATION . '/' . $configName . '.local.php';
        if ($isCustom) {
            $path = PathResolver::CUSTOM_DIR . '/' . $path;
        }
        return $path;
    }
}
