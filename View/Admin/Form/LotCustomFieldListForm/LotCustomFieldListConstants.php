<?php
/**
 * Lot Item Cust Fields Constants
 *
 * SAM-6237: Refactor Lot Custom Field List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotCustomFieldListForm;

/**
 * Class LotCustomFieldListConstants
 */
class LotCustomFieldListConstants
{
    public const ORD_ORDER = 'order';
    public const ORD_NAME = 'name';
    public const ORD_DEFAULT = self::ORD_ORDER;
}