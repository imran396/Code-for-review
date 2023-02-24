<?php
/**
 * SAM-10435: Add csv quick upload form to locations page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Mar 21, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class LocationUploadPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class LocationUploadPanelConstants
{
    public const CID_FLA_CSV_UPLOAD = 'luip17';
    public const CID_BTN_UPLOAD = 'ulf18';
    public const CID_LST_ENCODING = 'ulf25';
    public const CID_ICO_UPLOAD_WAIT = 'ulf16';
    public const CID_BTN_SHOW_LOCATION_BULK_UPLOAD_HELP = 'show-location-bulk-upload-help';
    public const CID_BLK_UPLOAD_ERROR = 'upload-error';
    public const CID_BLK_UPLOAD_PROGRESS = 'upload-progress';
    public const CID_BLK_UPLOAD_ABORT = 'upload-abort';
    public const CID_BLK_CSV_FORMAT = 'csv-format';
    public const CID_IMAGE_AUTO_ORIENT_CHECKBOX = 'ulf32';
    public const CID_IMAGE_OPTIMIZE_CHECKBOX = 'ulf33';
    public const CID_IMG_FILE_LOADER = 'luip34';

    public const CLASS_BLK_LOCATION_BULK_UPLOAD = 'location-bulk-upload';
    public const CLASS_LNK_Q_IMPORT = 'qimport';
}
