<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           05-09, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Web\Collect;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;

/**
 * Class PostNormalizer
 * @package
 */
class PostCollector extends CustomizableClass
{
    /** @var array */
    protected const GENERAL_TYPES = [
        Constants\Type::T_STRING,
        Constants\Type::T_INTEGER,
        Constants\Type::T_FLOAT
    ];

    /** @var array */
    protected const ARRAY_TYPES = [
        Constants\Type::T_ARRAY,
        Constants\Installation::T_STRUCT_ARRAY
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Creates an array containing all config options available for web-editing,
     * while for those config options that are not in the input data array, an empty value is set,
     * which depends on the type of data contained in the option.
     *
     * @param array $postData
     * @param DescriptorCollection $descriptorCollection
     * @return array
     */
    public function collect(array $postData, DescriptorCollection $descriptorCollection): array
    {
        $normalizedPostData = $this->normalizePostDataKeys($postData, $descriptorCollection->getConfigName());
        $output = [];
        foreach ($descriptorCollection->getEditableDescriptors(true) as $optionKey => $descriptor) {
            if (array_key_exists($optionKey, $normalizedPostData)) {
                $output[$optionKey] = $normalizedPostData[$optionKey];
            } else {
                $output[$optionKey] = $this->buildExpectedEmptyValue($descriptor);
            }
        }
        return $output;
    }

    /**
     * @param Descriptor $descriptor
     * @return array|string|null
     */
    protected function buildExpectedEmptyValue(Descriptor $descriptor): array|string|null
    {
        $type = $descriptor->getType();
        if (in_array($type, self::GENERAL_TYPES, true)) {
            return '';
        }
        if (in_array($type, self::ARRAY_TYPES, true)) {
            return [];
        }
        return null;
    }

    /**
     * Remove configuration name(with delimiter) prefix from POST data keys.
     * @param array $postData
     * @param string $configName
     * @return array
     */
    protected function normalizePostDataKeys(array $postData, string $configName): array
    {
        $delimiter = Constants\Installation::DELIMITER_GENERAL_OPTION_KEY;
        $normalizedPostData = [];
        foreach ($postData as $optionKey => $value) {
            $normalizedOptionKey = preg_replace("%^{$configName}{$delimiter}%", '', $optionKey);
            $normalizedPostData[$normalizedOptionKey] = $value;
        }
        return $normalizedPostData;
    }
}
