<?php
/**
 * Buyer Group List Constants
 *
 * SAM-5949: Refactor buyer group list page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupListForm;

/**
 * Class BuyerGroupListConstants
 */
class BuyerGroupListConstants
{
    public const ORD_BY_DEFAULT = 'id';
    public const ORD_BY_NAME = 'name';
    public const ORD_BY_USERS = 'users';
}
