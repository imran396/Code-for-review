<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\Configuration\CoreMetaConfiguration;
use Sam\Installation\Config\Edit\Meta\Configuration\CssLinksMetaConfiguration;
use Sam\Installation\Config\Edit\Meta\Configuration\JsScriptsMetaConfiguration;

/**
 * Class FileContentLoader
 * @package Sam\Installation\Config
 */
class FileContentLoader extends CustomizableClass
{
    /**
     * path for local config file.
     * @var string|null
     */
    protected ?string $localConfigFileRootPath = null;
    /**
     * path for global config file.
     * @var string|null
     */
    protected ?string $globalConfigFileRootPath = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get path for local config files.
     * @param string $configName
     * @return string
     */
    public function getLocalConfigFileRootPath(string $configName): string
    {
        if ($this->localConfigFileRootPath === null) {
            $this->localConfigFileRootPath = cfg()->getLocalConfigFileRootPath($configName);
        }
        return $this->localConfigFileRootPath;
    }

    /**
     * Set path for local config files.
     * @param string $localConfigFileRootPath
     * @return static
     */
    public function setLocalConfigFileRootPath(string $localConfigFileRootPath): static
    {
        $this->localConfigFileRootPath = trim($localConfigFileRootPath);
        return $this;
    }

    /**
     * Get path for global config files.
     * @param string $configName
     * @return string
     */
    public function getGlobalConfigFileRootPath(string $configName): string
    {
        if ($this->globalConfigFileRootPath === null) {
            $this->globalConfigFileRootPath = cfg()->getGlobalConfigFileRootPath($configName);
        }
        return $this->globalConfigFileRootPath;
    }

    /**
     * Set path for global config file
     * @param string $globalConfigFileRootPath
     * @return static
     */
    public function setGlobalConfigFileRootPath(string $globalConfigFileRootPath): static
    {
        $this->globalConfigFileRootPath = trim($globalConfigFileRootPath);
        return $this;
    }

    /**
     * global config file content
     * @param string $configName
     * @return array|int|null
     * array[] - when configuration file exists and returns an array.
     *  null - when no config name is empty or configuration file path is empty. (exceptional situation. used at unit tests)
     *  int - when global configuration files do not return an array. (exceptional situation. used at unit tests)
     * @see \Sam\Installation\Config\Edit\Load\FileContentLoader::requireFileToVariable
     * @see \Sam\Installation\Config\Edit\Validate\File\ConfigFileValidatorTest::fetchConfigFileContent
     */
    public function loadGlobalOptionsMultiDim(string $configName): array|int|null
    {
        $fileRootPath = $this->getGlobalConfigFileRootPath($configName);
        $output = $this->requireFileToVariable($fileRootPath);
        return $output;
    }

    /**
     * Local config file content
     * @param string $configName
     * @return array|int|null
     * array[] - when configuration file exists and returns an array.
     *  null - when no config name is empty or configuration file path is empty. (exceptional situation. used at unit tests)
     *  int - when local configuration files do not return an array. (exceptional situation. used at unit tests)
     * @see \Sam\Installation\Config\Edit\Load\FileContentLoader::requireFileToVariable
     * @see \Sam\Installation\Config\Edit\Validate\File\ConfigFileValidatorTest::fetchConfigFileContent
     */
    public function loadLocalOptionsMultiDim(string $configName): array|int|null
    {
        $fileRootPath = $this->getLocalConfigFileRootPath($configName);
        $output = $this->requireFileToVariable($fileRootPath);
        return $output;
    }

    /**
     * Meta config file content
     * @param string $configName
     * @return array[]|int|null
     *  array[] - when configuration file exists and returns an array.
     *  null - when no config name is empty or configuration file path is empty. (exceptional situation. used at unit tests)
     *  int - when meta configuration files do not return an array. (exceptional situation. used at unit tests)
     * @see \Sam\Installation\Config\Edit\Load\FileContentLoader::requireFileToVariable
     * @see \Sam\Installation\Config\Edit\Validate\File\ConfigFileValidatorTest::fetchConfigFileContent
     */
    public function loadMetaOptions(string $configName): array|int|null
    {
        if ($configName === 'core') {
            $metaOptions = CoreMetaConfiguration::new()->get();
            return $this->normalizeCoreConfigMetaFlatOptionKey($metaOptions, $configName);
        }
        if ($configName === 'cssLinks') {
            return CssLinksMetaConfiguration::new()->get();
        }
        if ($configName === 'jsScripts') {
            return JsScriptsMetaConfiguration::new()->get();
        }
        return [];
    }

    /**
     * @param string $fileRootPath
     * @return array|int|null
     */
    public function requireFileToVariable(string $fileRootPath): array|int|null
    {
        if (!$fileRootPath) {
            // Not expected situation return null
            return null;
        }

        if (!file_exists($fileRootPath)) {
            // Absent file is expected situation, that means empty data
            return [];
        }

        $opcacheEnabled = ini_get('opcache.enable');
        $opcacheCliEnabled = ini_get('opcache.enable_cli');
        if ((int)$opcacheEnabled === 1 || (int)$opcacheCliEnabled === 1) {
            opcache_invalidate($fileRootPath);
        }
        $output = require $fileRootPath;
        if (!is_array($output)) {
            // Not expected situation, config content type should be array
            log_error('Config file "' . $fileRootPath . '" returns not an array!');
            return -1;
        }

        return $output;
    }

    /**
     * Remove 'core' configuration name(with delimiter) prefix from metaOptions array keys.
     * @param array $metaOptions
     * @param string $configName
     * @return array
     */
    protected function normalizeCoreConfigMetaFlatOptionKey(array $metaOptions, string $configName): array
    {
        $delimiter = Constants\Installation::DELIMITER_META_OPTION_KEY;
        $normalizedMetaOptions = [];
        foreach ($metaOptions ?: [] as $optionKey => $metaAttributes) {
            $normalizedOptionKey = preg_replace("%^{$configName}{$delimiter}%", '', $optionKey);
            $normalizedMetaOptions[$normalizedOptionKey] = $metaAttributes;
        }
        return $normalizedMetaOptions;
    }
}
