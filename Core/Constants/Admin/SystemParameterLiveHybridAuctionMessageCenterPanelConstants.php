<?php
/**
 * SAM-10008: Move sections' logic to separate Panel classes at Manage settings system parameters live/hybrid auction page (/admin/manage-system-parameter/live-hybrid-auction)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 03, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterLiveHybridAuctionMessageCenterPanelConstants
 */
class SystemParameterLiveHybridAuctionMessageCenterPanelConstants
{
    public const CID_CHK_CLEAR_MSG_CENTER = 'apf114';
    public const CID_CHK_TWENTY_MESSAGES_MAX = 'apf115';
    public const CID_CHK_CLEAR_MESSAGE_CENTER_LOG = 'apf116';
    public const CID_DTG_MESSAGES = 'apf129';
    public const CID_HID_MESSAGES_ORDER = 'apf130';
    public const CID_BTN_MESSAGE_CENTER_ADD = 'apf131';
    public const CID_BTN_MESSAGE_CENTER_SAVE = 'apf132';
    public const CID_BTN_MESSAGE_CENTER_CANCEL = 'apf133';
    public const CID_TXT_MESSAGE_CENTER_TITLE = 'apf134';
    public const CID_TXT_MESSAGE_CENTER_TEXT = 'apf135';
    public const CID_BTN_MESSAGE_CENTER_EDIT_TPL = '%sbMcEd%s';
    public const CID_BTN_MESSAGE_CENTER_DELETE_TPL = '%sbMcDel%s';
    public const CID_HID_MESSAGE_CENTER_ORDER_TPL = '%shid%s';
    public const CID_FLA_MESSAGE_CENTER_SOUND_TPL = '%sfla%s';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_CLEAR_MSG_CENTER => Constants\Setting::CLEAR_MESSAGE_CENTER,
        self::CID_CHK_TWENTY_MESSAGES_MAX => Constants\Setting::TWENTY_MESSAGES_MAX,
        self::CID_CHK_CLEAR_MESSAGE_CENTER_LOG => Constants\Setting::CLEAR_MESSAGE_CENTER_LOG,
    ];

    public const CLASS_BTN_EDIT_LINK = 'editlink';
    public const CLASS_BLK_CHECKBOX = 'checkBox';
    public const CLASS_BLK_CHECKMARK = 'checkmark';
}
