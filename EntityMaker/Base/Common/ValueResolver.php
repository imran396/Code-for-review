<?php
/**
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Common;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ValueResolver
 * @package Sam\EntityMaker\Base\Common
 */
class ValueResolver extends CustomizableClass
{
    /**
     * @var array
     */
    protected const TRUE_SIGNS = ['1', 'Y'];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if value is a true sign
     * @param string|null $value
     * @return bool
     */
    public function isTrue(?string $value): bool
    {
        return in_array((string)$value, self::TRUE_SIGNS, true);
    }

}
