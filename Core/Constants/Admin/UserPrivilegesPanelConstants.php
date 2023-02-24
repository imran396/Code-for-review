<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/17/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class UserPrivilegesPanelConstants
 */
class UserPrivilegesPanelConstants
{
    public const CID_CHK_ADMIN_PRIVILEGES = 'chkAdminPrivileges';
    public const CID_CHK_BIDDER = 'chkBidder';
    public const CID_CHK_BUYERS_TAX_BP = 'chkBuyersTaxBp';
    public const CID_CHK_BUYERS_TAX_HP = 'chkBuyersTaxHp';
    public const CID_CHK_BUYERS_TAX_SERVICES = 'chkBuyersTaxServices';
    public const CID_CHK_CONSIGNOR_TAX_COMMISSION = 'chkConsTaxComm';
    public const CID_CHK_CONSIGNOR_TAX_HP = 'chkConsTaxHp';
    public const CID_CHK_CONSIGNOR_TAX_EXC = 'chkConsTaxHpExc';
    public const CID_CHK_CONSIGNOR_TAX_INC = 'chkConsTaxHpInc';
    public const CID_CHK_CONSIGNOR_TAX_SERVICES = 'chkConsTaxServices';
    public const CID_CHK_CONSIGNOR = 'chkConsignor';
    public const CID_CHK_HOUSE_BIDDER = 'chkHouseBidder';
    public const CID_CHK_AGENT = 'chkAgent';
    public const CID_CHK_MANAGER_ARRAY_TPL = 'chkManageArray%s';
    public const CID_CHK_PREFERRED_BIDDER = 'chkPreferredBidder';
    public const CID_LBL_CONSIGNOR_TAX_HP_ERR = 'lblConsTaxHpErr';
    public const CID_TXT_CONSIGNOR_TAX = 'txtConsignorTax';
    public const CID_TXT_SALES_TAX = 'txtSalesTax';

    // Group name
    public const GN_CONS_TAX_HP = 'taxhp';

    public const CLASS_BLK_SECTION_USER_PRIVILEGES = 'section-user-privileges';
}
