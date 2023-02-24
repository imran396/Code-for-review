<?php
/**
 * SAM-10007: Move sections' logic to separate Panel classes at Manage settings system parameters auction page (/admin/manage-system-parameter/auction)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 01, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class SystemParameterAuctionAdditionalRegistrationOptionsPanelConstants
 */
class SystemParameterAuctionAdditionalRegistrationOptionsPanelConstants
{
    public const CID_BTN_ARO_ADD = 'scf89';
    public const CID_DTG_BIDDER_OPTIONS = 'scf90';
    public const CID_TXT_ARO_NAME_TPL = '%stxtAroName%d';
    public const CID_TXT_ARO_OPTION_TPL = '%stxtAroOption%d';
    public const CID_BTN_ARO_SAVE_TPL = '%sbtnAroSave%d';
    public const CID_BTN_ARO_CANCEL_TPL = '%sbtnAroCancel%d';
    public const CID_TXT_HIDDEN_BIDDER_OPTIONS_ORDER = 'scf97';
    public const CID_LBL_ARO_WARNING = 'scf98';
    public const CID_CHK_ARO_CHOOSE_TPL = '%schkAroChoose%s';
    public const CID_BTN_ARO_EDIT_TPL = '%sbtnAroEdit%s';
    public const CID_BTN_ARO_DELETE_TPL = '%sbtnAroDelete%s';
}
