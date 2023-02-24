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
 * Class EntityDumpConfig
 * @package Sam\Log\Entity\Config
 */
class EntityDumpConfig extends CustomizableClass
{
    protected array $propertyConfigs = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param EntityPropertyConfig $propertyConfig
     */
    public function addPropertyConfig(EntityPropertyConfig $propertyConfig): void
    {
        $this->propertyConfigs[$propertyConfig->propertyName] = $propertyConfig;
    }

    /**
     * @param string $propertyName
     * @return EntityPropertyConfig
     */
    public function getPropertyConfig(string $propertyName): EntityPropertyConfig
    {
        $propertyConfig = $this->propertyConfigs[$propertyName]
            ?? $this->constructDefaultConfig($propertyName);
        return $propertyConfig;
    }

    /**
     * @param array $configArray
     * @return static
     */
    public function fromArray(array $configArray): static
    {
        $entityDumpConfig = self::new();
        foreach ($configArray as $propertyName => $propertyConfigArray) {
            $propertyConfig = EntityPropertyConfig::new()->fromArray($propertyName, $propertyConfigArray);
            $entityDumpConfig->addPropertyConfig($propertyConfig);
        }
        return $entityDumpConfig;
    }

    /**
     * @param string $propertyName
     * @return EntityPropertyConfig
     */
    protected function constructDefaultConfig(string $propertyName): EntityPropertyConfig
    {
        $propertyConfig = EntityPropertyConfig::new();
        $propertyConfig->propertyName = $propertyName;
        $propertyConfig->renderConfig = RenderConfig::new();
        return $propertyConfig;
    }
}
