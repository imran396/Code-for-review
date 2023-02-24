<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * Compares incoming data with data from the global config
 * and returns only the data that is different.
 * Incoming data is an array with flat option keys and web rendered values for them.
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           04-11, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Web\Filter;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;

/**
 * Class DataFilter
 * @package Sam\Installation\Config\Edit\Validate\Web\Filter
 */
class DataFilter extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Filter out values that are equal to default value from global config
     * @param array $data
     * @param DescriptorCollection $descriptorCollection
     * @return array
     */
    public function filter(array $data, DescriptorCollection $descriptorCollection): array
    {
        $output = [];
        $webNormalizedGlobalConfig = $this->buildWebNormalizedGlobalConfig($descriptorCollection);
        $webNormalizedLocalConfig = $this->buildWebNormalizedLocalConfig($descriptorCollection);
        foreach ($webNormalizedGlobalConfig as $optionKey => $optionValue) {
            if (array_key_exists($optionKey, $data)) {
                if (array_key_exists($optionKey, $webNormalizedLocalConfig)) {
                    $output[$optionKey] = $data[$optionKey];
                } elseif ($data[$optionKey] !== $optionValue) {
                    $output[$optionKey] = $data[$optionKey];
                }
            }
        }
        return $output;
    }

    /**
     * @param DescriptorCollection $descriptorCollection
     * @return array
     */
    protected function buildWebNormalizedGlobalConfig(DescriptorCollection $descriptorCollection): array
    {
        $output = [];
        foreach ($descriptorCollection->getEditableDescriptors(true) as $optionKey => $descriptor) {
            $output[$optionKey] = WebValueNormalizer::new()->normalize($descriptor);
        }
        return $output;
    }

    /**
     * @param DescriptorCollection $descriptorCollection
     * @return array
     */
    protected function buildWebNormalizedLocalConfig(DescriptorCollection $descriptorCollection): array
    {
        $output = [];
        foreach ($descriptorCollection->getEditableDescriptors(true) as $optionKey => $descriptor) {
            if ($descriptor->hasLocalValue()) {
                $output[$optionKey] = WebValueNormalizer::new()->normalize($descriptor, true);
            }
        }
        return $output;
    }

}
