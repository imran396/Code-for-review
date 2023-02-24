<?php
/**
 * SAM-6903: Move sections' logic to separate Panel classes at Manage inventory page (/admin/manage-inventory/items)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 14, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class InventoryListQuickImportPanelConstants
 */
class InventoryListQuickImportPanelConstants
{
    public const CID_PNL_LOT_IMPORT = 'ilf1';
    public const CID_BTN_UPLOAD = 'ilf2';
    public const CID_BTN_QUICK_IMPORT = 'section-qimport';
    public const CID_UPLOAD_PROGRESS = 'upload-progress';
    public const CID_UPLOAD_ABORT = 'upload-abort';

    public const CLASS_BLK_ALERT = 'alert';
    public const CLASS_BLK_AUCTION_LOT_IMPORT = 'auction-lot-import';
    public const CLASS_BLK_SEARCH_OPTIONS = 'search-options';
    public const CLASS_CHK_UNASSIGNED = 'unassigned';
}
