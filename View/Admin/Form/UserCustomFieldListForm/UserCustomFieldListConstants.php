<?php
/**
 * User Custom Field List Constants
 *
 * SAM-6285: Refactor User Custom Field List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserCustomFieldListForm;

/**
 * Class UserCustomFieldListConstants
 */
class UserCustomFieldListConstants
{
    public const ORD_NAME = 'name';
    public const ORD_ORDER = 'order';
    public const ORD_TYPE = 'type';
    public const ORD_PANEL = 'panel';
    public const ORD_REQUIRED = 'required';
    public const ORD_ENCRYPTED = 'encrypted';
    public const ORD_ON_REGISTRATION = 'on_registration';
    public const ORD_ON_PROFILE = 'on_profile';
    public const ORD_PARAMETERS = 'parameters';
    public const ORD_DEFAULT = self::ORD_ORDER;
}