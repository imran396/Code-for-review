<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-13, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\WebData;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;
use Sam\Installation\Config\Edit\Render\WebData\Option\ValidationErrorWebData;
use Sam\Installation\Config\Edit\Validate\Web\ValidatedData;

/**
 * Class ValidationErrorsBuilder
 * @package Sam\Installation\Config
 */
class ValidationErrorWebDataBuilder extends CustomizableClass
{
    use OptionHelperAwareTrait;
    use UrlHashBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ValidatedData[] $validatedData
     * @param string $configName
     * @return ValidationErrorWebData[]
     */
    public function build(array $validatedData, string $configName): array
    {
        $output = [];
        /** @var ValidatedData $validatedDatum */
        foreach ($validatedData ?: [] as $optionKey => $validatedDatum) {
            if (!empty($validatedDatum->getValidationResults())) {
                [$optionTitle, $urlHash] = $this->prepareValidationErrorForOption($optionKey, $configName);
                $output[] = new ValidationErrorWebData($optionTitle, $urlHash);
            }
        }
        return $output;
    }

    /**
     * @param string $optionKey
     * @param string $configName
     * @return array
     */
    protected function prepareValidationErrorForOption(string $optionKey, string $configName): array
    {
        $preparedUrlHash = $this->getUrlHashBuilder()->buildForOptionKey($optionKey, $configName);
        $urlHash = "#{$preparedUrlHash}";
        $optionTitle = $this->getOptionHelper()
            ->replaceGeneralDelimiter($optionKey, Constants\Installation::DELIMITER_RENDER_OPTION_KEY);
        if ($configName) {
            $optionTitle = $this->getOptionHelper()->prependConfigName($optionTitle, $configName);
        }
        return [$optionTitle, $urlHash];
    }
}
