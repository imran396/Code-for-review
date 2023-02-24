<?php
/**
 * SAM-6952: Move sections' logic to separate Panel classes at Manage User edit page (/admin/manage-users/edit/id/<UserId>)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-02, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;


/**
 * Class UserEditMoreActionsPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class UserEditMoreActionsPanelConstants
{
    public const CID_BTN_SEND_ACCOUNT = 'btnSendAccount';
    public const CID_BTN_IMPERSONATE = 'btnImpersonate';
    public const CID_BTN_SEND_VERIFICATION = 'btnSendVerification';
    public const CID_BTN_MANUAL_VERIFICATION = 'btnManualVerification';
    public const CID_PNL_LINKS = 'pnlLinks';
    public const CID_BTN_GENERATE_PASSWORD = 'uef29';
}
