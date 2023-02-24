<?php
/**
 * SAM-6743: Add ability to remove options via web form interface for 'Local config values, that not exists in global configuration ' section
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-21, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Delete\Internal\Helpers;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Load\FileContentLoaderCreateTrait;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;
use Sam\Installation\Config\Edit\Meta\Descriptor\MissingDescriptor;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;

/**
 * Class DeleterHelper
 * @package Sam\Installation\Config\Edit\Delete\Internal
 */
class DeleterHelper extends CustomizableClass
{
    use FileContentLoaderCreateTrait;
    use OptionHelperAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Render web-ready option key html. We use it for render messages.
     * @param string $optionKey
     * @param string $configName
     * @return string
     */
    public function renderOptionKey(string $optionKey, string $configName = ''): string
    {
        $optionHelper = $this->getOptionHelper();
        $normalizedOptionKey = $optionHelper
            ->replaceGeneralDelimiter($optionKey, Constants\Installation::DELIMITER_RENDER_OPTION_KEY);
        if ($configName) {
            $normalizedOptionKey = $optionHelper->prependConfigName($normalizedOptionKey, $configName);
        }
        $output = sprintf('<b>%s</b>', $normalizedOptionKey);
        return $output;
    }

    /**
     * Check is config option allowed for delete.
     * @param Descriptor $descriptor
     * @return bool
     */
    public function isDeletableOption(Descriptor $descriptor): bool
    {
        if ($descriptor instanceof MissingDescriptor) {
            return true;
        }
        return $descriptor->isDeletable() && $descriptor->isEditable();
    }

    /**
     * Fetch actual local config options from local config file
     * and return them as array with flat keys.
     * @param DescriptorCollection $descriptorCollection
     * @return array
     */
    public function getActualLocalOptions(DescriptorCollection $descriptorCollection): array
    {
        $optionHelper = $this->getOptionHelper();
        $configName = $descriptorCollection->getConfigName();
        $localOptionsMultiDim = $this->createFileContentLoader()->loadLocalOptionsMultiDim($configName);
        $descriptors = $descriptorCollection->toArray();
        $multiDimDescriptors = $optionHelper->transformFlatKeyToMultiDimKeyOptions($descriptors);
        $output = $optionHelper->transformMultiDimKeyToFlatKeyArray($localOptionsMultiDim, $multiDimDescriptors);
        return $output;
    }
}
