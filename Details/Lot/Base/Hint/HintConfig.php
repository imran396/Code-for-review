<?php
/**
 *
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Base\Hint;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class HintConfig
 * @package ${NAMESPACE}
 */
class HintConfig extends CustomizableClass
{
    public array $optionals = [
        'beginEndKey' => Constants\LotDetail::PL_NAME,
        'compositeView' => Constants\LotDetail::PL_NAME . '|' . Constants\LotDetail::PL_DESCRIPTION . '[flt=StripTags;Length(20)]|' . Constants\LotDetail::NOT_AVAILABLE,
        'inlineHelpSection' => 'admin_lot_details',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
