<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/15/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class LotImageBucketFormConstants
 */
class LotImageBucketFormConstants
{
    public const CID_LST_ASSOCIATE_BY = 'libf2';
    public const CID_IMG_FILE_ICON_ARRAY = 'libf2';
    public const CID_TXT_LOT = 'libf4';
    public const CID_CHK_IMAGE_AUTO_ORIENT = 'libf5';
    public const CID_CHK_IMAGE_OPTIMIZE = 'libf6';
    public const CID_CHK_REMOVE_ASSIGNED_IMAGES = 'libf7';
    public const CID_ICO_IMAGE_FILE_TPL = '%sid%s';
    public const CID_HID_LOT_ITEM = 'hdnlid';
    public const CID_BLK_IMAGE_CONTAINER = 'image-container';
    public const CID_BLK_LOT_IMAGE_CONTAINER = 'lot_image_container';
    public const CID_BLK_WAITING_ICON_BELOW_BUCKET = 'waiting-icon-below-bucket';
    public const CID_BLK_DRAG_HERE = 'drag_here';
    public const CID_LBL_INSERT_STRATEGY = 'insert_strategy_span';
    public const CID_LST_INSERT_STRATEGY = 'insert_strategy';
    public const CID_BTN_MANUALLY = 'manually-btn';
    public const CID_BLK_SELECT_LOT = 'select_lot';
    public const CID_BTN_SAVE_ORDER_LOT = 'save-order-lot';
    public const CID_BTN_PREV = 'prev_btn';
    public const CID_BTN_NEXT = 'next_btn';
    public const CID_BTN_ASSOC_RESULT = 'assoc-result';
    public const CID_BTN_ASSOC_ERRORS = 'assoc-errors';
    public const CID_BTN_SAVE_ORDER = 'save-order-btn';
    public const CID_BLK_IMAGE_TPL = '_image%s';
    public const CID_BLK_UPLOAD_SERVER_ERRORS = 'server-errors';
    public const CID_BLK_LOT_IMAGE_TPL = 'lot_image_%s';
    public const CID_BTN_DELETE_IMAGE_TPL = 'delImg_%s';
    public const CID_BTN_DELETE_LOT_IMAGE_TPL = 'delLotImg_%s';
    public const CID_BTN_DELETE_ALL = 'delAll';
    public const CID_BTN_ASSOCIATE = 'associate-btn';
    public const CID_BTN_ORDER_BY_FILENAME = 'order-by-filename-btn';
    public const CID_LOT_IMAGE_BUCKET_FORM = 'LotImageBucketForm';
    public const CID_SUB_CONTENT = 'sub-content';
    public const CID_BLK_TEMPLATE_UPLOAD = 'template-upload';

    public const STRAT_APPEND = 'ap';
    public const STRAT_PREPEND = 'pr';
    public const STRAT_REPLACE = 're';
    public const DEF_STRATEGY = self::STRAT_PREPEND;

    /** @var array */
    public static array $insertStrategies = [
        self::STRAT_APPEND => 'Append Images',
        self::STRAT_PREPEND => 'Prepend Images',
        self::STRAT_REPLACE => 'Replace Images',
    ];

    public const CLASS_BLK_ASSOCIATING_INDICATOR = 'associating-indicator';
    public const CLASS_BLK_IMAGE_BOX = 'image-box';
    public const CLASS_BLK_IMAGE_CONTAINER = 'image-container';
    public const CLASS_BLK_LIST_BOX = 'listbox';
    public const CLASS_BLK_NEXT_PREV_BTN = 'next_prev_btn';
    public const CLASS_BLK_NEW = 'new';
    public const CLASS_BLK_RIGHT_CONT = 'right_cont';
    public const CLASS_BLK_UI_AUTO_COMPLETE_LOADING = 'ui-autocomplete-loading';
    public const CLASS_BLK_UI_SELECTED = 'ui-selected';
    public const CLASS_BLK_UPLOAD_NOTICE = 'uploadNotice';
    public const CLASS_BTN_CANCEL = 'cancel';
    public const CLASS_BTN_DELETE_IMAGE = 'delImg';
}
