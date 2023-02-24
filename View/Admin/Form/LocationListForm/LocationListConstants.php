<?php
/**
 * Location List Constants
 *
 * SAM-6234: Refactor Location List page at admin side
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

namespace Sam\View\Admin\Form\LocationListForm;

/**
 * Class LocationListConstants
 */
class LocationListConstants
{
    public const ORD_BY_ACCOUNT = 'account';
    public const ORD_BY_ADDRESS = 'address';
    public const ORD_BY_CITY = 'city';
    public const ORD_BY_COUNTRY = 'country';
    public const ORD_BY_COUNTY = 'county';
    public const ORD_BY_CREATED_ON = 'created_on';
    public const ORD_BY_ID = 'id';
    public const ORD_BY_NAME = 'name';
    public const ORD_BY_STATE = 'state';
    public const ORD_BY_ZIP = 'zip';
}
