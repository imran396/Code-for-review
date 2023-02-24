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

namespace Sam\Installation\Config\Edit\Meta;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;
use Sam\Installation\Config\Edit\Meta\Descriptor\MissingDescriptor;

/**
 * Class OptionHelper
 * @package Sam\Installation\Config
 */
class OptionHelper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Convert input array with flat keys (one dimension array)
     * to same multi dimension array.
     * @param array $data with flat keys
     * @return array
     */
    public function transformFlatKeyToMultiDimKeyOptions(array $data): array
    {
        $multiDimOptions = [];
        foreach ($data ?: [] as $optionKey => $value) {
            $multiDimOption = $this->transformFlatKeyToMultiDimKeyOption($optionKey, $value);
            $multiDimOptions = array_replace_recursive($multiDimOptions, $multiDimOption);
        }
        return $multiDimOptions;
    }

    /**
     * Convert string like 'test__some__3' to multi dimension array and setup custom value
     * (any type. You can setup array\object\string\int\bool\float as a value) for last key in returned array.
     * for example you will write:
     * $array = stringToMultiDimArray('test->some->3', 67);
     * $array will be ['test'=>['some'=>['3'=>67]]]
     *
     * @param string $path
     * @param mixed $value
     * @return array
     */
    public function transformFlatKeyToMultiDimKeyOption(string $path, mixed $value): array
    {
        $delimiter = Constants\Installation::DELIMITER_GENERAL_OPTION_KEY;
        $optionPathKeys = explode($delimiter, $path);
        $optionPathKeys = array_reverse($optionPathKeys);
        foreach ($optionPathKeys as $pathKey) {
            $value = [$pathKey => $value];
        }
        return $value;
    }

    /**
     * Converts a multidimensional array into a one-dimensional array using the
     * Constants\Installation::DELIMITER_GENERAL_OPTION_KEY as a separator for one-dimensional array keys.
     * It takes meta information about the value from the meta-file ($descriptorsMultiDim arg.)
     * and correctly processes the values ​​that contains arrays.
     *
     * @param array $in a multidimensional array
     * @param Descriptor|Descriptor[] $descriptorsMultiDim multidimensional array
     * @param array $out - result out one-dimensional array
     * @param string $prefix - internal service string.
     * @return array
     */
    public function transformMultiDimKeyToFlatKeyArray(
        array $in,
        Descriptor|array|null $descriptorsMultiDim = null,
        array $out = [],
        string $prefix = ''
    ): array {
        $delimiter = Constants\Installation::DELIMITER_GENERAL_OPTION_KEY;
        foreach ($in as $key => $value) {
            $dataType = $descriptor = null;
            if (is_array($descriptorsMultiDim)) {
                $descriptor = $descriptorsMultiDim[$key] ?? [];
                if ($descriptor instanceof Descriptor) {
                    $dataType = $descriptor->getType();
                }
            } elseif ($descriptorsMultiDim instanceof Descriptor) {
                $dataType = $descriptorsMultiDim->getType();
            }

            if (is_array($value)) {
                if (in_array($dataType, [Constants\Type::T_ARRAY, Constants\Installation::T_STRUCT_ARRAY], true)) {
                    $out["{$prefix}{$key}"] = $value;
                } else {
                    $workPrefix = $prefix . $key . $delimiter;
                    $out = array_merge(
                        $out,
                        $this->transformMultiDimKeyToFlatKeyArray($value, $descriptor, $out, $workPrefix)
                    );
                }
            } else {
                $out["{$prefix}{$key}"] = $value;
            }
        }
        return $out;
    }

    /**
     * Replace optionKey delimiter
     * @param string $optionKey
     * @param string $sourceDelimiter
     * @param string $targetDelimiter
     * @return string
     */
    public function replaceDelimiter(string $optionKey, string $sourceDelimiter, string $targetDelimiter): string
    {
        $resultOptionKey = $sourceDelimiter !== $targetDelimiter
            ? str_replace($sourceDelimiter, $targetDelimiter, $optionKey)
            : $optionKey;
        return $resultOptionKey;
    }

    /**
     * Replace general option path delimiter to custom path delimiter.
     * @param string $optionKey
     * @param string $targetDelimiter
     * @return string
     */
    public function replaceGeneralDelimiter(string $optionKey, string $targetDelimiter): string
    {
        $resultOptionKey = $this->replaceDelimiter(
            $optionKey,
            Constants\Installation::DELIMITER_GENERAL_OPTION_KEY,
            $targetDelimiter
        );
        return $resultOptionKey;
    }

    /**
     * Replace meta option path delimiter to custom path delimiter.
     * @param string $optionKey
     * @param string $targetDelimiter
     * @return string
     */
    public function replaceMetaDelimiter(string $optionKey, string $targetDelimiter): string
    {
        $resultOptionKey = $this->replaceDelimiter(
            $optionKey,
            Constants\Installation::DELIMITER_META_OPTION_KEY,
            $targetDelimiter
        );
        return $resultOptionKey;
    }

    public function replaceMetaToGeneralDelimiter(string $optionKey): string
    {
        return $this->replaceMetaDelimiter($optionKey, Constants\Installation::DELIMITER_GENERAL_OPTION_KEY);
    }

    /**
     * @param DescriptorCollection $descriptorCollection
     * @return array
     */
    public function toActualOptions(DescriptorCollection $descriptorCollection): array
    {
        $output = [];
        foreach ($descriptorCollection->toArray() as $optionKey => $descriptor) {
            $output[$optionKey] = $descriptor->getActualValue();
        }
        return $output;
    }

    /**
     * @param DescriptorCollection $descriptorCollection
     * @return array
     */
    public function toGlobalOptions(DescriptorCollection $descriptorCollection): array
    {
        $output = [];
        foreach ($descriptorCollection->toArray() as $optionKey => $descriptor) {
            if (!$descriptor instanceof MissingDescriptor) {
                $output[$optionKey] = $descriptor->getGlobalValue();
            }
        }
        return $output;
    }

    /**
     * @param DescriptorCollection $descriptorCollection
     * @return array
     */
    public function toLocalOptions(DescriptorCollection $descriptorCollection): array
    {
        $output = [];
        foreach ($descriptorCollection->toArray() as $optionKey => $descriptor) {
            if ($descriptor->hasLocalValue()) {
                $output[$optionKey] = $descriptor->getLocalValue();
            }
        }
        return $output;
    }

    public function prependConfigName(
        string $optionKey,
        string $configName,
        string $delimiter = Constants\Installation::DELIMITER_RENDER_OPTION_KEY
    ): string {
        return "{$configName}{$delimiter}{$optionKey}";
    }
}
