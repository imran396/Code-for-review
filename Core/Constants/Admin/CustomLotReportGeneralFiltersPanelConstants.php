<?php
/**
 * SAM-6905: Move section's logic to Panel classes at Reports - Custom lots page (/admin/manage-reports/custom-lots)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-28, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;


/**
 * Class CustomLotReportGeneralFiltersPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class CustomLotReportGeneralFiltersPanelConstants
{
    public const CID_LST_ACCOUNT = 'lsrf01';
    public const CID_CHK_FILTER_DATE = 'lsrf02';
    public const CID_CAL_DATE_FROM = 'lsrf03';
    public const CID_CAL_DATE_TO = 'lsrf04';
    public const CID_CHK_INCLUDE_WITHOUT_HAMMER_PRICE = 'lsrf06';
    public const CID_LST_TEMPLATE = 'lsrf07';
    public const CID_LST_CATEGORY = 'lsrf11';
    public const CID_LST_CATEGORY_MATCH = 'lsrf12';
    public const CID_BLK_AUCTION_LIST_CONTAINER = 'auction-list-container';
}
