<?php
/**
 * SAM-6729: Improve logging - entity dump attribute logging options
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Entity\Config;

use Sam\Core\Service\CustomizableClass;
use Sam\Log\Render\Config\RenderConfig;

/**
 * Class EntityPropertyConfig
 * @package Sam\Log\Entity\Config
 */
class EntityPropertyConfig extends CustomizableClass
{
    public string $propertyName = '';
    public bool $skip = false;
    public bool $showDiff = false;
    public RenderConfig $renderConfig;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $propertyName
     * @param array $configArray
     * @return static
     */
    public function fromArray(string $propertyName, array $configArray): static
    {
        $config = self::new();
        $config->propertyName = $propertyName;
        $config->renderConfig = RenderConfig::new()->fromArray($configArray);
        if (array_key_exists('skip', $configArray)) {
            $config->skip = (bool)$configArray['skip'];
        }
        if (array_key_exists('showDiff', $configArray)) {
            $config->showDiff = (bool)$configArray['showDiff'];
        }
        return $config;
    }
}
