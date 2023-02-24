<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/1/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class LotImportPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class LotImportPanelConstants
{
    public const CID_CSV_FILE_LOADER_LIVE = 'liup1';
    public const CID_IMG_FILE_LOADER_LIVE = 'liup2';
    public const CID_ENCODING_LIVE = 'liup3';
    public const CID_ZIP_FILE_LOADER_LIVE = 'liup4';
    public const CID_CHK_EXISTING_ITEM_NUMBER_LIVE = 'liup5';
    public const CID_CHK_EXISTING_AUCTION_LOT_NUMBER_LIVE = 'liup11';
    public const CID_REPLACE_EXISTING_IMAGES_LIVE = 'liup6';
    public const CID_OUTPUT_BREAKS_LIVE = 'liup7';
    public const CID_OUTPUT_CLEAR_EMPTY_FIELDS_LIVE = 'liup8';
    public const CID_IMAGE_AUTO_ORIENT_CHECKBOX_LIVE = 'liup9';
    public const CID_IMAGE_OPTIMIZE_CHECKBOX_LIVE = 'liup10';

    public const CID_CSV_FILE_LOADER_TIMED = 'liupt1';
    public const CID_IMG_FILE_LOADER_TIMED = 'liupt2';
    public const CID_ENCODING_TIMED = 'liupt3';
    public const CID_ZIP_FILE_LOADER_TIMED = 'liupt4';
    public const CID_CHK_EXISTING_ITEM_NUMBER_TIMED = 'liupt5';
    public const CID_CHK_EXISTING_AUCTION_LOT_NUMBER_TIMED = 'liupt11';
    public const CID_REPLACE_EXISTING_IMAGES_TIMED = 'liupt6';
    public const CID_OUTPUT_BREAKS_TIMED = 'liupt7';
    public const CID_OUTPUT_CLEAR_EMPTY_FIELDS_TIMED = 'liupt8';
    public const CID_IMAGE_AUTO_ORIENT_CHECKBOX_TIMED = 'liupt9';
    public const CID_IMAGE_OPTIMIZE_CHECKBOX_TIMED = 'liupt10';

    public const CID_CSV_FILE_LOADER_INVENTORY = 'liiup1';
    public const CID_IMG_FILE_LOADER_INVENTORY = 'liiup2';
    public const CID_ENCODING_INVENTORY = 'liiup3';
    public const CID_ZIP_FILE_LOADER_INVENTORY = 'liiup4';
    public const CID_CHK_EXISTING_ITEM_NUMBER_INVENTORY = 'liiupt5';
    public const CID_REPLACE_EXISTING_IMAGES_INVENTORY = 'liiupt6';
    public const CID_OUTPUT_BREAKS_INVENTORY = 'liiupt7';
    public const CID_OUTPUT_CLEAR_EMPTY_FIELDS_INVENTORY = 'liiupt8';
    public const CID_IMAGE_AUTO_ORIENT_CHECKBOX_INVENTORY = 'liiup9';
    public const CID_IMAGE_OPTIMIZE_CHECKBOX_INVENTORY = 'liiup10';

    public const CID_BLK_UPLOAD_ERROR = 'upload-error';
    public const CID_BLK_UPLOAD_PROGRESS = 'upload-progress';
    public const CID_BLK_UPLOAD_ABORT = 'upload-abort';
    public const CID_BLK_CSV_FORMAT_TPL = 'csv-format-%s';

    // Import types
    public const IT_LIVE = 'live';
    public const IT_TIMED = 'timed';
    public const IT_INVENTORY = 'inventory';

    public const IMPORT_TYPES = [
        self::IT_LIVE,
        self::IT_TIMED,
        self::IT_INVENTORY,
    ];

    public const CLASS_LNK_SHOW_CSV_FORMAT_IMPORT = 'show-csv-format-import';
}
