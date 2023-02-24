<?php
/**
 * SAM-6729: Improve logging - entity dump attribute logging options
 * SAM-10338: Redact sensitive information in Soap error.log
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Render\Config;

use Sam\Core\Service\CustomizableClass;

/**
 * Class RenderConfig
 * @package Sam\Log\Render\Config
 */
class RenderConfig extends CustomizableClass
{
    public RenderMode $renderMode = RenderMode::PLAIN;
    public int $maxLength = 2048;
    public bool $trim = false;
    public bool $crc32 = false;
    public ?MaskConfig $mask = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $configArray
     * @return static
     */
    public function fromArray(array $configArray): static
    {
        $config = self::new();
        if (array_key_exists('renderMode', $configArray)) {
            $config->renderMode = is_string($configArray['renderMode'])
                ? RenderMode::from($configArray['renderMode'])
                : $configArray['renderMode'];
        }
        if (array_key_exists('maxLength', $configArray)) {
            $config->maxLength = (int)$configArray['maxLength'];
        }
        if (array_key_exists('trim', $configArray)) {
            $config->trim = (bool)$configArray['trim'];
        }
        if (array_key_exists('crc32', $configArray)) {
            $config->crc32 = (bool)$configArray['crc32'];
        }
        if (array_key_exists('mask', $configArray)) {
            $config->mask = MaskConfig::new()->fromArray($configArray['mask']);
        }
        return $config;
    }
}
