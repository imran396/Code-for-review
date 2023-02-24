<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 11, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class TaxSchemaEditFormConstants
 * @package Sam\Core\Constants\Admin
 */
class TaxSchemaEditFormConstants
{
    public const CID_BTN_CANCEL = 'tsef_cancel';
    public const CID_BTN_DELETE = 'tsef_delete';
    public const CID_BTN_SAVE = 'tsef_save';
    public const CID_BTN_SAVE_AND_ADD = 'tsef_save_and_add';
    public const CID_BTN_ADD_DEFINITION = 'tsef_add_definition';
    public const CID_CHK_FOR_INVOICE = 'tsef-for-invoice';
    public const CID_CHK_FOR_SETTLEMENT = 'tsef-for-settlement';
    public const CID_PNL_LOCATION = 'tsef_location';
    public const CID_DTG_DEFINITIONS = 'tsef_definitions';
    public const CID_DLG_SELECT_DEFINITIONS = 'tsef_select_definitions';
    public const CID_TXT_DESCRIPTION = 'tsef_description';
    public const CID_TXT_NAME = 'tsef_name';
    public const CID_TXT_NOTE = 'tsef_note';
    public const CID_RAD_AMOUNT_SOURCE = 'tsef-amount-source';
    public const CID_BTN_DELETE_DEFINITION_TPL = 'tsef_delete_definition_%s';
    public const CID_CHKT_LOT_CATEGORIES = 'tsef-lot-categories'; // don't use underscore, it will break \QTreeNav::GetItem()
    public const CID_LOT_CATEGORIES_TOGGLER = 'tsef-lot-categories-toggler';
}
