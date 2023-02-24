<?php
/**
 * SAM-6908: Move sections' logic to separate Panel classes at Manage Users page (/admin/manage-users)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 19, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class UserListUserBulkUploadPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class UserListUserBulkUploadPanelConstants
{
    public const CID_FLA_CSV_UPLOAD = 'luip17';
    public const CID_FLA_ZIP_UPLOAD = 'luip26';
    public const CID_BTN_UPLOAD = 'ulf18';
    public const CID_LST_ENCODING = 'ulf25';
    public const CID_CHK_RESET = 'ulf31';
    public const CID_ICO_UPLOAD_WAIT = 'ulf16';
    public const CID_BTN_SHOW_USER_BULK_UPLOAD_HELP = 'show-user-bulk-upload-help';
    public const CID_BLK_UPLOAD_ERROR = 'upload-error';
    public const CID_BLK_UPLOAD_PROGRESS = 'upload-progress';
    public const CID_BLK_UPLOAD_ABORT = 'upload-abort';
    public const CID_BLK_CSV_FORMAT = 'csv-format';
    public const CID_IMAGE_AUTO_ORIENT_CHECKBOX = 'ulf32';
    public const CID_IMAGE_OPTIMIZE_CHECKBOX = 'ulf33';
    public const CID_IMG_FILE_LOADER = 'luip34';

    public const CLASS_BLK_USER_BULK_UPLOAD = 'user-bulk-upload';
    public const CLASS_LNK_Q_IMPORT = 'qimport';
}
