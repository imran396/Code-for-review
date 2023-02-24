<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/14/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class ConsignorReportFormConstants
 */
class ConsignorReportFormConstants
{
    public const CID_AUTOCOMPLETE_AUCTION = 'crf01';
    public const CID_CAL_SETTLEMENT_DATE_FROM = 'crf08';
    public const CID_CAL_SETTLEMENT_DATE_TO = 'crf09';
    public const CID_TXT_CONSIGNOR = 'crf10';
    public const CID_HID_CONSIGNOR = 'crf11';
    public const CID_LST_STATUS = 'crf12';
    public const CID_LST_EMAIL_TEMPLATE = 'crf13';
    public const CID_BTN_GENERATE = 'crf02';
    public const CID_BTN_EMAIL = 'crf03';
    public const CID_BTN_PRINT = 'crf04';
    public const CID_DTG_CONSIGNORS = 'crf05';
    public const CID_LST_ITEM_PER_PAGE = 'crf06';
    public const CID_BTN_GENERATE_PDF = 'crf07';
    public const CID_LST_ACCOUNT = 'crf14';
    public const CID_CHK_CONSIGNOR_TPL = '%scCon%s';
    public const CID_BLK_AUCTION_LIST_CONTAINER = 'auction-list-container';
    public const CID_CHK_CHECK_ALL = 'chkall';
    public const CID_CONSIGNOR_REPORT_FORM = 'ConsignorReportForm';

    public const CLASS_CHK_CONSIGNOR = 'consignor';
}
