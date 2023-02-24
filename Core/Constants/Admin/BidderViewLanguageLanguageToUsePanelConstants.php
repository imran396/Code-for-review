<?php
/**
 * SAM-10081: Move sections' logic to separate Panel classes at Manage settings translations page (/admin/manage-translation)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 04, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class BidderViewLanguageLanguageToUsePanelConstants
 */
class BidderViewLanguageLanguageToUsePanelConstants
{
    public const CID_DTG_LANGUAGES = 'lcf4';
    public const CID_TXT_LANGUAGE_NAME = 'lcf5';
    public const CID_BTN_ADD_LANGUAGE = 'lcf6';
    public const CID_BTN_CANCEL_LANGUAGE = 'lcf7';
    public const CID_BTN_EDIT_TPL = '%sbe%s';
    public const CID_BTN_DELETE_TPL = '%sbd%s';
    public const CID_BTN_SET_DEFAULT_TPL = '%sbs%s';

    public const CLASS_BLK_LANG_TAB = 'langTab';
    public const CLASS_BLK_LANGUAGE_TAB = 'language-tab';
    public const CLASS_BLK_LEGEND = 'legend_div';
    public const CLASS_LNK_CLOSE = 'close';
    public const CLASS_LNK_LANGUAGE = 'language';
    public const CLASS_LNK_OPEN = 'open';
}
