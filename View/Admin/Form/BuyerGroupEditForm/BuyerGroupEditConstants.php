<?php
/**
 * Buyer Group Edit Constants
 *
 * SAM-5945: Refactor buyer group edit page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 24, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupEditForm;

/**
 * Class BuyerGroupEditConstants
 */
class BuyerGroupEditConstants
{
    public const ORD_BY_DEFAULT = 'id';
    public const ORD_BY_USERNAME = 'username';
    public const ORD_BY_EMAIL = 'email';
    public const ORD_BY_FIRST_NAME = 'first_name';
    public const ORD_BY_LAST_NAME = 'last_name';
    public const ORD_BY_ADDED_ON = 'added_on';
}
