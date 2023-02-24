<?php
/**
 * SAM-6743: Add ability to remove options via web form interface for 'Local config values, that not exists in global configuration ' section
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-20, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Delete\Internal\Validate;

use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;

/**
 * Class ConfigOptionValidator
 * @package Sam\Installation\Config\Edit\Delete\Internal
 */
class ConfigOptionValidator extends CustomizableClass
{
    private const ERROR_NOT_EXIST_CONFIG_KEY = 1;
    private const ERROR_EMPTY_CONFIG_KEY = 2;

    use OptionHelperAwareTrait;
    use ResultStatusCollectorAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function initInstance(): static
    {
        $this->initResultStatusCollector();
        return $this;
    }

    /**
     * Validate config key. Is it exists in local options and not empty.
     * @param string $optionKey
     * @param array $actualLocalOptions local config options with flat keys.
     * @return bool
     */
    public function validateConfigKey(string $optionKey, array $actualLocalOptions): bool
    {
        $success = true;
        $collector = $this->getResultStatusCollector();
        if (empty($optionKey)) {
            $collector->addError(self::ERROR_EMPTY_CONFIG_KEY);
            $success = false;
        } else {
            $isFound = array_key_exists($optionKey, $actualLocalOptions);
            if (!$isFound) {
                $optionKeyFormatted = $this->getOptionHelper()
                    ->replaceGeneralDelimiter($optionKey, Constants\Installation::DELIMITER_RENDER_OPTION_KEY);
                $collector->addErrorWithInjectedInMessageArguments(self::ERROR_NOT_EXIST_CONFIG_KEY, [$optionKeyFormatted]);
                $success = false;
            }
        }
        return $success;
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    protected function initResultStatusCollector(): void
    {
        // ResultStatusCollector default error messages for error codes
        $errorMessages = [
            self::ERROR_EMPTY_CONFIG_KEY => 'Configuration key is empty! Nothing for delete!',
            self::ERROR_NOT_EXIST_CONFIG_KEY => 'Configuration key <b>"%s"</b> not exists in local configuration! Nothing for delete!',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }
}
