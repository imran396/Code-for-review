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

namespace Sam\Installation\Config\Edit\Delete\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;

/**
 * Class DataPreparerForPublisher
 * @package Sam\Installation\Config\Edit\Delete\Internal
 */
class DataPreparerForPublisher extends CustomizableClass
{
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
     * Prepare data (as a multi-dimensional array) ready for publish to local config file.
     * @param string $optionKey
     * @param array $actualLocalOptions local config options with flat keys.
     * @return array
     */
    public function prepare(string $optionKey, array $actualLocalOptions): array
    {
        $readyForPublish = [];
        $deleteOptionKey = array_key_exists($optionKey, $actualLocalOptions) ? $optionKey : null;
        foreach ($actualLocalOptions as $key => $value) {
            if ($deleteOptionKey !== null && $key !== $deleteOptionKey) {
                $readyForPublish[$key] = $value;
            }
        }

        $output = $this->getOptionHelper()->transformFlatKeyToMultiDimKeyOptions($readyForPublish);
        return $output;
    }
}
