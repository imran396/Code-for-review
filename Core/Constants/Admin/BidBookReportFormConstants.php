<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class BidBookReportFormConstants
 * @package Sam\Core\Constants\Admin
 */
class BidBookReportFormConstants
{
    public const CID_BLK_PARAMETERS = 'bbrf_parameters';
    public const CID_LST_START_LOT = 'bbrf_start_lot';
    public const CID_LST_END_LOT = 'bbrf_end_lot';
    public const CID_CHK_INCLUDE_IMAGES = 'bbrf_include_images';
    public const CID_CHK_TABULAR = 'bbrf_tabular';
    public const CID_BTN_GENERATE = 'bbrf_gen';
    public const CID_BTN_GENERATE_ALL = 'bbrf_gen_ALL';
}
