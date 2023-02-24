<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\SettlementCheck\Payee\Build\Internal\Render;

/**
 * Class Renderer
 * @package Sam\Details\User\SettlementCheck\Payee\Build\Internal\Render
 */
class Renderer extends \Sam\Details\Core\Render\Renderer
{
    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
