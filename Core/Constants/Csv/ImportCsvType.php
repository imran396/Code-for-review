<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Csv;

/**
 * Class ImportCsvType
 * @package Sam\Core\Constants\Csv
 */
class ImportCsvType
{
    public const BIDDERS = 'Bidders';
    public const BIDS = 'Bids';
    public const INCREMENTS = 'Increments';
    public const LOCATIONS = 'Locations';
    public const LOTS = 'AuctionLots';
    public const USERS = 'Users';
}
