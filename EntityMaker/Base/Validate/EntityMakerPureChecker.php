<?php
/**
 * Contain pure validation methods
 *
 * SAM-8829: Extract pure validation methods into EntityMakerPureChecker
 * SAM-6366: Corrections for Auction Lot and Lot Item Entity Makers for v3.5
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Jun 02, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Validate;

use Sam\Core\Service\CustomizableClass;

/**
 * Class EntityMakerPureChecker
 * @package Sam\EntityMaker\Base\Validate
 */
class EntityMakerPureChecker extends CustomizableClass
{
    /**
     * Get instance of Logger
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check value has available length
     * @param string $value
     * @param int $min
     * @param int $max
     * @return bool
     */
    public function isLengthBetween(string $value, int $min, int $max): bool
    {
        return $min <= mb_strlen($value) && mb_strlen($value) <= $max;
    }
}
