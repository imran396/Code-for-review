<?php
/**
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           19.12.2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class LocationEditFormConstants
 * @package Sam\Core\Constants\Admin
 */
class LocationEditFormConstants
{
    public const OK_LOGO_UNCHANGED = 0;
    public const OK_LOGO_UPDATED = 1;
    public const OK_LOGO_DELETED = 2;

    public const CID_ICO_DTG_WAIT = 'lcfe0';
    public const CID_TXT_NAME = 'lcfe1';
    public const CID_FLA_LOGO = 'lcfe2';
    public const CID_TXT_ADDRESS = 'lcfe3';
    public const CID_LST_COUNTRY = 'lcfe4';
    public const CID_LST_US_STATE = 'lcfe5';
    public const CID_LST_CANADA_STATE = 'lcfe6';
    public const CID_LST_MEXICO_STATE = 'lcfe11';
    public const CID_TXT_STATE = 'lcfe7';
    public const CID_TXT_CITY = 'lcfe12';
    public const CID_TXT_COUNTY = 'lcfe13';
    public const CID_TXT_ZIP = 'lcfe8';
    public const CID_BTN_SAVE = 'lcfe9';
    public const CID_BTN_SAVE_ADD_MORE = 'lcfe9b';
    public const CID_BTN_CANCEL = 'lcfe10';
    public const CID_BTN_ADD_TAX_SCHEMA = 'lcfe14';
    public const CID_DTG_TAX_SCHEMA = 'lcfe_dtg_tax_schema';
    public const CID_DLG_TAX_SCHEMA = 'lcfe_dlg_tax_schema';
    public const CID_BTN_TAX_SCHEMA_DELETE = 'ts_delete_tax_%s';
}
