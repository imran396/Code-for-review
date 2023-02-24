<?php
/**
 * SAM-4886:  Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Июль 04, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\File;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Installation\Config\Edit\Load\FileContentLoaderCreateTrait;

/**
 * Class FileValidator
 * Validate all config and meta files.
 * @package Sam\Installation\Config
 */
class ConfigFileValidator extends CustomizableClass
{
    use FileContentLoaderCreateTrait;
    use ResultStatusCollectorAwareTrait;

    // ResultStatusCollector statuses constants
    public const ERR_EMPTY_CONFIG_NAME = 1;
    public const ERR_GLOBAL_EMPTY_OPTIONS = 2;
    public const ERR_GLOBAL_UNKNOWN_CONTENT = 3;

    public const WARN_UNKNOWN_CONTENT = 1;

    //Validation area status message templates
    protected const VAM_GLOBAL = 'Global options file <b>"%s.php"</b> %s';
    protected const VAM_LOCAL = 'Local options file <b>"%s.local.php"</b> %s';
    protected const VAM_META = 'Meta file <b>"%s.meta.php"</b> %s';

    /** @var string|null */
    private ?string $configName = '';
    /** @var string[] */
    private array $availableConfigNames = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate meta-global-local config files for existing and right data content.
     * @param string $configName
     * @return bool
     */
    public function validate(string $configName): bool
    {
        $this->configName = Cast::toString($configName, $this->getAvailableConfigNames());
        $this->initResultStatusCollector();
        $collector = $this->getResultStatusCollector();

        if (empty($this->configName)) {
            $collector->addError(self::ERR_EMPTY_CONFIG_NAME);
        } else {
            $this->validateGlobalOptionsFile();
            $this->validateLocalOptionsFile();
            $this->validateMetaFile();
        }

        return !$collector->hasError();
    }

    /**
     * Get config validation warning messages.
     * @return string[]
     */
    public function warningMessages(): array
    {
        return $this->getResultStatusCollector()->getWarningMessages();
    }

    /**
     * Get config validation error messages.
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * Get config validation warning codes.
     * @return int[]
     */
    public function warningCodes(): array
    {
        return $this->getResultStatusCollector()->getWarningCodes();
    }

    /**
     * Get config validation error codes.
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @param string[] $availableConfigNames
     * @return $this
     */
    public function setAvailableConfigNames(array $availableConfigNames): static
    {
        $this->availableConfigNames = $availableConfigNames;
        return $this;
    }

    /**
     * @return string[]
     */
    protected function getAvailableConfigNames(): array
    {
        if (!$this->availableConfigNames) {
            $this->availableConfigNames = Constants\Installation::AVAILABLE_CONFIG_NAMES;
        }
        return $this->availableConfigNames;
    }

    /**
     * Validate global config options and add errors to ResultStatusCollector
     */
    protected function validateGlobalOptionsFile(): void
    {
        $globalOptions = $this->createFileContentLoader()->loadGlobalOptionsMultiDim($this->configName);
        if (!is_array($globalOptions)) {
            $this->getResultStatusCollector()->addError(
                self::ERR_GLOBAL_UNKNOWN_CONTENT,
                sprintf(self::VAM_GLOBAL, $this->configName, 'has unknown data!')
            );
        }
        if (is_array($globalOptions) && empty($globalOptions)) {
            $this->getResultStatusCollector()->addError(
                self::ERR_GLOBAL_EMPTY_OPTIONS,
                sprintf(self::VAM_GLOBAL, $this->configName, 'is empty!')
            );
        }
    }

    /**
     * Validate local options
     */
    protected function validateLocalOptionsFile(): void
    {
        $localOptions = $this->createFileContentLoader()->loadLocalOptionsMultiDim($this->configName);
        if (!is_array($localOptions)) {
            $this->processValidationStatusesForLocalAndMetaAreas();
        }
    }

    /**
     * Validate meta options
     */
    protected function validateMetaFile(): void
    {
        $metaOptions = $this->createFileContentLoader()->loadMetaOptions($this->configName);
        if (!is_array($metaOptions)) {
            $this->processValidationStatusesForLocalAndMetaAreas(false);
        }
    }

    /**
     * Add Validation status code and message to ResultStatusCollector for local and meta validation areas.
     * @param bool $messageForLocalConfig
     */
    protected function processValidationStatusesForLocalAndMetaAreas(bool $messageForLocalConfig = true): void
    {
        $message = sprintf(
            $messageForLocalConfig ? self::VAM_LOCAL : self::VAM_META,
            $this->configName,
            'has unknown data!'
        );
        $this->getResultStatusCollector()->addWarning(self::WARN_UNKNOWN_CONTENT, $message);
    }

    /**
     * Initialize ResultStatusCollector
     */
    protected function initResultStatusCollector(): void
    {
        // ResultStatusCollector default error messages for error codes
        $errorMessages = [
            self::ERR_EMPTY_CONFIG_NAME => 'Config name is empty!',
            self::ERR_GLOBAL_EMPTY_OPTIONS => 'Empty array returned for main configuration!',
            self::ERR_GLOBAL_UNKNOWN_CONTENT => 'Main configuration file has unknown content!',
        ];
        // ResultStatusCollector default warning messages for warning codes
        $warningMessages = [
            self::WARN_UNKNOWN_CONTENT => 'Configuration files have unknown content!',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, [], $warningMessages);
    }
}
