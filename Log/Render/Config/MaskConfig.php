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

namespace Sam\Log\Render\Config;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class MaskConfig
 * @package Sam\Log\Entity\Internal\Config
 */
class MaskConfig extends CustomizableClass
{
    public int $start = 0;
    public ?int $length = null;
    public string $replacement = '.';

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
        if (array_key_exists('start', $configArray)) {
            $config->start = (int)$configArray['start'];
        }
        if (array_key_exists('length', $configArray)) {
            $config->length = Cast::toInt($configArray['length']);
        }
        if (array_key_exists('replacement', $configArray)) {
            $config->replacement = (string)$configArray['replacement'];
        }
        return $config;
    }
}
