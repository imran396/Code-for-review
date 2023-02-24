<?php

/**
 * Decimal-type custom user field editing component
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Jul 20, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @method BaseEdit|DecimalEdit getBaseComponent()
 */

namespace Sam\CustomField\User\Qform\Component;

use Sam\Core\Constants;

/**
 * Class DecimalEdit
 * @method \Sam\CustomField\Base\Qform\Component\BaseEdit getBaseComponent()
 */
class DecimalEdit extends BaseEdit
{
    protected int $type = Constants\CustomField::TYPE_DECIMAL;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
