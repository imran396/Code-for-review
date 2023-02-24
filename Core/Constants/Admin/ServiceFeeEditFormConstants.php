<?php
/**
 * SAM-11110: Stacked Tax. New Invoice Edit page: Service Fee Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class ServiceFeeEditFormConstants
 * @package Sam\Core\Constants\Admin
 */
class ServiceFeeEditFormConstants
{
    public const CID_BTN_DELETE = 'iief-delete';
    public const CID_BTN_SAVE = 'iief-save';
    public const CID_LST_TAX_SCHEMA = 'sfef-tax-schema';
    public const CID_LST_TYPE = 'sfef-type';
    public const CID_TXT_AMOUNT = 'sfef-amount';
    public const CID_TXT_NAME = 'sfef-name';
    public const CID_TXT_NOTE = 'sfef-note';

    public const CLASS_BLK_TAX = 'tax';
}
